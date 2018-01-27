<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use \Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use App\Service\Debug;

use App\Entity\Cart as CartEntity;

use App\Entity\Model;
use App\Entity\Material;

class Cart {
	const SESSION_CART_KEY = 'cart';
	const SESSION_CART_SUM = 'cartSum';

	private $session = null;
	private $modelRepository = null;
	private $materialRepository = null;
	private $uploaderHelper = null;
	private $cartList = null;
	private $materials = null;

	public function __construct(EntityManagerInterface $entityManager, UploaderHelper $uploaderHelper, Debug $debug){
		$this->session = new Session();
		$this->modelRepository = $entityManager->getRepository(Model::class);
		$this->materialRepository = $entityManager->getRepository(Material::class);
		$this->uploaderHelper = $uploaderHelper;
	}

	public function loadFromSession(){
		$cartData = $this->session->get(self::SESSION_CART_KEY, []);

		// Get ids on whole cart
		$ids = array_unique(array_map('intval', array_map(function($cartItem) {
			return $cartItem['id'];
		}, $cartData)));
		// Fetch all models
		$modelsRaw = $this->modelRepository->findById($ids);
		// Associate them with key
		$modelsById = array_reduce($modelsRaw, function($acc, $model) {
			$acc[$model->getId()] = $model;
			return $acc;
		}, []);
		// Replace ids by entities
		$this->materials = array_reduce( $this->materialRepository->findAll(), function($acc, $material){
			$acc[$material->getId()] = $material;
			return $acc;
		}, []);
		$this->cartList = array_map(function($cartItem) use ($modelsById) {
			$materialsWanted = $cartItem['materials'];

			$matsOnParts = $this->getMaterialsForParts($materialsWanted, $this->materials);
			$infos = $modelsById[$cartItem['id']]->computeModelInfos($this->uploaderHelper, $matsOnParts);
			$infos['parts'] = $matsOnParts;
			$infos['count'] = $cartItem['count'];
			$infos['masses'] = $modelsById[$cartItem['id']]->getMasses(true);
			return $infos;
		}, $cartData);

		Debug::getInstance()->dumpCollection($this->cartList);

		return $this;
	}

	private function getMaterialsForParts($partsAssociation, &$materialsCache = []){
		// Retrieve mats from DB
		foreach($partsAssociation as $matSlot => $matId){
			if(!isset($materialsCache[$matId])){
				$materialsCache[$matId] = $this->materialRepository->findOneById($matId);
			}
		}

		// Inject them in wanted
		$matsOnParts = array_map(function($matId) use ($materialsCache) {
			return $materialsCache[$matId];
		}, $partsAssociation);

		return $matsOnParts;
	}



	public function getCartList(){
		if($this->cartList === null){
			$this->loadFromSession();
		}
		return $this->cartList;
	}

	public function getMaterials(){
		if($this->cartList === null){
			$this->loadFromSession();
		}
		return $this->materials;
	}
}
