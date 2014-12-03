<?php namespace Helpers;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailHelper {

	public static function sendEmailPlain($data)
	{
		try {
			Mail::queue([], $data, function($message) use ($data)
			{
				$message->to($data['email']);
				$message->subject($data['subject']);
				$message->setBody($data['text']);
			});
			return true;
		} catch (Exception $e)
		{
			ApiHelper::sendSMStoAdmins('Email template wasn\'t sent out to email: '.$data['email'].', check error log');
			Log::error('Error message: '.$e->getMessage().', email wasn\'t sent out to '.$data['email'].', subject: '.$data['subject'].', text: '.$data['text']);
			return false;
		}
	}

	public static function sendAdminWarningEmail($subject, $text) {
		return self::sendEmailPlain(array('text' => App::environment().': '.$text, 'email' => Config::get('mail.admin_email'), 'subject' => App::environment().': '.$subject));
	}

	public static function sendAdminSocialEmail($subject, $text) {
		return self::sendEmailPlain(array('text' => App::environment().': '.$text, 'email' => Config::get('mail.admin_email'), 'subject' => App::environment().': '.$subject));
	}

}