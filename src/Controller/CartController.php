<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\Model;
use App\Entity\Material;

/**
 * @Route("/cart")
 */
class CartController extends Controller
{
	/**
	 * @Route("", name="cart")
	 */
	public function index() {
		$session = new Session();
		$cart = $session->get('cart', []);

		// Get ids on whole cart
		$ids = array_map(function($cartItem) {
			return $cartItem['id'];
		}, $cart);
		// Fetch all models
		$modelsRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->findById($ids);
		// Associate them with key
		$modelsById = array_reduce($modelsRaw, function($acc, $model) {
			$acc[$model->getId()] = $model;
			return $acc;
		}, []);
		// Replace ids by entities
		$helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
		$materialsCache = [];
		$cartList = array_map(function($cartItem) use ($modelsById, $helper, $materialsCache) {
			$materialsWanted = $cartItem['materials'];

			$matsOnParts = $this->getMaterialsForParts($materialsWanted, $materialsCache);
			$infos = $modelsById[$cartItem['id']]->computeModelInfos($helper, $matsOnParts);
			$infos['parts'] = $matsOnParts;
			return $infos;
		}, $cart);

		$materialsCache = [];
		foreach($cartList as $key => $cart){
			echo $this->get('jms_serializer')->serialize($cart, 'json');
		}

		return $this->render('pages/cart/cart.html.twig', ['cartList' => $cartList]);
	}

	/**
	 * @Route("/add/{slug}", name="addToCart")
	 */
	public function addToCart(Request $request, $slug){
		$modelRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->findOneBySlug($slug);

		$materialsWanted = array_map('intval', $request->request->get('materials'));

		$matsOnParts = $this->getMaterialsForParts($materialsWanted);
		$helper = $this->container->get('vich_uploader.templating.helper.uploader_helper');
		$modelInfos = $modelRaw->computeModelInfos($helper, $matsOnParts);

		$modelInfos['materials'] = $materialsWanted;
		$modelInfos['id'] = $modelInfos['entity']->getId();
		$modelInfos['count'] = 1;
		unset($modelInfos['entity']);

		$session = new Session();
		$cart = $session->get('cart', []);
		$cartSum = $session->get('cartSum', 0);

		$cart[] = $modelInfos;
		$cartSum += $modelInfos['price'];

		$session->set('cart', $cart);
		$session->set('cartSum', $cartSum);

		return $this->redirectToRoute('cart');
	}

	private function getMaterialsForParts($partsAssociation, &$materialsCache = []){
		// Retrieve mats from DB
		foreach($partsAssociation as $matSlot => $matId){
			if(!isset($materialsCache[$matId])){
				$materialsCache[$matId] = $this->getDoctrine()
					->getRepository(Material::class)
					->findOneById($matId);
			}
		}

		// Inject them in wanted
		$matsOnParts = array_map(function($matId) use ($materialsCache) {
			return $materialsCache[$matId];
		}, $partsAssociation);

		return $matsOnParts;
	}

	public function cartSum(){
		$session = new Session();
		$cart = $session->get('cart', []);

		$count = 0;
		foreach($cart as $cartItem){
			$count += $cartItem['count'];
		}

		$amount = 0;

		return $this->render( 'components/cart_indicator.html.twig', [
			'amount' => $session->get('cartSum', 0),
			'count' => $count,
		]);
	}
}
