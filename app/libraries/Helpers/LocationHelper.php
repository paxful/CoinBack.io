<?php namespace Helpers;

use Country;
use Illuminate\Support\Facades\Cache;

class LocationHelper {

	public static function getCountriesList()
	{
		return Cache::rememberForever('countries', function() {
			return Country::orderBy('sort_id', 'desc')->lists('name', 'id');
		});
	}

}