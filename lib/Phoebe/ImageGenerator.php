<?php

	/**
	 * This file is part of Phoebe - Front-End Build Engine
	 *
	 * For the full copyright and license information, please view the LICENSE
	 * files distributed in the lib folders.
	 */

	 /**
	 * Produces and manipulates images for
	 * fast frontend prototyping and development.
	 */	
	class Phoebe_ImageGenerator {

		// generate dummy image
		public static function generateDummyImage($width, $height, $filename) {
			
			$img = imagecreatetruecolor($width, $height);
			$backgroundColor = imagecolorallocate($img,204,204,204);
			$foregroundColor = imagecolorallocate($img,255,255,255);
			
			// switch on fast antialiasing
			imageantialias($img, true);

			// fill image with background color
			imagefill($img, 0, 0, $backgroundColor); 

			// add diagonal lines
			imageline($img, 0, $height, $width, 0, $foregroundColor);
			imageline($img, 0, 0, $width, $height, $foregroundColor);

			// add dimensions label
			$label = $width . ' * ' . $height ;
			imagestring($img, 2, $width/2 - strlen($label)/2*6, $height-20,  $label, $foregroundColor);

			// render image, store, clean memory
			imagepng($img, $filename);
			imagedestroy($img);
		}
	}
	
?>