<?php

class Location extends Eloquent {

	protected $table = 'locations';
	protected $connection = 'pgsql2';

	public static function getUserLocationByIp($ip)
	{
		if (!starts_with($ip, '::ffff:')) {
			$ip = '::ffff:'.$ip;
		}

		// local env doesnt have ip4r
		if (App::environment('local')) {
			return Location::with('country')
			               ->whereRaw('locations.id = ( select geoname_id from blocks where net_ip >>= ? )',
				               array($ip))->first();
		}
		else {
			return Location::with('country')
			               ->whereRaw('locations.id = ( select geoname_id from blocks where (network_start_ip::ipaddress / network_prefix_length) >>= ?)',
				               array($ip))->first();
		}
	}

	public static function getCountryStates($country_id)
	{
		return Location::where('country_id', $country_id)
		               ->distinct()
		               ->get(array('subdivision_iso_code', 'subdivision_name'))
		               ->sortBy('subdivision_iso_code')
		               ->lists('subdivision_iso_code', 'subdivision_name');
	}

	public function country()
	{
		return $this->belongsTo('Country');
	}
}