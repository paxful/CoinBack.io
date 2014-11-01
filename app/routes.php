<?php

Route::controller('control', 'ControlController');
Route::get('/', array('as' => 'home', function()
{
	$countries = Cache::rememberForever('countries', function() {
		return Country::orderBy('sort_id', 'desc')->lists('name', 'id');
	});

	$data = array(
		'country' => $countries
	);
	return View::make('index', $data);
}));