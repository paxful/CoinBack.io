<?php

Route::controller('control', 'ControlController');
Route::get('/', function()
{
	return View::make('index');
});