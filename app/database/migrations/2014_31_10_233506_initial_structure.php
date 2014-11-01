<?php

use Illuminate\Database\Migrations\Migration;

class InitialStructure extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('addresses_promo', function($table) {
            $table->increments('id');
            $table->string('address', 48);
            $table->string('promo_code', 40)->nullable()->unique();
            $table->boolean('activated')->default(false);
            $table->string('phone', 25)->nullable();
            $table->text('label')->nullable();
            $table->bigInteger('user_account_id')->nullable();
            $table->bigInteger('crypto_bonus_amount')->default(0);
            $table->integer('crypto_currency_id')->default("1")->nullable()->index();
            $table->decimal('fiat_bonus_amount', 7, 2)->default(0);
            $table->integer('fiat_currency_id')->index();
            $table->timestamps();
        });

	    /* user
	        -id
	        -email
	        -business_name
	        -pass
	        -token
	        -ip_address
	        -bitcoin_address
	        -bitcoin_balance
	        -average_rate
	        -qr_code_path
	        -fiat_currency_id
	        -timezone
	        -location_id
	        -address
	        -postal code

	    fiat_currencies
	    fiat_currencies_temp

	    locations
	    blocks

	    transactions
	        -id
	        -user_id
	        -type: send/receive
	        -note
	        -crypto_amount
	        -fiat_amount
	        -fiat_currency_id
	        -send_to_address
	        -tx_hash
	        -confirms
	    logs
	    */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('addresses_promo');
    }

}