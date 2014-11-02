<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	public function up()
	{
		Schema::table('transactions', function($table) {
			$table->decimal('bitcoin_current_rate_usd', 7, 2);
			$table->bigInteger('remaining_bitcoin')->default(0)->nullable();
			$table->boolean('sold')->unsigned()->default(0);
		});
	}

	public function down()
	{
		Schema::table('transactions', function($table)
		{
			$table->dropColumn('bitcoin_current_rate_usd');
			$table->dropColumn('remaining_bitcoin');
			$table->dropColumn('sold');
		});
	}

}