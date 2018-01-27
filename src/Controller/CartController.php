<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
		$cart->loadFromSession();

		/*foreach($cartList as $key => $cart){
			echo '<pre>'.$this->get('jms_serializer')->serialize($cart, 'json').'</pre>';
		}
		foreach($materialsCache as $key => $material){
			echo '<pre>'.$this->get('jms_serializer')->serialize($material, 'json').'</pre>';
		}*/

		return $this->render('pages/cart/cart.html.twig', [
			'cartList' => $cart->getCartList(),
			'materials' => $cart->getMaterials(),
			]
		);
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
			]
		);
	}
}
