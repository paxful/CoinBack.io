<?php

class ControlController extends BaseController {

	protected $layout = 'layouts.master';

	/*public function __construct()
	{
		$this->beforeFilter('auth');
	}*/

	public function getIndex()
	{
		return View::make('control');
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
			));

			// TODO blockchain.info wallet
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
