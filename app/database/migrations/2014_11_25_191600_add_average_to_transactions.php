<?php

use Illuminate\Database\Migrations\Migration;

class AddAverageToTransactions extends Migration {

	public function up()
	{
		Schema::table('transactions', function($table)
		{
			$table->decimal('new_average', 8, 2)->nullable();
			$table->decimal('sale_profit', 8, 2)->nullable();
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('users', function($table)
		{
			$table->decimal('fiat_total', 15, 2)->default(0);
		});
	}

	public function down()
	{
		Schema::table('transactions', function($table)
		{
			$table->dropColumn('new_average');
			$table->dropColumn('sale_profit');
			$table->dropForeign('transactions_user_id_foreign');
		});

		Schema::table('users', function($table)
		{
			$table->dropColumn('fiat_total');
		});
	}

}