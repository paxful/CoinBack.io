<?php namespace Helpers;

class BitcoinHelper {

	public static function satoshiToBtc($satoshi)
	{
		return (float)bcdiv($satoshi, SATOSHI_FRACTION, 8);
	}
}
