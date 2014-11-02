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
	return BCInfoHelper::sendPayment('', 'afc8eb57f6f5');
}));

