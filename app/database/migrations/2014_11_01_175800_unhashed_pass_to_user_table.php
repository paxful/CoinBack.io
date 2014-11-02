<?php

use Illuminate\Database\Migrations\Migration;

class UnhashedPassToUserTable extends Migration {

	public function up()
	{
		Schema::table('users', function($table) {
			$table->string('unhashed_password', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('unhashed_password');
		});
	}

}