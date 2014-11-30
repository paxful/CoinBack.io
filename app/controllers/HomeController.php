<?php

class HomeController extends BaseController {

	protected $layout = 'layouts.master';

	public function getIndex()
	{
		if (Auth::check()) {
			return Redirect::to('control');
		}
		$countries = Cache::rememberForever('countries', function() {
			return Country::orderBy('sort_id', 'desc')->lists('name', 'id');
		});

		return View::make('index', array('country' => $countries));
	}

	public function postLogin()
	{
		static $alertContainer = '<div class="alert alert-%s" role="alert">%s</div>';
		if (Session::token() != Input::get('token'))
		{
			Log::warning('Login hack attempt. Token didn\'t match, input data: '.json_encode(Input::all()));
			return Response::json(array('status' => 'fail', 'message' => sprintf($alertContainer, 'warning', trans('web.system_error'))));
		}

		if (Auth::attempt(array('email' => Input::get('email'), 'password' => Input::get('password')))) {
			$logged_in_user = Auth::user();
			$logged_in_user->date_last_login = date('Y-m-d H:i:s');
			$logged_in_user->login_times = $logged_in_user->login_times + 1;
			$logged_in_user->save();
			$redirectUrl = App::environment('production') ? secure_url('control') : url('control'); // if production, then https, otherwise http
			return Response::json(array('status' => 'success', 'redirect' => $redirectUrl, 'message' => sprintf($alertContainer, 'success', 'Logged in successfully, redirecting to your dashboard...')));
		}
		return Response::json(array('status' => 'fail', 'message' => sprintf($alertContainer, 'warning', 'Incorrect email or password')));
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
			return Redirect::to('/#merchantRegister')
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

		$location = App::environment('testing') ? Location::find(5128581) : Location::find($locationId);

		try {
			DB::beginTransaction();

			$newWallet = json_decode(BCInfoHelper::createNewWallet($password, $email));

			// TODO qr code path, image
			$qrCodePath = ImageHelper::createDefaultQrCode($newWallet->address); // create default qr code for user

			$user = User::create(array(
				'business_name' => $business_name,
				'email' => $email,
				'phone' => $phone,
				'ip_address' => $ip_address,
				'password' => Hash::make($password),
				'unhashed_password' => $password,
				'location_id' => $locationId,
				'country_id' => $location->country->id,
				'address' => $address,
				'post_code' => $post_code,
				'bitcoin_address' => $newWallet->address,
				'bitcoin_address_label' => 'first',
				'guid' => $newWallet->guid,
				'qr_code_path' => $qrCodePath
			));

			/* create listener for that address. whenever this address receives payment, our system is notified */
			$chainComJsonResponse = json_decode(ChainComHelper::createAddressNotification($newWallet->address));

			// if notification created
			if (isset($chainComJsonResponse->id)) {
				$logNotification = new LogNotification(array(
					'notification_id' => $chainComJsonResponse->id,
					'callback_url' => $chainComJsonResponse->url,
					'address' => $chainComJsonResponse->address
				));
				$user->logs()->save($logNotification);
			} else {
				// otherwise create later manually, don't let new user mess with his experience and let him proceed
				Log::error('Notification for new merchant ('.$email.') was not created for following address: '.$newWallet->address);
				MailHelper::sendAdminWarningEmail('Failed to create notification for merchant', "Merchant ($email), address: " . $newWallet->address);
			}

			DB::commit();

			Log::info('New user created');

			$mailData = array(
				'email' => $email,
				'subject' => "Welcome to CoinBack.io. Start selling bitcoin today",
				'text' => "Hello $business_name\n\nWelcome to CoinBack.io\n\nYour password is $password\nEmail: $email\nPhone: $phone\n\nTo get started selling bitcoin you need to have some to sell first."
			);
			MailHelper::sendEmailPlain($mailData);

			Auth::login($user);

			return Redirect::to('control/?registered=1')->with('flash_success', 'You have successfully signed up.');
		}
		catch(Exception $e)
		{
			DB::rollback();
			Log::error("Merchant registration failed. " . $e->getMessage());
			Log::error($e);
//			ApiHelper::sendSMStoAdmins('RuntimeException! Merchant registration does not work!');
			MailHelper::sendAdminWarningEmail('Merchant registration does not work!', "Error: ".$e);
			return Redirect::to('/#merchantRegister')
			               ->with('flash_danger', 'Error creating new account. Administrator has been notified')
			               ->withInput();
		}
	}

	public function postLocationByIp()
	{
		// local env gives 127.0.0.1 so its not in locations table
		$ip_address = App::environment('local', 'testing') ? '69.162.16.13' : Request::getClientIp();

		$location = Location::getUserLocationByIp($ip_address);
		if (count($location))
		{
			return Response::json(array(
				'status' => 'success',
				'location' => $location->toJson()
			));
		}
		return Response::json(array('status' => 'fail'));
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
