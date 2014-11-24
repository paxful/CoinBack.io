<?php

use Illuminate\Database\Migrations\Migration;

class AddBalancesToTransactions extends Migration {

	public function up()
	{
		Schema::table('transactions', function($table)
		{
			$table->bigInteger('bitcoin_balance')->default(0);
			$table->decimal('fiat_balance', 7, 2)->nullable();
		});
	}

	public function down()
	{
		Schema::table('transactions', function($table)
		{
			$table->dropColumn('bitcoin_balance');
			$table->dropColumn('fiat_balance');
		});
	}

}