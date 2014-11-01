<?php

class Location extends Eloquent {

	protected $table = 'locations';

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

	public function country()
	{
		return $this->belongsTo('Country');
	}
}