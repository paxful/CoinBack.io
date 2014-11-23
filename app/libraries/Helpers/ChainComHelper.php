<?php namespace Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;

class ChainComHelper {

	public static function createAddressNotification($address) {
		if (App::environment('testing')) {
			// random chain.com response for unit testing
			return json_encode(array(
				'id' => 'f69d69d5-d46b-41d5-8ac7-e50bfe0b44b1',
				'notification_id' => 'f69d69d5-d46b-41d5-8ac7-e50bfe0b44b1',
				'url' => 'http://www.bigbooties.com',
				'address' => '1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU',
			));
		}

		$CHAIN_COM_URL = $_ENV['CHAIN_COM_API_URL'];
		$data = array(
			'type' => 'address',
			'block_chain' => 'bitcoin', // testnet3 / bitcoin
			'address' => $address,
			'url' => $_ENV['CHAIN_COM_NOTIFICATION_URL'],
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $_ENV['CHAIN_COM_ID'] . ":" . $_ENV['CHAIN_COM_SECRET']);
		curl_setopt($ch, CURLOPT_URL, $CHAIN_COM_URL.'/notifications');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data)); // make sure to encode into json from array
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$response = curl_exec ($ch);
		curl_close ($ch);

		Log::info('chain.com response: '.json_encode($response));
		return $response;
	}

}