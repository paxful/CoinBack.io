<?php

class Transaction extends Eloquent {

    protected $table = 'transactions';

    protected $fillable = array('user_id', 'type', 'note', 'bitcoin_amount', 'fiat_amount', 'fiat_currency_id',
        'sent_to_address', 'transaction_hash', 'confirms', 'bitcoin_current_rate_usd', 'remaining_bitcoin', 'sold', 'sale_profit',
        'bitcoin_balance', 'fiat_balance', 'new_average');

	public static function updateConfirmations($transaction_model, $confirmations) {
		$transaction_model->confirms = $confirmations;
		$transaction_model->save();
	}

	public static function getTransactionByHash($tx_hash) {
		return self::where('transaction_hash' , '=', $tx_hash)->first();
	}

	public static function lastIncomingUnspent($userId) {
		return self::prepareAvailableCoinsQuery($userId)->orderBy('id', 'desc')->first();
	}

	public static function availableCoinsForSale($userId) {
		return self::prepareAvailableCoinsQuery($userId)->get();
	}

	private static function prepareAvailableCoinsQuery($userId) {
		return self::where('user_id', $userId)->where('type', RECEIVED)->where('sold', 0)->where('remaining_bitcoin', '>', 0);
	}

    public function user() {
        return $this->belongsTo('User');
    }
}