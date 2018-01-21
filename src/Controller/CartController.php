<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\Model;

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
		var_dump($ids);
		$modelsRaw = $this->getDoctrine()
			->getRepository(Model::class)
			->findById($ids);
		foreach($modelsRaw as $model){
			echo $this->get('jms_serializer')->serialize($model, 'json');
			$cartList[] = [ 'model' => $model ];
		}

		return $this->render('pages/cart/cart.html.twig', ['cartList' => $cartList]);
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
			'amount' => $amount,
			'count' => $count,
		]);
	}
}
