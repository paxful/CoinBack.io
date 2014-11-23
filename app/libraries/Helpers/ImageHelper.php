<?php namespace Helpers;

use Illuminate\Support\Facades\App;
use QRcode;

class ImageHelper {

	/**
	* creates default qr code for user
	*/
	public static function createDefaultQrCode($address)
	{
		if (App::environment('testing')) {
			// for testing random path
			return 'images/bill-cards/2014/11/1FEMvQUJkSH9ZiW8s5e3LbaaLqexi6T4iU.png';
		}
		require(app_path().'/libraries/phpqrcode/phpqrcode.php');
		$customerQrcodeImageRelativePath = self::getCurrentImageFolderPath() . '/' . $address. '.png';
		QRcode::png($address, public_path($customerQrcodeImageRelativePath), "H", 20, 1); // saves qr code image to disk
		return $customerQrcodeImageRelativePath;
	}

	public static function createBillCard($address, $qrCodePath, $type)
	{
		$frame               = imagecreatefromstring(file_get_contents($qrCodePath));
		$billCardsPath       = public_path('images/bill-cards/tmp');
		$portraitDimensions  = array('dest-x' => 115, 'dest-y' => 70, 'source-size' => 780, 'text-x' => 60, 'text-y' => 900, 'text-size' => 30);
		$landscapeDimensions = array('dest-x' => 30, 'dest-y' => 10, 'source-size' => 500, 'text-x' => 20, 'text-y' => 540, 'text-size' => 18);
		switch ($type):
			case 'portrait-bw':
				$bwPortraitEmptyPoster = public_path('images/bill-cards/CB-Poster-8x11-bw.png');
				return self::createSingleBillCard($address, $billCardsPath, $bwPortraitEmptyPoster, $frame, $portraitDimensions, '8x11-bw');
			case 'portrait-color':
				$colorPortraitEmptyPoster = public_path('images/bill-cards/CB-Poster-8x11-color.png');
				return self::createSingleBillCard($address, $billCardsPath, $colorPortraitEmptyPoster, $frame, $portraitDimensions, '8x11-color');
			case 'landscape-bw':
				$bwLandscapeEmptyPoster = public_path('images/bill-cards/CB-Poster-11x8-bw.png');
				return self::createSingleBillCard($address, $billCardsPath, $bwLandscapeEmptyPoster, $frame, $landscapeDimensions, '11x8-bw', true);
			case 'landscape-color':
				$colorLandscapeEmptyPoster = public_path('images/bill-cards/CB-Poster-11x8-color.png');
				return self::createSingleBillCard($address, $billCardsPath, $colorLandscapeEmptyPoster, $frame, $landscapeDimensions, '11x8-color', true);
			default:
				return null;
		endswitch;
	}

	private static function createSingleBillCard($address, $billCardFolder, $emptyPoster, $frame, $dimensions, $cardType, $landscape = false)
	{
		$newBillCardPath = $billCardFolder.'/'.$address.'-'.$cardType.'.png';
		if (file_exists($newBillCardPath)) {
			return $newBillCardPath; // don't create image again, because it exists
		}
		$blank = imagecreatefromstring(file_get_contents($emptyPoster)); //source
		if ($landscape)
		{
			$resizedFrame = imagecreatetruecolor(510, 510);
			imagecopyresized($resizedFrame, $frame, 0, 0, 0, 0, 510, 510, 780, 780);
			$frame = $resizedFrame;
		}
		imagecopymerge($blank, $frame, $dimensions['dest-x'], $dimensions['dest-y'], 0, 0, $dimensions['source-size'], $dimensions['source-size'], 100);

		$black = imagecolorallocate($blank, 0, 0, 0);

		imagettftext($blank, $dimensions['text-size'], 0, $dimensions['text-x'], $dimensions['text-y'], $black, public_path('fonts/arial.ttf'), $address);
		imagepng($blank, $newBillCardPath);
		imagedestroy($blank);
		if ($landscape)
		{
			imagedestroy($frame);
		}
		return $newBillCardPath;
	}

	private static function getCurrentImageFolderPath()
	{
		$yearFolder = 'images/bill-cards/'.date('Y');
		$monthFolder = $yearFolder . '/' . date('m');

		!file_exists($yearFolder) && mkdir($yearFolder , 0775);
		!file_exists($monthFolder) && mkdir($monthFolder, 0775);

		return $monthFolder;
	}
}

