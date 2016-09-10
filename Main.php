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

	// Step 1 - Get page
	echo getWebPage("http://php.net/");
	
	// Step 2 - Put all words in a word & number map
	// Step 3 - get top 10
?>