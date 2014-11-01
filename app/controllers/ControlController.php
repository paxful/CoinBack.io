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
			'email'    => 'required|email|email_ignore_case',
			'name' => 'required|max:50',
			'phone-number' => 'max:24',
			'promo-code' => 'sometimes|promo_code'
		);

		// override default message
		$message = array(
			'promo_code' => 'The selected promo code is invalid or already taken.',
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

		// set location for new merchant if found by ip
		$location   = Location::getUserLocationByIp($ip_address);
		if (count($location)) {

		}

		try {
			DB::beginTransaction();

			DB::commit();
		}
		catch(Exception $e)
		{
			DB::rollback();
			Log::error("Merchant registration failed. " . $e->getMessage());
			Log::error($e);
			ApiHelper::sendSMStoAdmins('RuntimeException! Merchant registration does not work!');
			MailHelper::sendAdminWarningEmail('RuntimeException! Merchant registration does not work!', "Error: ".$e);
			return Redirect::to('merchant')
	           ->with('flash_danger', 'Error creating new account. Administrator has been notified')
	           ->withInput();
		}
	}
}
