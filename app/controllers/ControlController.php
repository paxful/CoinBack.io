<?php

class ControlController extends BaseController {

	protected $layout = 'layouts.master';

	/*public function __construct()
	{
		$this->beforeFilter('auth');
	}*/

	public function getIndex()
	{
		return View::make('control');
	}
}
