<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use App\Entity\Model;
use App\Entity\Material;
use App\Service\Cart;

/**
* @Route("/cart")
*/
class CartController extends Controller
{
	/**
	* @Route("", name="cart")
	*/
	public function index(Cart $cart) {
		return $this->render('pages/cart/cart.html.twig', [
			'cartList' => $cart->getCart(),
			'materials' => $cart->getMaterials(),
			]
		);
	}

	/**
	* @Route("/add/{slug}", name="addToCart")
	* @Method("POST")
	*/
	public function addToCart(Cart $cart, Request $request, $slug){
		$model = $this->getDoctrine()
		->getRepository(Model::class)
		->findOneBySlug($slug);

		$materialsWanted = array_map('intval', $request->request->get('materials'));

		$cart->addItem($model, $materialsWanted);

		return $this->redirectToRoute('cart');
	}

	/**
	* @Route("/empty", name="emptyCart")
	*/
	public function emptyCart(Cart $cart){
		$cart->removeItem(true);
		return $this->redirectToRoute('cart');
	}

	/**
	 * ContactAction
	 *
	 * @Route("/remove/{index}", name="removeFromCart", condition="request.isXmlHttpRequest()")
	 */
	public function deleteFromCart(Cart $cart, $index){
		$cartItem = $cart->getCart()[intval($index) - 1];
		$cart->removeItem($cartItem['entity'], array_map(function($material){
			return $material->getId();
		}, $cartItem['attrs']));
	}

	public function cartSum(Cart $cart){
		$count = 0;
		foreach($cart->getCart() as $cartItem){
			$count += $cartItem[$cart::COUNT_KEY];
		}

		return $this->render( 'components/cart_indicator.html.twig', [
			'amount' => $cart->getTotalPrice(),
			'count' => $count,
			]
		);
	}
}
