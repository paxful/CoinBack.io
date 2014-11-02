<?php

use Illuminate\Database\Migrations\Migration;

class AddTotalProfitToUsers extends Migration {

	public function up()
	{
		Schema::table('users', function($table) {
			$table->decimal('total_profit', 15, 2)->default(0);
		});
	}

	public function down()
	{
		Schema::table('users', function($table)
		{
			$table->dropColumn('total_profit');
		});
	}

}