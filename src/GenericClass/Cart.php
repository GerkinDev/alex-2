<?php

namespace App\GenericClass;

use Symfony\Component\HttpFoundation\Session\Session;
use Doctrine\ORM\EntityManagerInterface;
use \Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use App\Service\Debug;

use App\Entity\Cart as CartEntity;

use App\Entity\Model;
use App\Entity\Material;

class Cart {
	public const PRODUCT_KEY = 'product';
	public const ATTRS_KEY = 'attrs';
	public const ATTRS_FACTOR_KEY = 'factors';
	public const COUNT_KEY = 'count';

	private $findItemFct;
	private $findMaterialFct;
	private $filterInfos;

	private $cart = null;
	private $attrs = null;

	public function __construct(callable $findItemFct, callable $findMaterialFct, callable $filterInfos = null){
		$this->findItemFct = $findItemFct;
		$this->findMaterialFct = $findMaterialFct;
		$this->filterInfos = $filterInfos;
	}

	public function deserialize(array $cartData){
		// Get all product ids
		$productIds = array_unique(array_map('intval', array_map(function($cartItem) {
			return $cartItem[self::PRODUCT_KEY];
		}, $cartData)));

		// Get products in [id => product]
		$products = $this->arrayColToKeyed(call_user_func($this->findItemFct, $productIds));

		// Get all attr ids
		$attrIds = [];
		foreach($cartData as $cartItem){
			$attrsWanted = $cartItem[self::ATTRS_KEY];
			foreach($attrsWanted as $attr){
				$attrIds[] = $attr;
			}
		}
		$attrIds = array_unique($attrIds);

		// Get attrs in [id => attr]
		$attrs = $this->arrayColToKeyed(call_user_func($this->findMaterialFct, $attrIds));

		$this->cart = array_map(function($cartItem) use ($products, $attrs) {
			$product = $products[$cartItem[self::PRODUCT_KEY]];

			$infos = isset($this->filterInfos) ? $this->filterInfos($product) : [];

			$attrsWanted = $cartItem[self::ATTRS_KEY];
			$infos[self::ATTRS_KEY] = array_map(function($attr) use ($attrs){
				return $attrs[$attr];
			}, $attrsWanted);
			$infos[self::PRODUCT_KEY] = $product;
			$infos[self::COUNT_KEY] = $cartItem[self::COUNT_KEY];
			$infos[self::ATTRS_FACTOR_KEY] = $products[$cartItem[self::PRODUCT_KEY]]->getAttrsFactors();
			return $infos;
		}, $cartData);

		return $this;
	}

	public function addItem($product, array $attrs, $count = 1){
		if($this->cart === null){
			throw new \Exception('Call '.__NAMESPACE__.'::loadFromData before');
		}
		if($product === null || !method_exists($product, 'getId') || !method_exists($product, 'getAttrsFactors')){
			throw new \Exception('First argument must be a product with "getId()" & "getAttrsFactors()" methods');
		}


		// Check attr keys
		$partsFactor = $product->getAttrsFactors();
		$partsNames = array_keys($partsFactor);
		$attrsKeys = array_keys($attrs);

		$missingProps = array_diff($partsNames, $attrsKeys);
		if(count($missingProps) > 0){
			throw new \Exception('Missing parts attrs for '.implode(', ', $missingProps));
		}

		$excessingProps = array_diff($attrsKeys, $partsNames);
		if(count($excessingProps) > 0){
			throw new \Exception('Excessing parts attrs for '.implode(', ', $excessingProps));
		}

		// Get missing attrs (with ID)
		$attrsToRetrieve = array_unique(array_reduce($attrs, function($acc, $attr){
			if(is_numeric($attr)){
				$acc[] = $attr;
			}
			return $acc;
		}, []));
		if(count($attrsToRetrieve) > 0){
			$attrsRetrieved = $this->arrayColToKeyed(call_user_func($this->findMaterialFct, $attrsToRetrieve));
			$attrs = array_map(function($attr) use ($attrsRetrieved){
				if(is_numeric($attr)){
					return $attrsRetrieved[$attr];
				} else {
					return $attr;
				}
			}, $attrs);
		}

		foreach($this->cart as &$cartItem){
			if($cartItem[self::PRODUCT_KEY] === $product){
				if($this->attributesEqual($cartItem[self::ATTRS_KEY], $attrs) === true){
					$cartItem[self::COUNT_KEY] += $count;
					return;
				}
			}
		}

		// Compose cart item
		$infos = isset($this->filterInfos) ? $this->filterInfos($product) : [];
		$infos[self::ATTRS_KEY] = $attrs;
		$infos[self::PRODUCT_KEY] = $product;
		$infos[self::COUNT_KEY] = $count;
		$infos[self::ATTRS_FACTOR_KEY] = $product->getAttrsFactors();

		$this->cart[] = $infos;
	}

	public function removeItem($product, array $attrs = null){
		if($this->cart === null){
			throw new \Exception('Call '.__NAMESPACE__.'::loadFromData before');
		}
		if($product !== true && ($product === null || !method_exists($product, 'getId') || !method_exists($product, 'getAttrsFactors'))){
			throw new \Exception('First argument must be a product with "getId()" & "getAttrsFactors()" methods');
		}

		// Handle purge
		if($product === true){
			$this->cart = [];
		}
		if($attrs === null){
			$this->cart = array_values(array_filter($this->cart, function($cartItem) use ($product){
				return $cartItem[self::PRODUCT_KEY]->getId() !== $product->getId();
			}));
		} else {
			$this->cart = array_values(array_filter($this->cart, function($cartItem) use ($product, $attrs){
				return ($cartItem[self::PRODUCT_KEY]->getId() !== $product->getId()) ||
				($this->attributesEqual($cartItem[self::ATTRS_KEY], $attrs) === false);
			}));
		}

		return $this;
	}

	public function attributesEqual($a, $b){
		foreach($a as $part => $mat){
			$comparison = $b[$part] === $mat;
			if($comparison === false){
				return false;
			}
		}
		return true;
	}


	public function getCart($datas = null){
		if($datas !== null){
			$this->deserialize($datas);
		}
		return $this->cart;
	}

	public function serialize(){
		return array_map(function($cartItem){
			return [
				self::COUNT_KEY => $cartItem[self::COUNT_KEY],
				self::PRODUCT_KEY => $cartItem[self::PRODUCT_KEY]->getId(),
				self::ATTRS_KEY => array_map(function($attr){
					return $attr->getId();
				}, $cartItem[self::ATTRS_KEY]),
			];
		}, $this->cart);
	}

	private function arrayColToKeyed($col){
		return array_reduce($col, function($acc, $item) {
			$acc[$item->getId()] = $item;
			return $acc;
		}, []);
	}
}
