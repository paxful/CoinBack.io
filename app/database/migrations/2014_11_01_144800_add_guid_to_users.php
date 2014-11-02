<?php

use Illuminate\Database\Migrations\Migration;

class AddGuidToUsers extends Migration {

	public function up()
	{
		Schema::table('users', function($table) {
			$table->string('guid', 255)->nullable();
		});
	}

	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('guid');
		});
	}

}