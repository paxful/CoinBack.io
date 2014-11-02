<?php

class LogNotification extends Eloquent {

	protected $table = 'log_notifications';

	protected $fillable = array(
		'user_id', 'notification_id', 'callback_url', 'address',
	);

	public function user()
	{
		return $this->belongsTo('User');
	}

}
