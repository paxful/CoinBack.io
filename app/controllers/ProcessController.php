<?php

class ProcessController extends BaseController {

	public function postReceive()
	{
		$result = Input::all();
		$payload = $result['payload'];

		Log::info($result);

		// means outgoing payment, because 'sent'
		if ($payload['sent'] > 0) {
			return '*ok*'; // do nothing further, send back to Chain HTTP 200
		}

		$confirms = $payload['confirmations'];

		$transactionModel = Transaction::getTransactionByHash($payload['transaction_hash']);

		/* transaction exists, only updated confirmations number */
		if (count($transactionModel)):
			Log::info('Updated transaction: '.$transactionModel->transaction_hash.', confirms: '.$transactionModel->confirms);
			Transaction::updateConfirmations($transactionModel, $confirms);
			return '*ok*';
		endif;

		$receivedSatoshis = $payload['received'];
		$bitcoinCurrentPrice = ApiHelper::getBitcoinPrice();
		$fiat_amount_satoshi = bcmul($bitcoinCurrentPrice, $receivedSatoshis, 2);
		$fiat_amount = bcdiv($fiat_amount_satoshi, SATOSHI_FRACTION, 2);

		$chainNotification = LogNotification::where('notification_id', $result['notification_id'])->first();

		$newTransaction = new Transaction();
		$newTransaction->user_id                    = $chainNotification->user_id;
		$newTransaction->type                       = 'received';
		$newTransaction->bitcoin_amount             = $receivedSatoshis;
		$newTransaction->remaining_bitcoin          = $receivedSatoshis;
		$newTransaction->fiat_amount                = $fiat_amount;
		$newTransaction->fiat_currency_id           = 144;
		$newTransaction->transaction_hash           = $payload['transaction_hash'];
		$newTransaction->confirms                   = $confirms;
		$newTransaction->bitcoin_current_rate_usd   = $bitcoinCurrentPrice;

		/* get new average price */
		$newTransaction = $this->calculateNewAverageOnReceive($newTransaction, $chainNotification->user_id);
		$newTransaction->save();

		/* update user with new average price */
		$user = User::find($chainNotification->user_id);
		$user->average_rate = $newTransaction->new_average;
		$user->bitcoin_balance = bcadd($user->bitcoin_balance, $receivedSatoshis);
		$user->bitcoin_num_transactions = $user->bitcoin_num_transactions + 1;
		$user->fiat_total = bcadd($user->fiat_total, $fiat_amount, 2);

		$user->save();

		Log::info('Inserted new incoming transaction for merchant id: '.$chainNotification->user_id);

		ApiHelper::sendSms($user->phone, $_ENV['PLIVO_NUMBER'], "You received $fiat_amount USD. CoinBack.io");

		return '*ok*';
	}

	private function calculateNewAverageOnReceive($newTransaction, $userId) {
		$lastIncomingTransaction = Transaction::lastIncomingUnspent($userId); // where still is remaining bitcoin and not sold

		if (!count($lastIncomingTransaction)) {
			// if no other incoming transactions with available bitcoins found, just calculate and save balance + average
			$newTransaction->bitcoin_balance = $newTransaction->remaining_bitcoin;
			$newTransaction->fiat_balance = $newTransaction->fiat_amount;
			$newTransaction->new_average = $newTransaction->fiat_amount;
			return $newTransaction;
		}
		$newCryptoBalance = bcadd($lastIncomingTransaction->bitcoin_balance, $newTransaction->remaining_bitcoin); // in satoshis
		$newFiatBalance = bcadd($lastIncomingTransaction->fiat_balance, $newTransaction->fiat_amount, 2);
		$newTransaction->bitcoin_balance = $newCryptoBalance;
		$newTransaction->fiat_balance = $newFiatBalance;

		$newBtcBalance = BitcoinHelper::satoshiToBtc($newCryptoBalance);
		$newAverage = bcdiv($newFiatBalance, $newBtcBalance, 2);
		$newTransaction->new_average = $newAverage;
		return $newTransaction;
	}

	private function fiatAmount($fiat_currency_rate_USD, $value_satoshi, $crypto_current_rate_usd) {
		$value_btc = bcdiv($value_satoshi, SATOSHI_FRACTION, 8); // satoshis to BTC conversion
		$value_fiat_USD = bcmul($value_btc, $crypto_current_rate_usd, 2);
		return bcmul($fiat_currency_rate_USD, $value_fiat_USD, 2);
	}
}
