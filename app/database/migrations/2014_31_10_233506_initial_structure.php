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
        Schema::create('users', function($table) {
            $table->bigIncrements('id');
	        $table->string('email', 255);
	        $table->string('business_name', 255);
	        $table->string('password', 64);
	        $table->rememberToken();
	        $table->string('ip_address', 45);
	        $table->string('phone', 45);
	        $table->string('timezone', 255)->default('America/New_York');
	        $table->string('bitcoin_address', 48);
	        $table->text('bitcoin_address_label')->nullable();
	        $table->bigInteger('bitcoin_balance')->default(0);
	        $table->bigInteger('bitcoin_total_received')->default(0);
	        $table->integer('bitcoin_num_transactions')->default(0);
	        $table->decimal('average_rate')->nullable();
	        $table->string('qr_code_path', 255)->nullable();
	        $table->integer('active_fiat_currency_id')->default(144);
	        $table->integer('country_id')->default(227);
	        $table->integer('location_id')->nullable();
	        $table->string('address', 200)->nullable();
	        $table->string('post_code', 25)->nullable();
	        $table->dateTime('date_last_login')->default("2001-09-28 01:00:00");
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