<?php namespace Helpers;

use Illuminate\Support\Facades\Log;

class BCInfoHelper {

	static $NEW_WALLET_URL = 'https://blockchain.info/api/v2/create_wallet';

	public static function createNewWallet($password, $email, $label = 'first') {
		$fullUrl = self::$NEW_WALLET_URL.'?password='.$password.'&email='.$email.'&label='.$label.'&api_code='.$_ENV['BCINFO_KEY'];
		$response = ApiHelper::runUrl($fullUrl);
		 // TODO dont log the pass to log file, log it to db
		Log::info('New blockchain.info wallet created, email: '.$email.', pass: '.$password.'. Response: '.$response);
		return $response;
	}
}