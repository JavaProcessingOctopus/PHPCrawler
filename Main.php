<?php
	function getWebPage($url) {
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}	
	
	function parseToMap($text) {
		$array = array();
		
		$currentWord = "";
		for($i=0; $i<strlen($text); $i++) {
			if(separator($text[$i])) {
				if (isset($array[ $currentWord ])) {
					$array[ $currentWord ] = $array[ $currentWord ] + 1;
				} else {
					$array[ $currentWord ] = 1;
				}
				$currentWord = "";
			} else {
				$currentWord = $currentWord . strtolower ( $text[$i] );
			}
			echo $currentWord;
		}
		
		return $array;
	}
	
	function separator($char) {
		return ctype_space($char) || ctype_punct($char) || $char == "";
	}

	// Step 1 - Get page
	$htmlRaw = getWebPage('php.net');
	//echo $htmlRaw;
	$text = strip_tags($htmlRaw);
	
	// Step 2 - Put all words in a word & number map
	$map = parseToMap($text);
	
	// Step 3 - get top 10
	arsort( $map );
	$map = array_slice($map, 0, 10);
	print_r($map);
?>