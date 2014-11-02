<?php

Route::controller('control', 'ControlController');
Route::controller('home', 'HomeController');
Route::controller('process', 'ProcessController');
Route::get('/', array('as' => 'home', function()
{
	if (Auth::check()) {
		return Redirect::to('control');
	}
	$countries = Cache::rememberForever('countries', function() {
		return Country::orderBy('sort_id', 'desc')->lists('name', 'id');
	});

	$data = array(
		'country' => $countries
	);
	return View::make('index', $data);
}));

Route::get('logout', array('as' => 'logout', function () {
	Auth::logout();
	return Redirect::to('/')->with('flash_success', 'You are successfully logged out.');

}))->before('auth'); // check auth in filter.php

Route::get('huihuihui', array('as' => 'huihuihui', function()
{
//	return BCInfoHelper::sendPayment('39594f3f-8a70-4268-9b6b-f0cb3c963116', '9e4c4e6778ba', '1Frhf6J9BTThkbAqSJQfUzSSBJmABUvpsJ', 247412, '14UGYVfEnVRUJRCHUUe5rVs3tK3WvSLV1p');
}));

