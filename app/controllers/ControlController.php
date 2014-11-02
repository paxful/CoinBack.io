<?php

class ControlController extends BaseController {

	protected $layout = 'layouts.master';

	public function __construct()
	{
		$this->beforeFilter('auth');
	}

	public function getIndex()
	{
		$transactions = Transaction::where('user_id', Auth::id())->get();
		return View::make('control', array('transactions' => $transactions));
	}

	public function postRegister()
	{
		$rules = array(
			'business_name' => 'required',
			'email'    => 'required|email|email_ignore_case',
			'phone' => 'required'
		);

		// override default message
		$message = array(
			'email_ignore_case' => 'The selected email is already in use.'
		);
		$validator = Validator::make(Input::all(), $rules, $message);

		// if the validator fails, redirect back to the form
		if ($validator->fails()) {
			return Redirect::to('/')
               ->withErrors($validator) // send back all errors to the registration form
               ->withInput(); // send back the input (not the password) so that we can repopulate the form
		}

		$business_name =  Input::get('business_name');
		$email = Input::get('email');
		$ip_address = Request::getClientIp();
		$password = substr(hash('sha512',rand()),0,12);
		$phone = Input::get('phone');

		$locationId = Input::get('location_id');
		$address = Input::get('address');
		$post_code = Input::get('post_code');

		$location = Location::find($locationId);

		try {
			DB::beginTransaction();

			$newWallet = json_encode(BCInfoHelper::createNewWallet($password, $email));

			$user = User::create(array(
				'business_name' => $business_name,
				'email' => $email,
				'phone' => $phone,
				'ip_address' => $ip_address,
				'password' => Hash::make($password),
				'location_id' => $locationId,
				'country_id' => $location->country->id,
				'address' => $address,
				'post_code' => $post_code,
				'bitcoin_address' => $newWallet->address,
				'bitcoin_address_label' => 'first',
				'guid' => $newWallet->guid,
			));

			// TODO qr code path, image
			// TODO chain.com notification


			DB::commit();

			return Redirect::to('control/?registered=1')->with('flash_success', 'You have successfully signed up.');
		}
		catch(Exception $e)
		{
			DB::rollback();
			Log::error("Merchant registration failed. " . $e->getMessage());
			Log::error($e);
//			ApiHelper::sendSMStoAdmins('RuntimeException! Merchant registration does not work!');
//			MailHelper::sendAdminWarningEmail('RuntimeException! Merchant registration does not work!', "Error: ".$e);
			return Redirect::to('/')
	           ->with('flash_danger', 'Error creating new account. Administrator has been notified')
	           ->withInput();
		}
	}

	public function postSendPayment()
	{
		$amountBtc = Input::get('amountBTC');
		$email = Input::get('email');
		$amountSatoshi = bcmul($amountBtc, 100000000);

		$user = Auth::user();

		// validate if merchant has enough balance
		if ($user->bitcoin_balance < $amountSatoshi) {
			return Redirect::to('control')->with('flash_error', "Not enough balance");
		}

	    $bitcoinMarketPrice = ApiHelper::getBitcoinPrice(); // get price from backend
		$amountCurrency = bcmul($amountBtc, $bitcoinMarketPrice, 2);

		// create new wallet for user
		$password = substr(hash('sha512',rand()),0,12);
		$newWalletResponse = json_decode(BCInfoHelper::createNewWallet($password, $email));
		$newGuid = $newWalletResponse->guid;

		BCInfoHelper::sendPayment($user->guid, $user->unhashed_password, $newWalletResponse->address, $amountSatoshi, $user->bitcoin_address);

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

		// calculate profit
		$user->bitcoin_balance = bcsub($user->bitcoin_balance, $amountSatoshi); // subtract bitcoin balance
		$user->total_profit = 0.2; // TODO change
		$user->save();

		// add transaction row
		Transaction::create(array(
			'user_id' => $user->id,
			'type' => 'sent',
			'bitcoin_amount' => $amountSatoshi,
			'fiat_amount' => $amountCurrency,
			'fiat_currency_id' => 144,
			'transaction_hash' => 'xxxxxxxx', // TODO hash
			'confirms' => 0,
			'bitcoin_current_rate_usd' => $bitcoinMarketPrice,
			'remaining_bitcoin' => $amountSatoshi,
			'sale_profit' => 0.2 // TODO change
		));

		// TODO recalculate average price
		// TODO profit, tax

		return Redirect::to('control')->with('flash_success', "$amountBtc BTC sent successfully to $email");
	}

	public function getBillCard()
	{
		if (!Auth::check())
		{
			return Redirect::to('/')->with('flash_warning', 'Please login to view the page.');
		}

		$billCardPath = ImageHelper::createBillCard(Auth::user()->bitcoin_address, public_path(Auth::user()->qr_code_path), Input::get('type'));

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
