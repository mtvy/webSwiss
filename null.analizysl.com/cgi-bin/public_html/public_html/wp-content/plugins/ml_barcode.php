<?php
/**
 * @package ml_barcode
 */
/*
Plugin Name: MedLab BarCode
Plugin URI: 
Description: allow you to display and print barcodes
Version: 1.0
Author: me
Author URI: my
License: GPLv2 or later
Text Domain: wsd
*/

/* 
 * wsd_barcode
 */

define('IMG_FORMAT_PNG',	1);
define('IMG_FORMAT_JPEG',	2);
define('IMG_FORMAT_WBMP',	4);
define('IMG_FORMAT_GIF',	8);
class FDrawing {
	private $w, $h;		// int
	private $color;		// Fcolor
	private $filename;	// char *
	private $im;		// {object}
	private $barcode = array();// BarCode *

	/**
	 * Constructor
	 *
	 * @param int $w
	 * @param int $h
	 * @param string filename
	 * @param FColor $color
	 */
	public function __construct($w,$h,$filename,Fcolor $color) {
		$this->w = $w;
		$this->h = $h;
		$this->filename = $filename;
		$this->color = $color;
	}

	/**
	 * Destructor
	 */
	public function __destruct() {
		$this->destroy();
	}

	/**
	 * Init Image and color background
	 */
	public function init(){
		$this->im = imagecreate($this->w, $this->h)
		or die('Can\'t Initialize the GD Libraty');
		imagecolorallocate($this->im,$this->color->r(),$this->color->g(),$this->color->b());
	}

	/**
	 * @return ressource
	 */
	public function get_im() {
		return $this->im;
	}
	public function set_im($im) {
		$this->im = $im;
	}

	/**
	 * Add barcode into the drawing array (for future drawing)
	 *
	 * @param BarCode $barcode
	 */
	public function add_barcode(BarCode $barcode) {
		$this->barcode[] = $barcode;
	}

	/**
	 * Draw first all forms and after all texts on $im
	 */
	public function draw_all() {
		for($i=0;$i<count($this->barcode);$i++)
			$this->barcode[$i]->draw($this->im);
	}

	/**
	 * Save $im into the file (many format available)
	 *
	 * @param int $image_style
	 * @param int $quality
	 */
	public function finish($image_style=IMG_FORMAT_PNG,$quality=100) {
		if($image_style==constant('IMG_FORMAT_PNG')){
			if(empty($this->filename))
				imagepng($this->im);
			else
				imagepng($this->im,$this->filename);
		}
		elseif($image_style==constant('IMG_FORMAT_JPEG'))
			imagejpeg($this->im,$this->filename,$quality);
	}

	/**
	 * Free the memory of PHP (called also by destructor)
	 */
	public function destroy() {
		imagedestroy($this->im);
	}
}

class FColor {
	protected $r,$g,$b;	// int Hexadecimal Value

	/**
	 * Save RGB value into the classes
	 *
	 * @param int $r
	 * @param int $g
	 * @param int $b
	 */
	public function __construct($r,$g,$b){
		$this->r = $r;
		$this->g = $g;
		$this->b = $b;
	}

	/**
	 * Returns Red Color
	 *
	 * @return int
	 */
	public function r(){
		return $this->r;
	}

	/**
	 * Returns Green Color
	 *
	 * @return int
	 */
	public function g(){
		return $this->g;
	}

	/**
	 * Returns Blue Color
	 *
	 * @return int
	 */
	public function b(){
		return $this->b;
	}

	/**
	 * Returns the int value for PHP color
	 *
	 * @return int
	 */
	public function allocate($im) {
		return imagecolorallocate($im,$this->r,$this->g,$this->b);
	}
}

class BarCode {
	protected $maxHeight;
	protected $color1,$color2;
	protected $positionX, $positionY, $res;
	public $lastX, $lastY;
	private $error;

	/**
	 * Constructor
	 *
	 * @param int $maxHeight
	 * @param FColor $color1
	 * @param FColor $color2
	 * @param int $res
	 */
	protected function __construct($maxHeight,FColor $color1,FColor $color2,$res){
		$this->maxHeight = $maxHeight;
		$this->color1 = $color1;
		$this->color2 = $color2;
		$this->res = $res;
		$this->error = 0;
		$this->positionY = 0;
	}

	/**
	 * Returns the index in $keys (useful for checksum)
	 *
	 * @param mixed $var
	 * @return mixed
	 */
	protected function findIndex($var){
		return array_search($var,$this->keys);
	}

	/**
	 * Returns the code of the char (useful for drawing bars)
	 *
	 * @param mixed $var
	 * @return string
	 */
	protected function findCode($var){
		return $this->code[$this->findIndex($var)];
	}

	/**
	 * Draws a Bar of $color depending of the resolution
	 *
	 * @param ressource $img
	 * @param FColor $color
	 */
	protected function DrawSingleBar($im,FColor $color){
		$bar_color = (is_null($color))?NULL:$color->allocate($im);
		if(!is_null($bar_color))
			for($i=0;$i<$this->res;$i++)
				imageline($im,$this->positionX+$i,$this->positionY,$this->positionX+$i,$this->positionY+$this->maxHeight,$bar_color);
	}

	/**
	 * Writes the Error on the picture
	 *
	 * @param ressource $img
	 * @param string $text
	 */
	protected function DrawError($im,$text){
		$text_color = (is_null($this->color1))?NULL:$this->color1->allocate($im);
		imagestring($im,5,0,$this->error*15,$text,$text_color);
		$this->error++;
		$this->lastX = (imagefontwidth(5)*strlen($text)>$this->lastX)?imagefontwidth(5)*strlen($text):$this->lastX;
		$this->lastY = $this->error*15;
	}

	/**
	 * Moving the pointer right to write a bar
	 */
	protected function nextX(){
		$this->positionX+=$this->res;
	}

	/**
	 * Draws all chars thanks to $code. if $start==1, the line begins by a bar.
	 * if $start==2, the line begins by a space.
	 *
	 * @param ressource $im
	 * @param string $code
	 * @param int $start
	 */
	protected function DrawChar($im,$code,$start=1){
		$currentColor = ($start==1)?$this->color1:$this->color2;
		$colornumber = $start;
		for($i=0;$i<strlen($code);$i++){
			for($j=0;$j<intval($code[$i])+1;$j++){
				$this->DrawSingleBar($im,$currentColor);
				$this->nextX();
			}
			if($colornumber==1){
				$currentColor=$this->color2;
				$colornumber=2;
			}
			else{
				$currentColor=$this->color1;
				$colornumber=1;
			}
		}
	}

	/**
	 * Draws the label under the barcode
	 *
	 * @param ressource $im
	 */
	protected function DrawText($im) {
		if($this->textfont != 0) {
			$xPosition = ($this->positionX / 2) - (strlen($this->text)/2)*imagefontwidth($this->textfont);
			$text_color = (is_null($this->color1))?NULL:$this->color1->allocate($im);
			imagestring($im,$this->textfont,$xPosition,$this->maxHeight,$this->text,$text_color);
			$this->lastY = $this->maxHeight + imagefontheight($this->textfont);
		}
	}
}

class code128 extends BarCode {
	protected $keys = array(), $keysA = array(), $keysB = array(), $keysC = array(), $code = array();
	private $starting, $ending, $starting_text;
	protected $text;
	protected $textfont;
	private $currentCode;

	/**
	 * Constructor
	 *
	 * @param int $maxHeight
	 * @param FColor $color1
	 * @param FColor $color2
	 * @param int $res
	 * @param string $text
	 * @param int $textfont
	 * @param char $start
	 */
	public function __construct($maxHeight,FColor $color1,FColor $color2,$res,$text,$textfont,$start='B') {
		BarCode::__construct($maxHeight,$color1,$color2,$res);
		if($start=='A')
			$this->starting = 103;
		elseif($start=='B')
			$this->starting = 104;
		elseif($start=='C')
			$this->starting = 105;
		$this->ending = 106;
		$this->currentCode = $start;
		/* CODE 128 A */
		$this->keysA = array(' ','!','"','#','$','%','&','\'','(',')','*','+',',','-','.','/','0','1','2','3','4','5','6','7','8','9',':',';','<','=','>','?','@','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','[','\\',']','^','_','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',chr(128),chr(129));

		/* CODE 128 B */
		$this->keysB = array(' ','!','"','#','$','%','&','\'','(',')','*','+',',','-','.','/','0','1','2','3','4','5','6','7','8','9',':',';','<','=','>','?','@','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','[','\\',']','^','_','`','a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','{','|','}','~','','','','',chr(128),'',chr(130));

		/* CODE 128 C */
		$this->keysC = array();
		for($i=0;$i<=99;$i++)
			$this->keysC[] = sprintf('%02d',$i);
		$this->keysC[] = chr(129);
		$this->keysC[] = chr(130);

		$this->code = array(
			'101111',	/* 00 */
			'111011',	/* 01 */
			'111110',	/* 02 */
			'010112',	/* 03 */
			'010211',	/* 04 */
			'020111',	/* 05 */
			'011102',	/* 06 */
			'011201',	/* 07 */
			'021101',	/* 08 */
			'110102',	/* 09 */
			'110201',	/* 10 */
			'120101',	/* 11 */
			'001121',	/* 12 */
			'011021',	/* 13 */
			'011120',	/* 14 */
			'002111',	/* 15 */
			'012011',	/* 16 */
			'012110',	/* 17 */
			'112100',	/* 18 */
			'110021',	/* 19 */
			'110120',	/* 20 */
			'102101',	/* 21 */
			'112001',	/* 22 */
			'201020',	/* 23 */
			'200111',	/* 24 */
			'210011',	/* 25 */
			'210110',	/* 26 */
			'201101',	/* 27 */
			'211001',	/* 28 */
			'211100',	/* 29 */
			'101012',	/* 30 */
			'101210',	/* 31 */
			'121010',	/* 32 */
			'000212',	/* 33 */
			'020012',	/* 34 */
			'020210',	/* 35 */
			'001202',	/* 36 */
			'021002',	/* 37 */
			'021200',	/* 38 */
			'100202',	/* 39 */
			'120002',	/* 40 */
			'120200',	/* 41 */
			'001022',	/* 42 */
			'001220',	/* 43 */
			'021020',	/* 44 */
			'002012',	/* 45 */
			'002210',	/* 46 */
			'022010',	/* 47 */
			'202010',	/* 48 */
			'100220',	/* 49 */
			'120020',	/* 50 */
			'102002',	/* 51 */
			'102200',	/* 52 */
			'102020',	/* 53 */
			'200012',	/* 54 */
			'200210',	/* 55 */
			'220010',	/* 56 */
			'201002',	/* 57 */
			'201200',	/* 58 */
			'221000',	/* 59 */
			'203000',	/* 60 */
			'110300',	/* 61 */
			'320000',	/* 62 */
			'000113',	/* 63 */
			'000311',	/* 64 */
			'010013',	/* 65 */
			'010310',	/* 66 */
			'030011',	/* 67 */
			'030110',	/* 68 */
			'001103',	/* 69 */
			'001301',	/* 70 */
			'011003',	/* 71 */
			'011300',	/* 72 */
			'031001',	/* 73 */
			'031100',	/* 74 */
			'130100',	/* 75 */
			'110003',	/* 76 */
			'302000',	/* 77 */
			'130001',	/* 78 */
			'023000',	/* 79 */
			'000131',	/* 80 */
			'010031',	/* 81 */
			'010130',	/* 82 */
			'003101',	/* 83 */
			'013001',	/* 84 */
			'013100',	/* 85 */
			'300101',	/* 86 */
			'310001',	/* 87 */
			'310100',	/* 88 */
			'101030',	/* 89 */
			'103010',	/* 90 */
			'301010',	/* 91 */
			'000032',	/* 92 */
			'000230',	/* 93 */
			'020030',	/* 94 */
			'003002',	/* 95 */
			'003200',	/* 96 */
			'300002',	/* 97 */
			'300200',	/* 98 */
			'002030',	/* 99 */
			'003020',	/* 100*/
			'200030',	/* 101*/
			'300020',	/* 102*/
			'100301',	/* 103*/
			'100103',	/* 104*/
			'100121',	/* 105*/
			'122000'	/*STOP*/
		);
		$this->setText($text);
		$this->textfont = $textfont;
		$this->usingCode($start);
		$this->starting_text = $start;
	}

	/**
	 * Saves Text
	 *
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	private function usingCode($code) {
		if($code=='A')
			$this->keys = $this->keysA;
		elseif($code=='B')
			$this->keys = $this->keysB;
		elseif($code=='C')
			$this->keys = $this->keysC;
		$this->currentCode = $code;
	}

	/**
	 * Draws the barcode
	 *
	 * @param ressource $im
	 */
	public function draw($im) {
		$error_stop = false;

		$this->usingCode($this->starting_text);
		// Checking if all chars are allowed
		for($i=0;$i<strlen($this->text);$i++) {
			if($this->currentCode=='C') {
				if(isset($this->text[$i+1]) && $this->check_int($this->text[$i+1])) {
					$value_test = array_search($this->text[$i].$this->text[$i+1],$this->keys);
					$i++;
				}
				else {
					$this->DrawError($im,'With Code C, you must provide always pair of two integers.');
					$error_stop = true;
				}
			}
			else
				$value_test = array_search($this->text[$i],$this->keys);
			if(!is_int($value_test)) {
				$this->DrawError($im,'Char \''.$this->text[$i].'\' not allowed.');
				$error_stop = true;
			}
			if($this->findIndex($this->text[$i]) == 99 && $this->currentCode != 'C')
				$this->usingCode('C');
			elseif($this->findIndex($this->text[$i]) == 100 && $this->currentCode != 'B')
				$this->usingCode('B');
			elseif($this->findIndex($this->text[$i]) == 101 && $this->currentCode != 'A')
				$this->usingCode('A');
		}
		if($error_stop == false) {
			// The START-A, START-B, START-C, STOP are not allowed
			if(is_int(strpos($this->text,chr(135))) || is_int(strpos($this->text,chr(136))) || is_int(strpos($this->text,chr(137))) || is_int(strpos($this->text,chr(138)))) {
				$this->DrawError($im,'Chars START-A, START-B, START-C, STOP not allowed.');
				$error_stop = true;
			}
			if($error_stop == false) {
				$this->usingCode($this->starting_text);
				// Starting Code
				$this->DrawChar($im,$this->code[$this->starting],1);
				// Chars
				for($i=0;$i<strlen($this->text);$i++) {
					if($this->currentCode=='C') {
						$this->DrawChar($im,$this->findCode($this->text[$i].$this->text[$i+1]),1);
						$i++;
					}
					else
						$this->DrawChar($im,$this->findCode($this->text[$i]),1);
					if($this->findIndex($this->text[$i]) == 99 && $this->currentCode != 'C')
						$this->usingCode('C');
					elseif($this->findIndex($this->text[$i]) == 100 && $this->currentCode != 'B')
						$this->usingCode('B');
					elseif($this->findIndex($this->text[$i]) == 101 && $this->currentCode != 'A')
						$this->usingCode('A');
				}
				// Checksum
				// First Char (START)
				// + Starting with the first data character following the start character,
				// take the value of the character (between 0 and 102, inclusive) multiply
				// it by its character position (1) and add that to the running checksum.
				// Modulated 103
				if($this->starting == 103)
					$this->usingCode('A');
				elseif($this->starting == 104)
					$this->usingCode('B');
				elseif($this->starting == 105)
					$this->usingCode('C');
				$checksum = 0;
				$checksum += $this->starting;
				for($position=1,$i=0;$i<strlen($this->text);$position++,$i++) {
					if($this->currentCode=='C') {
						$checksum += intval($this->text[$i].$this->text[$i+1]) * $position;
						$i++;
					}
					else
						$checksum += $this->findIndex($this->text[$i]) * $position;

					if($this->findIndex($this->text[$i]) == 99 && $this->currentCode != 'C')
						$this->usingCode('C');
					elseif($this->findIndex($this->text[$i]) == 100 && $this->currentCode != 'B')
						$this->usingCode('B');
					elseif($this->findIndex($this->text[$i]) == 101 && $this->currentCode != 'A')
						$this->usingCode('A');
				}
				$this->DrawChar($im,$this->code[$checksum % 103],1);
				// Ending Code
				$this->DrawChar($im,$this->code[$this->ending],1);
				// Draw a Final Bar
				$this->DrawChar($im,'1',1);
				$this->lastX = $this->positionX;
				$this->lastY = $this->maxHeight;
				// Removing Special Code
//				$this->text = ereg_replace(chr(128),'',$this->text);
//				$this->text = ereg_replace(chr(129),'',$this->text);
//				$this->text = ereg_replace(chr(130),'',$this->text);
                
//				$this->text = preg_replace(chr(128),'',$this->text);
//				$this->text = preg_replace(chr(129),'',$this->text);
//				$this->text = preg_replace(chr(130),'',$this->text);
                $r=[];
                $r[chr(128)]='';
                $r[chr(129)]='';
                $r[chr(130)]='';
				$this->text = strtr($this->text, $r);
				$this->DrawText($im);
			}
		}
	}

	private function check_int($var) {
		if(intval($var)>=0 || intval($var)<=9)
			return true;
		else
			return false;
	}
}

class code11 extends BarCode {
	protected $keys = array(), $code = array();
	private $starting, $ending;
	protected $text;
	protected $textfont;

	/**
	 * Constructor
	 *
	 * @param int $maxHeight
	 * @param FColor $color1
	 * @param FColor $color2
	 * @param int $res
	 * @param string $text
	 * @param int $textfont
	 */
	public function __construct($maxHeight,FColor $color1,FColor $color2,$res,$text,$textfont) {
		BarCode::__construct($maxHeight,$color1,$color2,$res);
		$this->starting = $this->ending = 47;
		$this->keys = array('0','1','2','3','4','5','6','7','8','9','-');
		$this->code = array(	// 0 added to add an extra space
			'000010',	/* 0 */
			'100010',	/* 1 */
			'010010',	/* 2 */
			'110000',	/* 3 */
			'001010',	/* 4 */
			'101000',	/* 5 */
			'011000',	/* 6 */
			'000110',	/* 7 */
			'100100',	/* 8 */
			'100000',	/* 9 */
			'001000'	/* - */
		);
		$this->setText($text);
		$this->textfont = $textfont;
	}

	/**
	 * Saves Text
	 *
	 * @param string $text
	 */
	public function setText($text) {
		$this->text = $text;
	}

	/**
	 * Draws the barcode
	 *
	 * @param ressource $im
	 */
	public function draw($im) {
		$error_stop = false;

		// Checking if all chars are allowed
		for($i=0;$i<strlen($this->text);$i++) {
			if(!is_int(array_search($this->text[$i],$this->keys))) {
				$this->DrawError($im,'Char \''.$this->text[$i].'\' not allowed.');
				$error_stop = true;
			}
		}
		if($error_stop == false) {
			// Starting Code
			$this->DrawChar($im,'001100',1);
			// Chars
			for($i=0;$i<strlen($this->text);$i++)
				$this->DrawChar($im,$this->findCode($this->text[$i]),1);
			// Checksum
			// First CheckSUM "C"
			// The "C" checksum character is the modulo 11 remainder of the sum of the weighted
			// value of the data characters. The weighting value starts at "1" for the right-most
			// data character, 2 for the second to last, 3 for the third-to-last, and so on up to 20.
			// After 10, the sequence wraps around back to 1.

			// Second CheckSUM "K"
			// Same as CheckSUM "C" but we count the CheckSum "C" at the end
			// After 9, the sequence wraps around back to 1.
			$sequence_multiplier = array(10,9);
			$temp_text = $this->text;
			for($z=0;$z<2;$z++) {
				// We don't display the K CheckSum if the original text had a length less than 10
				if(strlen($temp_text)<=10 && $z==1)
					break;
				$checksum = 0;
				for($i=strlen($temp_text),$j=0;$i>0;$i--,$j++) {
					$multiplier = $i % $sequence_multiplier[$z];
					if($multiplier==0)
						$multiplier=$sequence_multiplier[$z];
					$checksum += $this->findIndex($temp_text[$j]) * $multiplier;
				}
				$this->DrawChar($im,$this->findCode($checksum % 11),1);
				$temp_text .= $this->keys[$checksum % 11];
			}
			// Ending Code
			$this->DrawChar($im,'00110',1);
			$this->lastX = $this->positionX;
			$this->lastY = $this->maxHeight;
			$this->DrawText($im);
		}
	}
}

class MLBarcode {
    public function shortcode ($atts,$content,$tag){
header("Content-Type: image/png");
        $bar_types = [11,128];
        $bar_def=128;
        $bar_print = filter_input(INPUT_GET, 'barprint',FILTER_DEFAULT);
        $bar_type = filter_input(INPUT_GET, 'bartype',FILTER_SANITIZE_NUMBER_INT);
        $barcode = filter_input(INPUT_GET, 'barcode',FILTER_SANITIZE_NUMBER_INT);
        $code = filter_input(INPUT_GET, 'zbarcode',FILTER_SANITIZE_NUMBER_INT);
        if(!in_array($bar_type,$bar_types))$bar_type=$bar_def;
        if($bar_type==128)
            $barcode = filter_input(INPUT_GET, 'barcode',FILTER_DEFAULT);
        
        $atts = shortcode_atts( array( 'barcode' => $barcode,'bar_type' => $bar_type, ), $atts );
        $bar_type = $atts['bar_type'];
        $barcode = $atts['barcode'];
        
        $code=123;
        if(!$barcode)$barcode=$code;
        echo MLBarcode::img($barcode);
//        return;
if(false && $code){
$default_value = array();
$default_value['output'] = 1;
$default_value['thickness'] = 30;
$default_value['res'] = 1;
$default_value['font'] = 2;
$default_value['text2display'] = '';
$default_value['a1'] = '';
$default_value['a2'] = '';

		$color_black = new FColor(0,0,0);
		$color_white = new FColor(255,255,255);
//		if(!empty($_GET['a2']))
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f'],$_GET['a1'],$_GET['a2']);
//		elseif(!empty($_GET['a1']))
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f'],$_GET['a1']);
//		else
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f']);
        
//        $textfont = '';
        
        $output = 1;
        $textfont = 2;
        $res = 1;
        $thickness = 30;
        
//        $code_generated = new code128($thickness,$color_black,$color_white,$res,$code,$textfont);
//        $code_generated = new code11($thickness,$color_black,$color_white,$res,$code,$textfont);
        $bar_class  = 'code'. $bar_type;
        $code_generated = new $bar_class($thickness,$color_black,$color_white,$res,$code,$textfont);
		$drawing = new FDrawing(1024,1024,'',$color_white);
		$drawing->init();
		$drawing->add_barcode($code_generated);
		$drawing->draw_all();
		$im = $drawing->get_im();
		$im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
		imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
		$drawing->set_im($im2);
		$drawing->finish($output);
        
        exit();
}
    }
    public static function img ($barcode=123,$thickness_def = 30,$barfont_def  = 2){
        $bar_types = [11,128];
        $bar_def=128;
//        $thickness_def = 30;
//        $barfont_def  = 2;
        $bar_print = filter_input(INPUT_GET, 'barprint',FILTER_DEFAULT);
        $bar_type = filter_input(INPUT_GET, 'bartype',FILTER_SANITIZE_NUMBER_INT);
        $thickness = filter_input(INPUT_GET, 'barweight',FILTER_SANITIZE_NUMBER_INT);
        $barfont = filter_input(INPUT_GET, 'barfont',FILTER_SANITIZE_NUMBER_INT);
//        $barcode = filter_input(INPUT_GET, 'barcode',FILTER_SANITIZE_NUMBER_INT);
        $code = filter_input(INPUT_GET, 'zbarcode',FILTER_SANITIZE_NUMBER_INT);
        if(!in_array($bar_type,$bar_types))$bar_type=$bar_def;
        if($bar_type==128)
            $code = filter_input(INPUT_GET, 'zbarcode',FILTER_DEFAULT);
        
        if(!$thickness) $thickness = $thickness_def;
        if(!$barfont) $barfont = $barfont_def;
//    $code=123;
//    if(!$barcode)$barcode=$code;
//    if($bar_print=='print'){
        $img = '<img src="/barcode/'
                
                . '?zbarcode='.urlencode($barcode)
                . '&bartype='.urlencode($bar_type)
                . '&barweight='.urlencode($thickness).''
                . '&barfont='.urlencode($barfont).'"'
                
                . ' alt="Error? Can\'t display image!" width="200px"'
                . ' id="ml_bar_'.urlencode($barcode).'" '
                . ' onclick="return barcode_print(\''.urlencode($barcode).'\');"  />';
        return $img;
//    }
    }
    public static function img_file ($barcode=123,$dir_path = null, $prefix = 'bar',$bc_attr=[],$bar_type = null,$thickness = null,$barfont = null){
       
        if($bc_attr){
            foreach($bc_attr as $k=>&$item){
                $item =  ' '.$k.'="'.$item.'"';
            }
            $bc_attr = implode(' ',$bc_attr);
        }else $bc_attr = '';
        
        $bar_types = [11,128];
        $bar_def=128;
        $thickness_def = 30;
        $barfont_def  = 2;
//        $bar_print = filter_input(INPUT_GET, 'barprint',FILTER_DEFAULT);
        $bar_type = filter_input(INPUT_GET, 'bartype',FILTER_SANITIZE_NUMBER_INT);
        $thickness = filter_input(INPUT_GET, 'barweight',FILTER_SANITIZE_NUMBER_INT);
        $barfont = filter_input(INPUT_GET, 'barfont',FILTER_SANITIZE_NUMBER_INT);
//        $barcode = filter_input(INPUT_GET, 'barcode',FILTER_SANITIZE_NUMBER_INT);
        $code = filter_input(INPUT_GET, 'zbarcode',FILTER_SANITIZE_NUMBER_INT);
        if(!in_array($bar_type,$bar_types))$bar_type=$bar_def;
        if($bar_type==128)
            $code = filter_input(INPUT_GET, 'zbarcode',FILTER_DEFAULT);
        
        if($prefix) $prefix.='_';
        $r=[];
        $r[ABSPATH] = get_site_url().'/';
        
        $file_name = $prefix . $barcode . '.png';
        $file_name = $prefix . $barcode . '_' . $thickness . '_' . $bar_type . '_' . $barfont . '.png';
        $file_url = strtr($dir_path,$r) . $file_name;
        $file_path = $dir_path . $file_name;
        
        if(!$thickness) $thickness = $thickness_def;
        if(!$barfont) $barfont = $barfont_def;
//    $code=123;
//    if(!$barcode)$barcode=$code;
//    if($bar_print=='print'){
        $file_url = self::create_img_file($barcode, $dir_path, $prefix, $bar_type, $thickness, $barfont);
        $url = '/barcode/'
                
                . '?zbarcode='.urlencode($barcode)
                . '&bartype='.urlencode($bar_type)
                . '&barweight='.urlencode($thickness).''
                . '&barfont='.urlencode($barfont).''
                ;
        $url = $file_url ;
        $img = '<img src="'.$url.'"'
                . $bc_attr
                . ' alt="Error? Can\'t display image!" width="200px"'
                . ' id="ml_bar_'.urlencode($barcode).'" '
                . ' onclick="return barcode_print(\''.urlencode($barcode).'\');"  />';
        return $img;
//    }
    }
    public static function
        create_img_file($barcode=123, $dir_path = null, $prefix = 'bar', $bar_type = null, $thickness = null, $barfont = null){
        
        if(!$dir_path)return false;

        $bar_types = [11,128];
        $bar_def=128;
        $thickness_def = 30;
        $barfont_def  = 2;
        
//        $bar_print = filter_input(INPUT_GET, 'barprint',FILTER_DEFAULT);
//        $bar_type = filter_input(INPUT_GET, 'bartype',FILTER_SANITIZE_NUMBER_INT);
//        $thickness = filter_input(INPUT_GET, 'barweight',FILTER_SANITIZE_NUMBER_INT);
//        $barfont = filter_input(INPUT_GET, 'barfont',FILTER_SANITIZE_NUMBER_INT);
//        $barcode = filter_input(INPUT_GET, 'barcode',FILTER_SANITIZE_NUMBER_INT);
//        $code = filter_input(INPUT_GET, 'zbarcode',FILTER_SANITIZE_NUMBER_INT);
        
//        $bar_print = false;
        $bar_type = false;
//        $thickness = false;
        $barfont = false;
//        $barcode = false;
        $code = $barcode;
        
        if(!in_array($bar_type,$bar_types))$bar_type=$bar_def;
//        if($bar_type==128)
//        $code = filter_input(INPUT_GET, 'zbarcode',FILTER_DEFAULT);

        if(!$thickness) $thickness = $thickness_def;
        if(!$barfont) $barfont = $barfont_def;

        /*
         * ==========
         */
        
        if($prefix) $prefix.='_';
        $r=[];
        $r[ABSPATH] = get_site_url().'/';
        
        $file_name = $prefix . $barcode . '.png';
        $file_name = $prefix . $barcode . '_' . $thickness . '_' . $bar_type . '_' . $barfont . '.png';
        $file_url = strtr($dir_path,$r) . $file_name;
        $file_path = $dir_path . $file_name;
        
        if(file_exists($file_path))return $file_url;

        /*
         * ==========
         */
//        header("Content-Type: image/png");
        $default_value = array();
        $default_value['output'] = 1;
        $default_value['thickness'] = 30;
        $default_value['res'] = 1;
        $default_value['font'] = 2;
        $default_value['text2display'] = '';
        $default_value['a1'] = '';
        $default_value['a2'] = '';

        $color_black = new FColor(0,0,0);
        $color_white = new FColor(255,255,255);
//		if(!empty($_GET['a2']))
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f'],$_GET['a1'],$_GET['a2']);
//		elseif(!empty($_GET['a1']))
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f'],$_GET['a1']);
//		else
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f']);

//        $textfont = '';

        $output = 1;
        $textfont = 2;
        $textfont = $barfont;
        $res = 1;
//        $thickness = 30;

//        $code_generated = new code128($thickness,$color_black,$color_white,$res,$code,$textfont);
//        $code_generated = new code11($thickness,$color_black,$color_white,$res,$code,$textfont);
        $bar_class  = 'code'. $bar_type;
        $code_generated = new $bar_class($thickness,$color_black,$color_white,$res,$code,$textfont,'C');
        $drawing = new FDrawing(1024,1024,$file_path,$color_white);
        $drawing->init();
        $drawing->add_barcode($code_generated);
        $drawing->draw_all();
        $im = $drawing->get_im();
        $im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
        imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
        $drawing->set_im($im2);
        $drawing->finish($output);
//                exit();
        return $file_url;
    }
}
$MLBarcode = new MLBarcode();
add_shortcode('ml_barcode',[$MLBarcode, 'shortcode']);

if(10){


$bar_types = [11,128];
$bar_def=128;
$thickness_def = 30;
$barfont_def  = 2;
$bar_print = filter_input(INPUT_GET, 'barprint',FILTER_DEFAULT);
$bar_type = filter_input(INPUT_GET, 'bartype',FILTER_SANITIZE_NUMBER_INT);
$thickness = filter_input(INPUT_GET, 'barweight',FILTER_SANITIZE_NUMBER_INT);
$barfont = filter_input(INPUT_GET, 'barfont',FILTER_SANITIZE_NUMBER_INT);
$barcode = filter_input(INPUT_GET, 'barcode',FILTER_SANITIZE_NUMBER_INT);
$code = filter_input(INPUT_GET, 'zbarcode',FILTER_SANITIZE_NUMBER_INT);
if(!in_array($bar_type,$bar_types))$bar_type=$bar_def;
if($bar_type==128)
$code = filter_input(INPUT_GET, 'zbarcode',FILTER_DEFAULT);

if(!$thickness) $thickness = $thickness_def;
if(!$barfont) $barfont = $barfont_def;

if(!$code){
    if(0){
        $code=123;
        if(!$barcode)$barcode=$code;
        if($bar_print=='print'){
            $img = '<img src="wsd_barcode.php?zbarcode='.urlencode($barcode).'&bartype='.urlencode($bar_type).'" alt="Error? Can\'t display image!" width="100%" />';
            echo $img;
        }else{
        ?>
    <script>
        function barcode_print(){
            var barform = document.getElementById('barform');
    //        barform.style('display','none');
            barform.style.display = 'none';
    //        console.log(barform);
            window.print();
            barform.style.display = 'block';
            return false;
        }
    </script>
    <?php
            echo '<center>';
            $img = '<img src="wsd_barcode.php?zbarcode='.urlencode($barcode).'&bartype='.urlencode($bar_type).'" alt="Error? Can\'t display image!" width="100%" />';
            echo $img;
            echo '<form id = "barform">';
            echo '<br/><input name="barcode" value="'.$barcode.'">';
            echo '<br/><input name="bartype" value="'.$bar_type.'">';
                $bar_class  = 'code'. $bar_type;
            echo '<br/>'.$bar_class;
            echo '<br/>'.$bar_print;
            echo '<br/><button type="submit" name="barprint" value="send">send</button>';

            echo '<br/><button type="button" name="barprint" value="print" onclick="return barcode_print();">print</button>';
            echo '</form>';
            echo '</center>';

        }
    }
}else{
header("Content-Type: image/png");
$default_value = array();
$default_value['output'] = 1;
$default_value['thickness'] = 30;
$default_value['res'] = 1;
$default_value['font'] = 2;
$default_value['text2display'] = '';
$default_value['a1'] = '';
$default_value['a2'] = '';

		$color_black = new FColor(0,0,0);
		$color_white = new FColor(255,255,255);
//		if(!empty($_GET['a2']))
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f'],$_GET['a1'],$_GET['a2']);
//		elseif(!empty($_GET['a1']))
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f'],$_GET['a1']);
//		else
//			$code_generated = new $_GET['code']($_GET['t'],$color_black,$color_white,$_GET['r'],$_GET['text'],$_GET['f']);
        
//        $textfont = '';
        
        $output = 1;
        $textfont = 2;
        $textfont = $barfont;
        $res = 1;
//        $thickness = 30;
        
//        $code_generated = new code128($thickness,$color_black,$color_white,$res,$code,$textfont);
//        $code_generated = new code11($thickness,$color_black,$color_white,$res,$code,$textfont);
        $bar_class  = 'code'. $bar_type;
        $code_generated = new $bar_class($thickness,$color_black,$color_white,$res,$code,$textfont,'C');
		$drawing = new FDrawing(1024,1024,'',$color_white);
		$drawing->init();
		$drawing->add_barcode($code_generated);
		$drawing->draw_all();
		$im = $drawing->get_im();
		$im2 = imagecreate($code_generated->lastX,$code_generated->lastY);
		imagecopyresized($im2, $im, 0, 0, 0, 0, $code_generated->lastX, $code_generated->lastY, $code_generated->lastX, $code_generated->lastY);
		$drawing->set_im($im2);
		$drawing->finish($output);
        exit();
    
}
//http://bz._sandbox/bz12/medlab_15/test/wsd_barcode.php


}