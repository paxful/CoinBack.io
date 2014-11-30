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
		$transactions = Transaction::where('user_id', $this->user->id)->get();
		return View::make('control', array('transactions' => $transactions));
	}

	public function postSendPayment()
	{
		$amountBtc      = Input::get('amountBTC');
		$email          = Input::get('email');
		$bitcoinAddress = Input::get('bitcoinAddress');
		$premiumPercentage = intval(Input::get('premiumInput'));
		$sendAmount     = bcmul($amountBtc, SATOSHI_FRACTION);

		$user = $this->user;

		// merchant doesn't have enough balance
		if ($user->bitcoin_balance < $sendAmount) {
			return Redirect::to('control')->with('flash_error', 'Not enough balance');
		}

		// bitcoin address is set but is incorrect
		if ($bitcoinAddress and !BitcoinHelper::validateBitcoinAddress($bitcoinAddress)) {
			return Redirect::to('control')->with('flash_error', 'Invalid bitcoin address');
		}

		// premium input is not integer
		if ( !is_int($premiumPercentage) ) {
			return Redirect::to('control')->with('flash_error', 'Invalid premium percentage');
		}

	    $bitcoinMarketPrice = ApiHelper::getBitcoinPrice(); // get price from backend
		$amountCurrency = bcmul($amountBtc, $bitcoinMarketPrice, 2);

		// calculate with premium
		$totalPercentage = bcadd($premiumPercentage, 100);
		$percentageWithFee = bcadd($totalPercentage, 1);
		$finalMarketPrice = bcmul($bitcoinMarketPrice, $percentageWithFee/100, 2);
		$feeFiat = bcmul($finalMarketPrice, 0.9, 2);

		$availableIncomingTransactions = Transaction::availableCoinsForSale($user->id);
		foreach ($availableIncomingTransactions as $tx) {
			if ($tx->remaining_bitcoin > $sendAmount) {
				$tx->remaining_bitcoin = bcsub($tx->remaining_bitcoin, $sendAmount); // set new remaining btc in that transaction
			} else {
				$overAmount = bcsub($sendAmount, $tx->remaining_bitcoin);
			}
			// TODO recalculate average
		}

		// calculate profit
		$user->bitcoin_balance = bcsub($user->bitcoin_balance, $sendAmount); // subtract bitcoin balance
		$user->total_profit = 0.2; // TODO change
		$user->save();

		// add transaction row
		Transaction::create(array(
			'user_id' => $user->id,
			'type' => 'sent',
			'bitcoin_amount' => $sendAmount,
			'fiat_amount' => $amountCurrency,
			'fiat_currency_id' => 144,
			'transaction_hash' => 'xxxxxxxx', // TODO hash
			'confirms' => 0,
			'bitcoin_current_rate_usd' => $bitcoinMarketPrice,
			'remaining_bitcoin' => $sendAmount,
			'sale_profit' => 0.2 // TODO change
		));

		// TODO recalculate average price
		// TODO profit, tax

		if ($bitcoinAddress) {

		} else if ($email) {

		} else {

		}

		// create new wallet for user
		$password = substr(hash('sha512',rand()),0,12);
		$newWalletResponse = json_decode(BCInfoHelper::createNewWallet($password, $email));
		$newGuid = $newWalletResponse->guid;

		// TODO get response from blockchain.info
		BCInfoHelper::sendPayment($user->guid, $user->unhashed_password, $newWalletResponse->address, $sendAmount, $user->bitcoin_address);

		$mailData = array(
			'email' => $email,
			'subject' => "$amountCurrency USD worth of bitcoin purchased",
			'text' => "You bought $$amountCurrency worth of bitcoin ( $amountBtc BTC ) at a rate of $$bitcoinMarketPrice from ".$user->business_name." on ".date('l jS \of F Y h:i:s')."\n\n
				The funds are in your wallet at www.blockchain.info/wallet/login\n
				email: $email\n
				guid: $newGuid\n
				password: $password",
		);

		MailHelper::sendEmailPlain($mailData);

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

	public function postLocationByIp()
	{
		// local env gives 127.0.0.1 so its not in locations table
		$ip_address = App::environment('local') ? '69.162.16.13' : Request::getClientIp();

		$location = Location::getUserLocationByIp($ip_address);
		if (count($location))
		{
			return Response::json(array(
				'status' => 'success',
				'location' => $location->toJson()
			));
		}
		else
		{
			return Response::json(array('status' => 'fail'));
		}
	}

	public function postCountriesList()
	{
		return Country::all()->lists('name', 'id'); // for select dropdown <option>
	}

	public function postStatesList()
	{
		return Location::getCountryStates(e(Input::get('country_id')));
	}

	public function postCitiesList()
	{
		$state = e(Input::get('state'));
		$country_id = e(Input::get('country_id'));
		$results = DB::connection('pgsql2')->table('locations')
		             ->select(DB::raw('DISTINCT ON (city_name) id, city_name'))
		             ->where('subdivision_iso_code', $state)
		             ->where('country_id', $country_id)
		             ->orderBy('city_name')
		             ->orderBy('id')
		             ->get();
		return $results;
	}
}
