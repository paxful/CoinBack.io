<?php

class PasswordResetTest extends TestCase {

	public function testPasswordReset()
	{
		$email = 'artur@easybitz.com';
		/* sign up a user */
		$input = [
			'business_name' => 'We love big booties',
			'email' => $email,
			'phone' => '+123231321',
			'post_code' => '10003',
		];
		Input::replace($input);

		$this->action('POST', 'HomeController@postRegister', null, $input);

		$session_errors = Session::get('errors');

		$this->assertRedirectedTo('control/?registered=1');
		$this->assertNull($session_errors);
		$this->assertSessionHas('flash_success');
		$newUser = User::where('email', $email)->first();
		$this->assertEquals($email, $newUser->email);

		/* go to control page as logged in user*/
		// laravel doesn't have followRedirect, so have to get the logged in user path
		// also laravel $this->client->request('GET', 'control') does not work, so can't do filter('h3:contains("Get started")'
		$loggedInResponse = $this->action('GET', 'ControlController@getIndex');
		$this->assertTrue($loggedInResponse->isOk());
		$this->assertViewHas('transactions');

		/* log out user */
		$this->call('GET', '/logout');
		$this->assertRedirectedTo('/#merchantRegister');

		/* reset password */
		$input = [
			'forgotPassEmail' => $email,
		];
		Input::replace($input);

		$userResetPasswordJsonResponse = $this->action('POST', 'RemindersController@postRemind', null, $input);
		$jsonData = $userResetPasswordJsonResponse->getContent();
		$data = json_decode($jsonData);
		$this->assertEquals('success', $data->status);

		$user = DB::table('password_reminders')->where('email', $email)->first();
		$token = $user->token;
		$this->assertNotNull($token);

		/* go to password reset page */
		Session::start(); // used for csrf token
		$crawler = $this->client->request('GET', '/password/reset/140ffb19148f701b08e79d210010ffee9414fa4e');
		$this->assertTrue($this->client->getResponse()->isOk());
		$this->assertCount(1, $crawler->filter('h1:contains("Submit your new password")'));

		/* submit new password */
		$input = [
			'token' => $token,
			'email' => $email,
			'password' => '1234567890',
			'password_confirmation' => '1234567890',
		];
		Input::replace($input);

		$this->action('POST', 'RemindersController@postReset', null, $input);
		$this->assertSessionHas('password_reset');
	}

}
