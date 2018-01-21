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

		$modelsRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->getPaged($pageIndex);

		$helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
		$models = [];
		foreach($modelsRaw as $modelRaw){
			$models[$modelRaw->getId()] = $modelRaw->computeModelInfos($helper);
		}

		return $this->render('pages/shop/products.html.twig', ['models' => $models]);
	}
	/**
	 * @Route(
	 *     "/product/{slug}",
	 *      name="product")
	 */
	public function product(Request $request) {
		$query = $request->request;
		$slug = $query->get('slug');

		$model = $this->getDoctrine()
		->getRepository(Model::class)
		->findOneBySlug($slug);

		return $this->render('pages/shop/products.html.twig', ['model' => $model]);
	}
}

/*

		foreach($models as $model){
			echo $this->get('jms_serializer')->serialize($model, 'json');
		}
		exit();
		*/
