<?php

class RemindersController extends BaseController {

	protected $layout = 'layouts.master';

	/**
	 * Display the password reminder view.
	 *
	 * @return Response
	 */
	public function getRemind()
	{
		return View::make('password.remind');
	}

	public function postRemind() {
		$email = Input::only( 'forgotPassEmail' );
		switch ( $response = Password::remind( array( 'email' => $email ), function ( $message )
				{
					$message->subject( 'Password reset at CoinBack' );
				}
		))
		{
			case Password::INVALID_USER:
				return Response::json( array( 'status' => 'error', 'message' => trans($response) ) );

			case Password::REMINDER_SENT:
				return Response::json( array( 'status' => 'success', 'message' => trans($response) ) );
		}
	}

	/**
	 * Display the password reset view for the given token.
	 *
	 * @param  string  $token
	 * @return Response
	 */
	public function getReset($token = null)
	{
		if (is_null($token)) return Redirect::to('merchant')->with('flash_warning', 'Token not specified');

		return View::make('password.reset')->with('token', $token);
	}

	/**
	 * Handle a POST request to reset a user's password.
	 *
	 * @return Response
	 */
	public function postReset()
	{
		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);

		$response = Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();
		});

		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('flash_warning', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::to('merchant')->with('flash_info', 'New password set successfully.');
		}
	}

}
