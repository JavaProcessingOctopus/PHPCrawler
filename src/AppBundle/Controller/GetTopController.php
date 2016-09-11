<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use AppBundle\Entity\GetTopEntity;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class GetTopController extends Controller {
	/**
	 * @Route("/getTop")
	 */
	public function newAction(Request $request)
	{
		// create a task and give it some dummy data for this example
		$entity = new GetTopEntity();
		$entity->setUrl('http://php.net');
		$entity->setTopNumber(10);
	
		$form = $this->createFormBuilder($entity)
		->add('url', TextType::class)
		->add('topNumber', NumberType::class)
		->add('save', SubmitType::class, array('label' => 'Crawl page'))
		->getForm();
	
		$form->handleRequest($request);
	
		if ($form->isSubmitted() && $form->isValid()) {
			// $form->getData() holds the submitted values
			// but, the original `$task` variable has also been updated
			$entity = $form->getData();
			$url = $entity->getUrl();
			$topNumber = $entity->getTopNumber();
	
			// ... perform some action, such as saving the task to the database
			// for example, if Task is a Doctrine entity, save it!
			// $em = $this->getDoctrine()->getManager();
			// $em->persist($task);
			// $em->flush();
	
			return $this->getTopWord($url, $topNumber);
		}
	
		return $this->render('getTopForm.html.twig', array(
				'form' => $form->createView(),
		));
	}
	
	private function getTopWord($url, $topNumber) {
		//$url = "http://php.net";
		//$topNumber = 10;
		
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