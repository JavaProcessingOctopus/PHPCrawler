<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;

class GetTopController extends Controller {
	/**
	 * @Route("/getTop")
	 */
	public function getTopWord() {
		$url = "http://php.net";
		$topNumber = 10;
		
		// Step 1 - get Page
		$crawler = new Crawler(file_get_contents($url));
		$raw = implode($crawler/*->filter('body')*/->each(function (Crawler $node, $i) {
			return $node->text();
		}));
		// Step 2 - Put all words in a word & number map
		$map = array_count_values(
			preg_split("/[\s,]+/", $raw)
		);
		// Step 2.1 - Kepp only words longer than 3
		$map = array_filter($map, array($this, 'wordPostFilter'), ARRAY_FILTER_USE_KEY);
		arsort( $map );
		// Step 3 - get top 10
		$map = array_slice ( $map, 0, $topNumber );
		
		// Step 4 - Write response
		$responseStr = "";
		foreach ($map as $key => $value){
			$responseStr .= $key."<br />";
		}
		
		return new Response($responseStr);
	}
	
	function wordPostFilter($word) { return strlen($word) >3; }
}