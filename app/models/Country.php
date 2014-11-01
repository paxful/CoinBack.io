<?php

class Country extends Eloquent {

    protected $table = 'countries';
	protected $connection = 'pgsql2';

	public function locations()
	{
		return $this->hasMany('Location');
	}

}
