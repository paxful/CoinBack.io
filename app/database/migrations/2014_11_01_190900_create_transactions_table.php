<?php

use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration {

	public function up()
	{
		Schema::create('transactions', function($table) {
			$table->bigIncrements('id');
			$table->bigInteger('user_id')->nullable();
			$table->string('type')->nullable();
			$table->string('note')->nullable();
			$table->bigInteger('bitcoin_amount')->default(0);
			$table->decimal('fiat_amount', 7, 2)->nullable();
			$table->integer('fiat_currency_id')->nullable();
			$table->string('sent_to_address', 100)->nullable()->index();
			$table->string('transaction_hash', 255)->nullable()->index();
			$table->integer('confirms')->nullable();
			$table->timestamps();
		});
	}

	public function down()
	{
		Schema::drop('transactions');
	}

}