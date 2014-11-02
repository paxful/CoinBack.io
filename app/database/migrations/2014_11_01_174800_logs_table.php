<?php

use Illuminate\Database\Migrations\Migration;

class LogsTable extends Migration {

	public function up()
	{
		Schema::create('log_notifications', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->nullable();
			$table->string('notification_id')->nullable();
			$table->text('callback_url')->nullable();
			$table->string('address')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('log_notifications');
	}

}