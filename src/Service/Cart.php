<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use \Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use App\Service\Debug;

use App\Entity\Model;
use App\Entity\Material;

use App\GenericClass\Cart as BaseCart;
use App\GenericClass\ICartItem;

class Cart extends BaseCart{
	const SESSION_CART_KEY = 'cart';

	private $session = null;
	private $modelRepository = null;
	private $materialRepository = null;
	private $uploaderHelper = null;
	private $materials = null;

	public function __construct(EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper, Debug $debug){
		$this->session = new Session();
		$this->modelRepository = $entityManager->getRepository(Model::class);
		$this->materialRepository = $entityManager->getRepository(Material::class);
		$this->uploaderHelper = $uploaderHelper;
		$this->materials = $this->materialRepository->findAll();
		parent::__construct(function($ids){
			return $this->modelRepository->findById($ids);
		}, function($ids){
			return $this->materialRepository->findById($ids);
		}, function($model, $materials){
			return $model->computeModelInfos($this->uploaderHelper, $materials);
		});
		$this->deserialize($this->session->get(self::SESSION_CART_KEY, []));
		dump('Inited cart', $this);
	}

	public function getMaterials(){
		return $this->materials;
	}

	public function getTotalPrice(){
		$sum = 0;
		foreach($this->cart as $cartItem){
			$sum += $cartItem['price'];
		}
		return $sum;
	}

	public function addItem(ICartItem $product, array $attrs, $count = 1){
		parent::addItem($product, $attrs, $count);
		$this->session->set(self::SESSION_CART_KEY, $this->serialize());
	}
}
