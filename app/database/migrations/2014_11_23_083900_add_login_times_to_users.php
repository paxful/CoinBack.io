<?php

use Illuminate\Database\Migrations\Migration;

class AddLoginTimesToUsers extends Migration {

	public function up()
	{
		Schema::table('users', function($table)
		{
			$table->integer('login_times')->default(0);
		});
	}

	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('login_times');
		});
	}

}