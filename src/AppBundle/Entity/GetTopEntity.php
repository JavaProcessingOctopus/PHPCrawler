<?php

namespace AppBundle\Entity;

use Symfony\Component\DomCrawler\Crawler;

class GetTopEntity {
    protected $url;
    protected $topNumber;
    
    protected $top;

    public function getUrl() {
        return $this->url;
    }

    public function setUrl($url) {
        $this->url = $url;
    }

    public function getTopNumber() {
        return $this->topNumber;
    }

    public function setTopNumber($topNumber) {
        $this->topNumber = $topNumber;
    }
    
    public function getTop() {
    	if (! isset($this->top)) {
    		$this->initTop();
    	}
    	return $this->top;
    }
    
    private function initTop() {
    	// Step 1 - Get WebPage
    	$crawler = new Crawler(file_get_contents($this->url));
    	$raw = implode($crawler->each(function (Crawler $node, $i) {
    		return $node->text();
    	}));
    		// Step 2 - Put all words in a word & number map
    		$map = array_count_values(
    				preg_split("/[\s,]+/", $raw)
    				);
    		// Step 2.1 - Keep only words longer than 3
    		$map = array_filter($map, array($this, 'wordPostFilter'), ARRAY_FILTER_USE_KEY);
    		
    		arsort( $map );
    		// Step 3 - Get top 10 from map and set attribute
    		$this->top = array_slice ( $map, 0, $this->topNumber );
    }
    
    private function wordPostFilter($word) { return strlen($word) >3; }
}