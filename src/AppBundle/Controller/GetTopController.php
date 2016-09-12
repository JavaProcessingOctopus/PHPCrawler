<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
			// $em = $this->getDoctrine()->getManager();
			// $em->persist($entity);
			// $em->flush();
			
			// Build response
			$responseStr = "";
			foreach ($entity->getTop() as $key => $value){
				$responseStr .= $key." : ".$value."<br />";
			}
			
			return new Response( $responseStr );
		}
	
		return $this->render('getTopForm.html.twig', array(
				'form' => $form->createView(),
		));
	}
	
}