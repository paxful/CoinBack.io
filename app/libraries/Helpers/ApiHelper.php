<?php namespace Helpers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use RestAPI;

class ApiHelper {

	public static function getBitcoinPrice() {
		return self::getBitstampTicker()->bid;
	}

	public static function getBitstampTicker()
	{
		return Cache::remember('bitstamp_ticker', 1, function()
		{
			return self::runUrl('https://www.bitstamp.net/api/ticker/');
		});
	}

	public static function sendSMS($to, $from, $text) {

		require_once app_path().'/libraries/plivo.php';

		$auth_id = Config::get('services.plivo.auth_id');
		$auth_token = Config::get('services.plivo.auth_token');
		$p = new RestAPI($auth_id, $auth_token);

		if (is_array($to))
		{ // send to multiple numbers at once
			foreach ($to as $to_single) {
				$params = array(
					'src' => "$from",
					'dst' => "$to_single",
					'text' => "$text",
					'type' => 'sms',
				);
				$response = $p->send_message($params);

				if ($response['status'] != 202) {
					// TODO log to file
					MailHelper::sendAdminWarningEmail('ERROR! SMS wasn\'t sent out', "Message: $text\nTo: $to\nPlivo response: ".json_encode($response, JSON_PRETTY_PRINT));
				}
			}
			return true;
		}
		else
		{ // send to single number
			$params = array('src' => "$from", 'dst' => "$to", 'text' => "$text", 'type' => 'sms');
			$response = $p->send_message($params);

			if ($response['status'] == 202) {
				return true;
			} else {
				Log::warning("WARNING! SMS wasn't sent out. Message: $text, to: $to, Plivo response: ".print_r($response));
				MailHelper::sendAdminWarningEmail('WARNING! SMS wasn\'t sent out', 'Message: '.$text.'\nTo: '.$to.'\nPlivo response: '.json_encode($response, JSON_PRETTY_PRINT));
				return false;
			}
		}
	}

	public static function sendSMStoAdmins($text)
	{
		if (!App::environment('local'))
		{
			$admin_phones = Settings::getSettingValue('admin_phones'); // single text, comma separated
			$admin_phones_arr = explode(",", $admin_phones);
			$our_number = Config::get('services.plivo.number');
			self::sendSMS($admin_phones_arr, $our_number, App::environment().': '.$text);
		}
	}

	public static function getClassificator($key_name, $db_table, $column_name)
	{
		return Cache::rememberForever($key_name, function() use ($db_table, $column_name)
		{
			return $db_table::orderBy('sort_id', 'desc')->lists($column_name, 'id');
		});
	}

	private static function runUrl($full_url)
	{
		$curl_login = curl_init($full_url);
		curl_setopt($curl_login, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		curl_setopt($curl_login, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl_login, CURLOPT_HEADER, 0);
		curl_setopt($curl_login, CURLOPT_VERBOSE, 1);
		curl_setopt($curl_login, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($curl_login, CURLOPT_SSL_VERIFYHOST, 0);
		$json_data = curl_exec($curl_login);
		curl_close($curl_login);

		return json_decode($json_data);
	}
}