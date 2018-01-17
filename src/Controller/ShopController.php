<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Model;

class ShopController extends Controller
{
	/**
	 * @Route(
   *     "/products/{page}",
   *     defaults={"page": 1},
   *     requirements={"page": "\d+"},
   *      name="products")
	 */
	public function index(Request $request) {
		// replace this line with your own code!
		$query = $request->request;
		$pageIndex = $query->getInt('page');
		var_dump($pageIndex);

		$models = $this->getDoctrine()
			->getRepository(Model::class)
			->findAll(['price' => 'ASC']);

		return $this->render('pages/products.html.twig', ['models' => $models]);
	}
}
