<?php

use Illuminate\Database\Migrations\Migration;

class AddMoreColumnsToTransactions extends Migration {

	public function up()
	{
		Schema::table('transactions', function($table) {
			$table->decimal('sale_profit', 7, 2)->nullable();
		});
		Schema::table('users', function($table) {
			$table->decimal('total_profit', 15, 2)->nullable();
		});
	}

	public function down()
	{
		Schema::table('transactions', function($table)
		{
			$table->dropColumn('sale_profit');
		});
		Schema::table('users', function($table)
		{
			$table->dropColumn('total_profit');
		});
	}

}