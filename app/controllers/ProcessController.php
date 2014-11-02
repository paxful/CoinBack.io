<?php

class ProcessController extends BaseController {

	public function postReceive()
	{
		$result = Input::all();
		$payload = $result['payload'];

		// means outgoing payment, because 'sent'
		if ($payload['sent'] > 0) {
			return '*ok*'; // do nothing further, send back to Chain HTTP 200
		}

		$confirms = $payload['confirmations'];

		$transactionModel = Transaction::getTransactionByHash($payload['transaction_hash']);

		if (count($transactionModel)):
			Log::info('Updated transaction: '.$transactionModel->transaction_hash.', confirms: '.$transactionModel->confirms);
			Transaction::updateConfirmations($transactionModel, $confirms);
			return '*ok*';
		endif;

		$receivedSatoshis = $payload['received'];
		$bitcoinCurrentPrice = ApiHelper::getBitcoinPrice();
		$fiat_amount = $this->fiatAmount( $bitcoinCurrentPrice, $receivedSatoshis, 1 );

		$chainNotification = LogNotification::where('notification_id', $result['notification_id'])->first();

		Transaction::create(array(
			'user_id' => $chainNotification->user_id,
			'type' => 'received',
			'bitcoin_amount' => $receivedSatoshis,
			'fiat_amount' => $fiat_amount,
			'fiat_currency_id' => 144,
			'transaction_hash' => $payload['transaction_hash'],
			'confirms' => $confirms
		));

		Log::info('Inserted new incoming transaction for merchant id: '.$chainNotification->user_id);

		return '*ok*';
	}

	private function fiatAmount($fiat_currency_rate_USD, $value_satoshi, $crypto_current_rate_usd) {
		$value_btc = bcdiv($value_satoshi, SATOSHI_FRACTION, 8); // satoshis to BTC conversion
		$value_fiat_USD = bcmul($value_btc, $crypto_current_rate_usd, 2);
		return bcmul($fiat_currency_rate_USD, $value_fiat_USD, 2);
	}
}
