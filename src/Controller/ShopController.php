<?php

namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use App\Entity\Admin;

class ShopController extends Controller
{
	/**
	 * @Route("/catalogue/{page}", name="catalogue")
	 */
	public function index($id) {
		// replace this line with your own code!
    $page= $this->getDoctrine()
            ->getRepository(Model::class)
            ->findAll($id);

        if (!$page) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }

        return new Response('Check out this great product: '.$page->getName());

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }
    // retrieve GET and POST variables respectively
}
