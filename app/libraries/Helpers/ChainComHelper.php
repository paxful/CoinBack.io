<?php namespace Helpers;

use Illuminate\Support\Facades\Log;

class ChainComHelper {

	public static function createAddressNotification($address) {
		$CHAIN_COM_URL = $_ENV['CHAIN_COM_API_URL'];
		$data = array(
			'type' => 'address',
			'block_chain' => 'bitcoin',
			'address' => $address,
			'url' => 'https://www.bigbooties.com',
		);

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_USERPWD, $_ENV['CHAIN_COM_ID'] . ":" . $_ENV['CHAIN_COM_SECRET']);
		curl_setopt($ch, CURLOPT_URL, $CHAIN_COM_URL.'/notifications');
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		$response = curl_exec ($ch);
		curl_close ($ch);

		Log::info('chain.com response: '.json_encode($response));
		return $response;
	}

}