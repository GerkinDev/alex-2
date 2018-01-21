<?php

namespace App\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\Entity\Model;
use App\Entity\Material;

class ShopController extends Controller
{
	/**
	 * @Route(
	 *     "/products/{page}",
	 *     defaults={"page": 1},
	 *     requirements={"page": "\d+"},
	 *      name="products")
	 */
	public function index(Request $request, $page) {
		$modelsRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->getPaged($page);

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
	public function product($slug) {
		$modelRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->findOneBySlug($slug);
		$materials = $this->getDoctrine()
			->getRepository(Material::class)
			->findAll();

		$helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
		$model = $modelRaw->computeModelInfos($helper);

		return $this->render('pages/shop/product_page.html.twig', [
			'model' => $model,
			'materials' => $materials,
		]);
	}
}

/*

		foreach($models as $model){
			echo $this->get('jms_serializer')->serialize($model, 'json');
		}
		exit();
		*/
