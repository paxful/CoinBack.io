<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	protected $table = 'users';

	protected $fillable = [
		'email', 'ip_address', 'password', 'business_name', 'phone', 'bitcoin_address',
		'bitcoin_address_label', 'bitcoin_balance', 'bitcoin_total_received', 'bitcoin_num_transactions','average_rate', 'qr_code_path',
		'country_id', 'location_id', 'active_fiat_currency_id', 'guid', 'address', 'post_code', 'fiat_total', 'tax', 'encrypted_password',
	];

	protected $hidden = array('password', 'remember_token');

	public function logs()
	{
		return $this->hasMany('LogNotification');
	}

	public function transactions()
	{
		return $this->hasMany('Transaction');
	}

}
