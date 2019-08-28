<?php
	class MappaDeiCognomi {
		public function __construct(){
			//
		}
		private function encodeSurname($surname){
			$RSurname = str_replace(["è", "é", "ò", "ì", "ù", "à", "È", "É", "Ò", "Ì", "Ù", "À"], ["e'", "e'", "o'", "i'", "u'", "a'", "E'", "E'", "O'", "I'", "U'", "A'"], $surname);
		    $bArr = [24, 45, 125, 23, 86, 32, 3, 12, 99, 31, 111, 121, 23, 125, 14, 56];
		    $bytes = array();
		    $string = "";
			for($i = 0; $i < strlen($RSurname); $i++)
			     $bytes[] = ord($RSurname[$i]);
		    for ($i = 0; $i < count($bytes); $i++) {
		        $bytes[$i] = ($bytes[$i] ^ $bArr[(count($bytes) - ($i * 5)) & 15]);
		        $string .= sprintf("%02X", strval($bytes[$i]));
		    }
		    return $string;
		}
		public function generateImage($surname, $output='mdc/output.png', $background='mdc/bg.png', $tempOutput='mdc/temp.png')
		{
			$image = imagecreatefrompng($background);
			$encodedSurname = encodeSurname($surname);
			$test = file_put_contents($tempOutput, file_get_contents("http://www.mappadeicognomi.eu/image.php?sur=$encodedSurname"));
		  	$createTemporary = imagecreatefrompng($tempOutput);
			imagecopymerge($image, $createTemporary, 0,0,0,0,1000,1000,100);
			imagepng($image, $output);
		}
	}