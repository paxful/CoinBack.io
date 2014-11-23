<?php namespace Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class BCInfoHelper {

	static $NEW_WALLET_URL = 'https://blockchain.info/api/v2/create_wallet';
	static $MERCHANT_URL = 'https://blockchain.info/merchant/';

	public static function createNewWallet($password, $email, $label = 'first') {
		if (App::environment('testing')) {
			// random address and guid for unit testing
			return json_encode(array('address' => '1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU', 'guid' => 'ae0aabeb-da48-4e39-a35c-f60056b2c538'));
		}
		$fullUrl = self::$NEW_WALLET_URL.'?password='.$password.'&email='.$email.'&label='.$label.'&api_code='.$_ENV['BCINFO_KEY'];
		$response = ApiHelper::runUrl($fullUrl);
		 // TODO dont log the pass to log file, log it to db
		Log::info('New blockchain.info wallet created, email: '.$email.', pass: '.$password.'. Response: '.$response);
		return $response;
	}

	public static function sendPayment($guid, $pass, $to, $amountSatoshis, $from) {
		$fullUrl = self::$MERCHANT_URL.$guid.'/payment?password='.$pass.'&to='.$to.'&amount='.$amountSatoshis.'&from='.$from;
		$response = file_get_contents($fullUrl);
		$respJson = json_decode($response);
		Log::info('sent: '.$response);
		return '*ok*';
	}
}