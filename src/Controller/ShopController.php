<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use App\GenericClass\Collection;

use App\Service\ProductInfos;
use App\Entity\Model;
use App\Entity\VariableAttribute;

class ShopController extends Controller
{
	/**
	 * @Route(
	 *     "/products/{page}",
	 *     defaults={"page": 1},
	 *     requirements={"page": "\d+"},
	 *      name="products")
	 */
	public function index(ProductInfos $prodInfos, Request $request, $page) {
		$modelsRaw        = $this->getDoctrine()
			->getRepository(Model::class)
			->getPaged($page);

		$modelsInfos = $prodInfos->getProductsPresentationInfos(Collection::fromIterable($modelsRaw));

		return $this->render('pages/shop/products.html.twig', ['models' => $modelsInfos]);
	}
	/**
	 * @Route(
	 *     "/product/{slug}",
	 *      name="product")
	 */
	public function product(ProductInfos $prodInfos, $slug) {
		$model  = $this->getDoctrine()
			->getRepository(Model::class)
			->findOneBySlug($slug);

		$modelInfos = $prodInfos->getProductPresentationInfos($model);
		$attributesByCategory = $prodInfos->getPossibleAttributeForProduct($model);

		return $this->render('pages/shop/product_page.html.twig', [
			'model' => $modelInfos,
			'attributesByCategory' => $attributesByCategory,
		]);
	}
}

/*

		foreach($models as $model){
			echo $this->get('jms_serializer')->serialize($model, 'json');
		}
		exit();
		*/
