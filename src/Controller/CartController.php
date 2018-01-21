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

		$cartList = [];
		$ids = array_map(function($cartItem) {
			return $cartItem['id'];
		}, $cart);
		$modelsRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->findById($ids);
		foreach($modelsRaw as $model){
			echo $this->get('jms_serializer')->serialize($model, 'json');
			$cartList[] = [ 'model' => $model ];
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

		$materials = [];
		$materialsWanted = $request->request->get('materials');

		// Retrieve mats from DB
		foreach($materialsWanted as $matSlot => $matId){
			$materialsWanter[$matSlot] = intval($matId);
			if(!isset($materials[$matId])){
				$materials[$matId] = $this->getDoctrine()
					->getRepository(Material::class)
					->findOneById($matId);
			}
		}

		// Inject them in wanted
		$matsOnParts = [];
		foreach($materialsWanted as $matSlot => $matId){
			$matsOnParts[$matSlot] = $materials[$matId];
		}
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
