<?php


/**
 * @abstract Image resizer.
 * @version $Id: Resize.php 9889 2009-04-02 16:26:46Z dom $
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2009, w3concepts AG
 */

class Wdrei_Image_Resize {

	private $basePath;
	private $thumbsPath;
	private $dimension;
	private $imagePath;

	private function __construct() {

		$this->basePath = Zend_Registry :: getInstance()->config->dir->image->basePath;
		$this->thumbDir = Zend_Registry :: getInstance()->config->dir->image->thumbs;
		
		$this->dimension['width'] = 99999;
		$this->dimension['height'] = 99999;
		$this->dimension['box'] = false;
	}
	
	public static function getInstance() {
		
		static $instance;
		
		if (!isset($instance)) {
			$instance = new self();
		}
		
		return $instance;
	}
	
	public function setDimensions($width, $height, $boxed = false) {
		
		if ($width != null) {
			$this->dimension['width'] = (int) $width;
		}
		
		if ($height != null) {
			$this->dimension['height'] = (int) $height;
		}
		
		$this->dimension['box'] = (boolean) $boxed;
		
		return $this;
	}
	
	public function setImagePath($path) {
		
		$this->imagePath = $path;
		
		return $this;
	}

	private function sendHeaders() {

		header('Content-Disposition: inline');
		header('Content-type: image/jpeg');
	}

	public function outputImage() {

		if (empty ($this->dimension['width']) && empty ($this->dimension['width'])) {
			if (!file_exists($this->basePath . $this->imagePath)) {
				header("HTTP/1.0 404 Not Found");
			} else {
				$this->sendHeaders();
				readfile($this->basePath . $this->imagePath);
			}
			return;
		}

		$imagename = $this->resize();

		if ($imagename == '404') {
			header("HTTP/1.0 404 Not Found");
			return;
		}

		$this->sendHeaders();
		readfile($imagename);
	}

	private function resize() {

		$width = $this->dimension['width'];
		$height = $this->dimension['height'];
		$box = ($this->dimension['box']) ? ('.b') : ('');

		if ($width == 0 || $height == 0) {
			return;
		}

		$imageSource = $this->basePath . $this->imagePath;
		$targetName = str_replace('/', '_', $this->imagePath) . '.' . $width . 'x' . $height . $box . '.jpg';

		if (!file_exists($imageSource)) {
			/*
			 * Wenn die Originaldatei nicht vorhanden ist, ist abzubrechen.
			 */
			return '404';
		}

		if (file_exists($this->thumbDir . $targetName)) {
			/*
			 * Skaliertes Bild liegt bereits vor und wird zurückgegeben.
			 */
			return $this->thumbDir . $targetName;
		}

		$imageSourceDimensions = getimagesize($imageSource);

		if ($imageSourceDimensions[0] <= $width && $imageSourceDimensions[1] <= $height) {
			/*
			 * Das Bild muss (kann) nicht skaliert werden.
			 */
			return $this->basePath . $this->imagePath;
		}

		/*
		 * Zielgrösse des Bildes ermitteln.
		 */
		if ($this->dimension['box']) {
			$targetWidth = $width;
			$targetHeight = $height;
		} else {
			$slopeSource = $imageSourceDimensions[1] / $imageSourceDimensions[0];
			$slopeTarget = $height / $width;
			if ($slopeSource < $slopeTarget) {
				/*
				 * Auf Breite skalieren
				 */
				$targetWidth = (int) $width;
				$targetHeight = ($targetWidth / $imageSourceDimensions[0]) * $imageSourceDimensions[1];
			} else {
				/*
				 * Auf Höhe skalieren
				 */
				$targetHeight = (int) $height;
				$targetWidth = ($targetHeight / $imageSourceDimensions[1]) * $imageSourceDimensions[0];
			}
		}

		$targetWidth = (int) round($targetWidth);
		$targetHeight = (int) round($targetHeight);

		$sourcePath = $dst_im = imagecreatetruecolor($targetWidth, $targetHeight);
		$weiss = ImageColorAllocate($dst_im, 255, 255, 255);
		imagefill($dst_im, 0, 0, $weiss);

		if ($imageSourceDimensions[2] == 1) {
			$src_im = imagecreatefromGIF($imageSource);
		}
		elseif ($imageSourceDimensions[2] == 2) {
			$src_im = ImageCreateFromJPEG($imageSource);
		}
		elseif ($imageSourceDimensions[2] == 3) {
			$src_im = ImageCreateFromPNG($imageSource);
		} else {
			$src_im = imagecreatefromgd($imageSource);
		}

		if ($this->dimension['box']) {
			/*
			 * Vom Quellbild wird nur ein Ausschnitt verwendet werden.
			 */
			if ($imageSourceDimensions[0] / $imageSourceDimensions[1] < $targetWidth / $targetHeight) {
				$width = $imageSourceDimensions[0];
				$height = $imageSourceDimensions[0] * $targetHeight / $targetWidth;
				$x = 0;
				$y = ($imageSourceDimensions[1] - $height) / 2;
			} else {
				$width = $imageSourceDimensions[1] * $targetWidth / $targetHeight;
				$height = $imageSourceDimensions[1];
				$x = ($imageSourceDimensions[0] - $width) / 2;
				$y = 0;
			}
			$width = round($width);
			$height = round($height);
			$x = round($x);
			$y = round($y);
		} else {
			/*
			 * Bild wird vollständig verwendet.
			 */
			$width = $imageSourceDimensions[0];
			$height = $imageSourceDimensions[1];
			$x = 0;
			$y = 0;
		}

		imagecopyresampled($dst_im, $src_im, 0, 0, $x, $y, $targetWidth, $targetHeight, $width, $height);

		imagejpeg($dst_im, $this->thumbDir . $targetName, 100);

		return $this->thumbDir . $targetName;

	}
}