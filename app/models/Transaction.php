<?php

class Transaction extends Eloquent {

    protected $table = 'transactions';

    protected $fillable = array('user_id', 'type', 'note', 'bitcoin_amount', 'fiat_amount', 'fiat_currency_id',
        'sent_to_address', 'transaction_hash', 'confirms');

	public static function updateConfirmations($transaction_model, $confirmations) {
		$transaction_model->confirms = $confirmations;
		$transaction_model->save();
	}

	public static function getTransactionByHash($tx_hash) {
		return self::where('transaction_hash' , '=', $tx_hash)->first();
	}

	public static function allUnspent($userId) {
		return self::where('user_id' , $userId)->where('sold', 0)->get();
	}

    public function user() {
        return $this->belongsTo('User');
    }
}