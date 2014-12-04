<?php

use Illuminate\Auth\AuthManager;
use Illuminate\Support\Facades\Redirect;

class ControlController extends BaseController {

	protected $layout = 'layouts.master';
	protected $user;
	protected $auth;

	/* some simple inject for unit testing purposes */
	public function __construct(AuthManager $auth)
	{
		$this->beforeFilter('auth');
		$this->auth = $auth;
		$this->user = $auth->user();
	}

	public function getIndex()
	{
		$transactions = Transaction::where('user_id', $this->user->id)->orderBy('id', 'desc')->get();
		$countries = LocationHelper::getCountriesList();
		return View::make('control', array('transactions' => $transactions, 'country' => $countries));
	}

	public function postSendPayment()
	{
		$amountBtc          = Input::get('amountBTC');
		$email              = Input::get('email');
		$bitcoinAddress     = Input::get('bitcoinAddress');
		$premiumPercentage  = intval(Input::get('premiumInput'));
		$sendAmount         = abs(bcmul($amountBtc, SATOSHI_FRACTION)); // in satoshis without fee and also absolute value
		$sendAmountWithFee  = bcmul($sendAmount, 1.01); // add the fee

		$user = $this->user;

		$validator = Validator::make(Input::all(), ['email' => 'sometimes|email']); // if email set, validate it
		if ($validator->fails()) {
			return Redirect::to('/control')->with('flash_error', trans('validation.email', ['attribute' => 'email ']));
		}

		// not logged in
		if (!$this->auth->check()) {
			return Redirect::to('/')->with('flash_error', 'Please login first');
		}

		// amount in satoshis is not integer OR merchant doesn't have enough balance (1.01 - 1% is the fee) OR amount is negative
		if (!is_int($sendAmount) or $user->bitcoin_balance < $sendAmountWithFee or $sendAmountWithFee <= 0) {
			return Redirect::to('control')->with('flash_error', 'Not enough balance');
		}

		// bitcoin address is set but is incorrect
		if ($bitcoinAddress and !BitcoinHelper::validateBitcoinAddress($bitcoinAddress)) {
			return Redirect::to('control')->with('flash_error', 'Invalid bitcoin address');
		}

		/* premium input is not integer
		|* if some smart hacker as a merchant changes the premium percentage
		\* he fucks up only his ledger and correct balance and doesn't harm system itself */
		if ( !is_int($premiumPercentage) ) {
			return Redirect::to('control')->with('flash_error', 'Invalid premium percentage');
		}

		$availableIncomingTransactions = Transaction::availableCoinsForSale($user->id);
		$remainingAmountToSend = $sendAmountWithFee;
		foreach ($availableIncomingTransactions as $tx) {
			// enough btc in first available transaction, subtract from that
			if ($tx->remaining_bitcoin >= $remainingAmountToSend)
			{
				$tx->remaining_bitcoin = bcsub($tx->remaining_bitcoin, $remainingAmountToSend); // set new remaining btc in that transaction
				if ($tx->remaining_bitcoin == $remainingAmountToSend)
				{
					$tx->sold = true;
				}
				$tx->save();
				break; // all send amount covered, break out from loop
			}
			else
			{
				$remainingAmountToSend = bcsub($remainingAmountToSend, $tx->remaining_bitcoin);
				$tx->remaining_bitcoin = 0;
				$tx->sold = true;
				$tx->save();
			}
		}
		// recalculate user records - average, fiat and crypto balances
		$recalculatedRecords = $this->recalculateRecords( $user );

		$bitcoinMarketPrice     = ApiHelper::getBitcoinPrice(); // get price from backend
		$amountFiatNoPremium    = bcmul($amountBtc, $bitcoinMarketPrice, 2);

		$totalPercentage = bcadd($premiumPercentage, 100);
		$finalMarketPrice = bcmul($bitcoinMarketPrice, $totalPercentage/100, 2);

		$amountFiatWithPremium = bcmul($amountBtc, $finalMarketPrice, 2);
		$btcAveragePrice = bcmul($user->average_rate, $amountBtc, 2); // average for sold bitcoins
		$btcFinalPrice  = bcmul($finalMarketPrice, $amountBtc, 2); // final price for sold bitcoins
		$saleProfit = bcsub($btcFinalPrice, $btcAveragePrice, 2); // profit from that sale

		// calculate profit, update new average and balances
		$user->average_rate = $recalculatedRecords['new_average'];
		$user->fiat_total = $recalculatedRecords['fiat_amount'];
		$user->bitcoin_num_transactions = $user->bitcoin_num_transactions + 1;
		$user->bitcoin_balance = bcsub($user->bitcoin_balance, $sendAmountWithFee); // subtract bitcoin balance
		$user->total_profit = bcadd($user->total_profit, $saleProfit, 2);
		$user->save();

		// add transaction row
		$sentTxModel = Transaction::create(array(
			'user_id'                   => $user->id,
			'type'                      => 'sent',
			'bitcoin_amount'            => $sendAmount,
			'fiat_amount'               => $amountFiatNoPremium,
			'fiat_amount_market_rate'   => $amountFiatWithPremium,
			'fiat_currency_id'          => 144,
			'fee'                       => bcmul($sendAmount, FEE),
			'confirms'                  => 0,
			'bitcoin_current_rate_usd'  => $bitcoinMarketPrice,
			'bitcoin_premium_exchange_rate' => $finalMarketPrice,
			'sale_profit'               => $saleProfit,
			'tax'                       => bcmul($saleProfit, TAX/100, 2), // just informational for user, not used in any calculations
		));

		// if email is set in form, it means send coins to email address
		if ($email) {
			// create new wallet for user
			$password = substr(hash('sha512',rand()),0,12);
			$newWalletResponse = json_decode(BCInfoHelper::createNewWallet($password, $email));
			$newGuid = $newWalletResponse->guid;
			$bitcoinAddress = $newWalletResponse->address;

			$mailData = array(
				'email' => $email,
				'subject' => "$amountFiatNoPremium USD worth of bitcoin purchased",
				'text' => "You bought $$amountFiatNoPremium worth of bitcoin ( $amountBtc BTC ) at a rate of $$bitcoinMarketPrice from ".$user->business_name." on ".date('l jS \of F Y h:i:s')."\n\n
					The funds are in your wallet at www.blockchain.info/wallet/login\n
					email: $email\n
					guid: $newGuid\n
					password: $password",
			);

			MailHelper::sendEmailPlain($mailData);
		}

		$jsonResp = BCInfoHelper::sendManyPayment(
			$user->guid,
			Crypt::decrypt($user->encrypted_password),
			$bitcoinAddress,
			$sendAmount,
			bcmul($sendAmount, FEE) // fee goes to coinback.io maintainer
		);
		if (isset($jsonResp->tx_hash)) {
			$sentTxModel->transaction_hash = $jsonResp->tx_hash;
			$sentTxModel->note = $jsonResp->message;
		} else {
			// failed to send payment, log and message merchant and us to email
			$sentTxModel->note = $jsonResp->error;
			$this->logPaymentFailure( $user, $sentTxModel, $email, $bitcoinAddress );
		}
		$sentTxModel->save();

		return Redirect::to('control')->with('flash_success', "$amountBtc BTC sent successfully to $email");
	}

	public function getBillCard()
	{
		if (!$this->auth->check())
		{
			return Redirect::to('/')->with('flash_warning', 'Please login to view the page.');
		}

		$billCardPath = ImageHelper::createBillCard($this->user->bitcoin_address, public_path($this->user->qr_code_path), Input::get('type'));

		if (!$billCardPath) {
			return Redirect::intended('/')->with('flash_warning','Unknown bill-card chosen');
		} else {
			return Response::download($billCardPath, null, array('Content-Type' => 'image/png'));
		}
	}

	public function postUpdate()
	{
		static $alertContainer = '<div class="alert alert-%s" role="alert">%s</div>';

		if (Session::token() != Input::get('token'))
		{
			return Response::json(array('status' => 'fail',
			                            'message' => sprintf($alertContainer, 'warning', 'System error, administrator has been notified')));
		}

		$businessName   = e(Input::get('business_name'));
		$phone          = preg_replace('/\D/', '', Input::get('phone')); // store only numbers
		$location_id    = Input::get('location_id');
		$country_id     = e(Input::get('country_id'));
		$address        = e(Input::get('address'));
		$post_code      = e(Input::get('post_code'));
		$tax            = e(Input::get('tax'));

		$rules = array(
			'business_name' => 'required',
			'phone'         => 'required',
			'location_id'   => 'required|integer',
			'tax'   => 'numeric',
		);

		// override default message
		$message = array(
			'location_id.required' => trans('web.location_not_set')
		);
		$validator = Validator::make(Input::all(), $rules, $message);

		// validate
		if ($validator->fails())
		{
			$messages = $validator->messages();
			$errorMessages = '';
			foreach ($messages->all('<li>:message</li>') as $message)
			{
				$errorMessages .= $message;
			}
			return Response::json(array('status' => 'fail', 'message' => sprintf($alertContainer, 'warning', '<ul>'.$errorMessages.'</ul>')));
		}

		$this->user->business_name  = $businessName;
		$this->user->phone          = $phone;
		$this->user->location_id    = $location_id;
		$this->user->country_id     = $country_id;
		$this->user->address        = $address;
		$this->user->post_code      = $post_code;
		$this->user->tax            = $tax;
		$this->user->save();

		return Response::json([
			'status' => 'success',
			'message' => sprintf($alertContainer, 'info', trans('web.account_details_changed'))
		]);

	}

	/**
	 * @param $user
	 *
	 * @return string
	 */
	private function recalculateRecords( $user ) {
		$remainingReceivedTxs = Transaction::availableCoinsForSale( $user->id );
		$totalFiatRemaining   = $totalSatoshisRemaining = 0;
		foreach ( $remainingReceivedTxs as $tx )
		{
			$totalSatoshisRemaining = bcadd( $tx->remaining_bitcoin, $totalSatoshisRemaining );
			$remaining_btc_amount = bcdiv($tx->remaining_bitcoin, SATOSHI_FRACTION, 8);
			$remaining_fiat_amount = bcmul($remaining_btc_amount, $tx->bitcoin_current_rate_usd, 2);
			$totalFiatRemaining = bcadd($remaining_fiat_amount, $totalFiatRemaining, 2);
		}

		$totalBtcRemaining = bcdiv( $totalSatoshisRemaining, SATOSHI_FRACTION, 8 );
		$newAverage        = bcdiv( $totalFiatRemaining, $totalBtcRemaining, 2 );

		return [
			'new_average'   => $newAverage,
			'user_balance'  => $totalSatoshisRemaining,
			'fiat_amount'   => $totalFiatRemaining,
		];
	}

	/**
	 * @param $user
	 * @param $sentTxModel
	 * @param $email
	 * @param $bitcoinAddress
	 *
	 * @return array
	 */
	private function logPaymentFailure( $user, $sentTxModel, $email, $bitcoinAddress ) {
		Log::error( 'Failed to send out payment by merchant: ' . $user->business_name . ' (' . $user->email . ')' );
		ApiHelper::sendSMStoAdmins( 'Failed to send out payment by merchant: ' . $user->business_name . ' (' . $user->email . ')' ); // redo to queue
		MailHelper::sendAdminWarningEmail(
			'CRITICAL! Failed sending payment by merchant',
			'Transaction id: ' . $sentTxModel->id . ', ' . $user->business_name . ' (' . $user->email . ')'
		); // redo to queue
		$mailData = array(
			'email'   => $user->email,
			'subject' => 'Failed to send out payment',
			'text'    => 'Payment wasn\'t sent out to ' . $email . ' ' . $bitcoinAddress
		);
		MailHelper::sendEmailPlain( $mailData ); // notify merchant via email. redo to queues
	}
}
