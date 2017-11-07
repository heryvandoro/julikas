<?php
namespace App\Helpers;

class STR{	
		static public function startsWith($haystack, $needle) {
			return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
		}

		static public function endsWith($haystack, $needle) {
			return $needle === "" || (($temp = strlen($haystack) - strlen($needle)) >= 0 && strpos($haystack, $needle, $temp) !== false);
		}
		static public function clean($string) {
			$string = str_replace(' ', '-', $string); 
			return preg_replace('/[^A-Za-z0-9\-]/', '', $string); 
		}
}