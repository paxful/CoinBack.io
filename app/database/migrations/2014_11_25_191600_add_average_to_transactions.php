<?php

use Illuminate\Database\Migrations\Migration;

class AddAverageToTransactions extends Migration {

	public function up()
	{
		Schema::table('transactions', function($table)
		{
			$table->decimal('new_average', 8, 2)->nullable();
			$table->decimal('sale_profit', 8, 2)->nullable();
			$table->decimal('fiat_amount_market_rate', 8, 2)->nullable();
			$table->decimal('bitcoin_premium_exchange_rate', 8, 2)->nullable();
			$table->integer('fee')->nullable();
			$table->decimal('tax', 8, 2)->nullable();
			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::table('users', function($table)
		{
			$table->decimal('fiat_total', 15, 2)->default(0);
			$table->decimal('tax', 8, 2)->default(8.1); // vegas sales tax
			$table->string('encrypted_password')->nullable();
			$table->dropColumn('unhashed_password');
		});
	}

	public function down()
	{
		Schema::table('transactions', function($table)
		{
			$table->dropColumn('new_average');
			$table->dropColumn('sale_profit');
			$table->dropColumn('fiat_amount_market_rate');
			$table->dropColumn('bitcoin_premium_exchange_rate');
			$table->dropColumn('fee');
			$table->dropColumn('tax');
			$table->dropForeign('transactions_user_id_foreign');
		});

		Schema::table('users', function($table)
		{
			$table->dropColumn('fiat_total');
			$table->dropColumn('tax');
			$table->dropColumn('encrypted_password');
			$table->string('unhashed_password')->nullable();
		});
	}

}