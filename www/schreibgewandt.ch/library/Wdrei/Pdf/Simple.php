<?php


/**
 * Simple PDF creation based on an existing document.
 * @version $Id: Simple.php 9900 2009-04-03 16:24:19Z akm $
 * @author Andreas Kummer, w3concepts AG
 * @copyright Copyright &copy; 2009, w3concepts AG
 */

require_once ('Zend/Pdf.php');

class Wdrei_Pdf_Simple {

	private $pdfName;
	private $templatePath;
	private $data = array ();
	private $text = array ();
	private $pageWidth;
	private $pageHeight;
	private $marginRight = 0;
	private $encoding = 'UTF-8';
	private $font = array('name' => Zend_Pdf_Font::FONT_HELVETICA, 'size' => 10);
	private $lineHeight = 15;
	private $pdf = null;

	private function __construct($name) {

		$this->pdfName = $name;
	}

	public static function getInstance($name) {

		static $instances = array ();

		if (!array_key_exists($name, $instances)) {
			$instances[$name] = new self($name);
		}

		return $instances[$name];
	}

	public function addData($data) {

		foreach ($data as $key => $value) {
			$this->data[$key] = $value;
		}

		return $this;
	}

	public function addText($dataKey, $x, $y, $page = 1) {

		$this->text[$page][$dataKey][] = array (
			'x' => $x,
			'y' => $y
		);

		return $this;
	}

	public function setTemplatePath($path) {

		$this->templatePath = $path;

		return $this;
	}

	private function createPdf() {

		if ($this->pdf != null) {
			return $this->pdf;
		}
		
		$this->renderData();

		try {
			$pdf = Zend_Pdf :: load($this->templatePath);
		} catch (Zend_Pdf_Exception $e) {
			return 'PDF template not existing...';
		}

		$this->pageWidth = $pdf->pages[0]->getWidth();
		$this->pageHeight = $pdf->pages[0]->getHeight();
		$this->standardFont = Zend_Pdf_Font :: fontWithName($this->font['name']);

		for ($i = 0; $i < count($pdf->pages); $i++) {
			$pdf->pages[0]->setFont($this->standardFont, $this->font['size']);
			foreach ($this->text[$i +1] as $key => $value) {
				foreach ($value as $pos) {
					$this->drawText($this->data[$key], $pdf->pages[$i], $pos['x'], $pos['y']);
				}
			}
		}

		return $pdf;
	}
	
	public function getPdf() {
		
		return $this->createPdf()->render();
	}
	
	public function savePdf($path) {
		
		$this->createPdf()->save($path);
		
		return $this;
	}

	private function drawText($text, $page, $x, $y) {

		$y = $this->getY($y);
		$stringWidth = $this->widthForStringUsingFontSize(preg_replace('/\\|/', "", $text), $this->standardFont, $this->font['size']);

		if ($x + $stringWidth < $this->pageWidth - $this->marginRight) {
			$page->drawText(preg_replace('/\\|/', "", $text), $x, $y, $this->encoding);
			return;
		}

		$parts = preg_split('/([\\s\\-\\|])/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
		$currentLine = 0;
		$text = '';
		for ($i = 0; $i < count($parts); $i = $i + 2) {
			$testString = preg_replace('/\\|/', "", $text);
			$stringWidth = $this->widthForStringUsingFontSize($testString . $parts[$i], $this->standardFont, $this->font['size']);
			if ($x + $stringWidth > $this->pageWidth - $this->marginRight) {
				if (isset($parts[$i + 1]) && $parts[$i + 1] == '|') {
					$testString .= '-';
				}
				$page->drawText($testString, $x, $y - $currentLine * $this->lineHeight, $this->encoding);
				$text = '';
				$currentLine++;
			}
			$text .= $parts[$i];
			if (isset($parts[$i + 1])) {
				$text .= $parts[$i + 1];
			}
		}
		if (strlen($text) > 0) {
			$page->drawText($text, $x, $y - $currentLine * $this->lineHeight, $this->encoding);
		}

	}

	private function getY($height) {

		return $this->pageHeight - $height;
	}

	private function getData($match) {
		return $this->data[$match[1]];
	}

	private function renderData() {

		$done = false;
		while (!$done) {
			$done = true;
			foreach ($this->data as $key => $value) {
				$this->data[$key] = preg_replace_callback('/\\{(.*?)\\}/', array (
					$this,
					"getData"
				), $value);
				if (preg_match('/\\{.*?\\}/', $value)) {
					$done = false;
				}
			}
		}
	}

	public function setMarginRight($margin) {

		$this->marginRight = $margin;

		return $this;
	}

	public function setEncoding($enc) {

		$this->encoding = $enc;

		return $this;
	}

	public function setFont($name, $size) {

		$this->font['name'] = $name;
		$this->font['size'] = $size;

		return $this;
	}

	private function widthForStringUsingFontSize($string, $font, $fontSize) {
		
		$drawingString = iconv('UTF-8', 'UTF-16BE', $string);
		$characters = array ();
		
		for ($i = 0; $i < strlen($drawingString); $i++) {
			$characters[] = (ord($drawingString[$i++]) << 8) | ord($drawingString[$i]);
		}
		
		$glyphs = $font->glyphNumbersForCharacters($characters);
		$widths = $font->widthsForGlyphs($glyphs);
		$stringWidth = (array_sum($widths) / $font->getUnitsPerEm()) * $fontSize;
		
		return $stringWidth;
	}

	public function setLineHeight($lineHeight) {
		
		$this->lineHeight = $lineHeight;
		
		return $this;
	}
}