<?php

class IncomingTransactionTest extends TestCase {

	public function testIncomingTransactionsAverage()
	{
		// stub user
		$user = User::create([
			'business_name' => 'big booties in ur face',
			'email' => 'fake@email.com',
			'phone' => '1221212121',
			'ip_address' => '255.255.255.255',
			'password' => Hash::make('supersecret'),
			'unhashed_password' => 'supersecret',
			'location_id' => 5128581,
			'country_id' => 227,
			'address' => 'skip',
			'post_code' => '10003',
			'bitcoin_address' => '1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU',
			'bitcoin_address_label' => 'first',
			'guid' => 'ae0aabeb-da48-4e39-a35c-f60056b2c538',
			'qr_code_path' => 'random/relative/path'
		]);

		// stub chain.com notification
		$logNotification = new LogNotification(array(
			'notification_id' => 'f69d69d5-d46b-41d5-8ac7-e50bfe0b44b1',
			'callback_url' => 'http://www.bigbooties.com',
			'address' => '1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU'
		));
		$user->logs()->save($logNotification);

		/* first incoming transaction 1.2 btc $380/btc = $456 */
		$input = $this->newChainComAddressCallback(bcmul(1.2, SATOSHI_FRACTION));
		Input::replace($input);
		ApiHelper::$stubBtcPrice = 380;
		$response = $this->action('POST', 'ProcessController@postReceive', null, $input);
		$this->assertEquals('*ok*', $response->getContent());

		/* second incoming transaction - 0.5 btc $340/btc = $170 */
		$input = $this->newChainComAddressCallback(bcmul(0.5, SATOSHI_FRACTION));
		Input::replace($input);
		ApiHelper::$stubBtcPrice = 340;
		$response = $this->action('POST', 'ProcessController@postReceive', null, $input);
		$this->assertEquals('*ok*', $response->getContent());

		$user = User::find(1);
		$this->assertEquals(bcmul(1.7, SATOSHI_FRACTION), $user->bitcoin_balance);
		$this->assertEquals(368, $user->average_rate, null, 0.5);
		$this->assertEquals(626, $user->fiat_total, null, 0.5);

		/* third incoming transaction - 0.8 btc $450/btc = $360 */
		$input = $this->newChainComAddressCallback(bcmul(0.8, SATOSHI_FRACTION));
		Input::replace($input);
		ApiHelper::$stubBtcPrice = 450;
		$response = $this->action('POST', 'ProcessController@postReceive', null, $input);
		$this->assertEquals('*ok*', $response->getContent());

		$user = User::find(1);
		$this->assertEquals(bcmul(2.5, SATOSHI_FRACTION), $user->bitcoin_balance);
		$this->assertEquals(394, $user->average_rate, null, 0.5);
		$this->assertEquals(986, $user->fiat_total, null, 0.5);

//		$authorizedUser = Mockery::mock('Illuminate\Auth\AuthManager');
//		$authorizedUser->shouldReceive('user')->andReturn(true);
//		App::instance('Illuminate\Auth\Manager', $authorizedUser);

		Auth::shouldReceive('user')->andReturn($user = Mockery::mock($user));
		Auth::shouldReceive('check')->andReturn(true);

		/* transaction out FIFO method 0.5 btc @ $410*/
		$premiumPercentage = 13;
		$amountBTC = 0.5;
		$input = ['amountBTC' => $amountBTC, 'bitcoinAddress' => 'mooooskcbTkvQ6DJcHju5V5BicKbEJWaGG', 'premiumInput' => $premiumPercentage];
		ApiHelper::$stubBtcPrice = 362.83;
		$response = $this->action('POST', 'ControlController@postSendPayment', null, $input);

		$session_errors = Session::get('errors');

		$this->assertRedirectedTo('control');
		$this->assertNull($session_errors);
		$this->assertSessionHas('flash_success');

		$user = User::find(1);

		$amountWithFee = bcmul($amountBTC, 1.01, 8);
		$newUserBalance = bcsub(2.5, $amountWithFee, 8);
		$this->assertEquals(bcmul($newUserBalance, SATOSHI_FRACTION), $user->bitcoin_balance);
		$this->assertEquals(398, $user->average_rate, null, 0.5);
		$this->assertEquals(796, $user->fiat_total, null, 3);

		/* transaction out 1 btc @ $415*/
		$initial_user_state = User::find(1);
		$premiumPercentage = 15;
		$amountBTC = 1;
		$input = ['amountBTC' => $amountBTC, 'bitcoinAddress' => 'mooooskcbTkvQ6DJcHju5V5BicKbEJWaGG', 'premiumInput' => $premiumPercentage];
		ApiHelper::$stubBtcPrice = 360.86;
		$response = $this->action('POST', 'ControlController@postSendPayment', null, $input);

		$session_errors = Session::get('errors');

		$this->assertRedirectedTo('control');
		$this->assertNull($session_errors);
		$this->assertSessionHas('flash_success');

		$user = User::find(1);

		$amountWithFee = bcmul($amountBTC, 1.01, 8); // add 1% fee to 1 btc
		$satoshiAmountWithFee = $amountWithFee*SATOSHI_FRACTION;
		$newUserBalance = bcsub($initial_user_state->bitcoin_balance, $satoshiAmountWithFee);
		$this->assertEquals($newUserBalance, $user->bitcoin_balance); // little bit less than 1 btc
		$this->assertEquals(429, $user->average_rate, null, 0.5);
		$this->assertEquals(423, $user->fiat_total, null, 3);

	}

	private function newChainComAddressCallback($amount) {
		return [
			'id' => 'f69d69d5-d46b-41d5-8ac7-e50bfe0b44b1',
			'created_at' => '2014-10-20T18:27:16Z',
			'delivery_attempt' => 1,
			'notification_id' => 'f69d69d5-d46b-41d5-8ac7-e50bfe0b44b1',
			'payload' => [
				'type' => 'address',
				'address' => '1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU',
				'block_chain' => 'bitcoin',
				'sent' => 0,
				'received' => $amount,
				'input_addresses' => ['1FfmbHfnpaZjKFvyi1okTjJJusN455paPH'],
				'output_addresses' => ['1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU'],
				'transaction_hash' => hash('sha256', rand()),
				'block_hash' => '00000000000004758',
				'confirmations' => 0
			]
		];
	}

	protected function getAuthMock($isLoggedIn)
	{
		$authMock = Mockery::mock('Illuminate\Auth\AuthManager');
		$authMock->shouldReceive('user')->once()->andReturn($isLoggedIn);
		return $authMock;
	}
} 