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
            ->findAll();
            var_dump($models);
        if (is_empty($models)) {
            throw $this->createNotFoundException( 'No product found ' );
        }

        return $this->render('products.html.twig', ['models' => $models]);

        // or render a template
        // in the template, print things with {{ product.name }}
        // return $this->render('product/show.html.twig', ['product' => $product]);
    }
    // retrieve GET and POST variables respectively
}
