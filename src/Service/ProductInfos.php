<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use \Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Doctrine\Common\Collections\Selectable;

use App\GenericClass\Collection;

use App\Entity\Model;
use App\Entity\VariableAttribute;

class ProductInfos {
	private $em;
	private $uploaderHelper;

	public function __construct(EntityManagerInterface $em, UploaderHelper $uploaderHelper){
		$this->em = $em;
		$this->uploaderHelper = $uploaderHelper;
	}

	public function getProductPresentationInfos(Model $product){
		$categories = Collection::fromIterable($product->getAttributes()->map(function($attribute){
			return $attribute->getCategory();
		}));
		$attrsRepository = $this->em->getRepository(VariableAttribute::class);
		$cheapestOfCategories = $categories->reduce(function($acc, $category, $index) use (&$attrsRepository){
			$acc->set($category->getId(), $attrsRepository->findCheapest($category));
			return $acc;
		}, new Collection());

		return $this->getModelInfos($product, $cheapestOfCategories);
	}

	public function getProductsPresentationInfos(Collection $productsCol){
		// Get all categories (without duplicate)
		$categories = $productsCol->reduce(function($acc, $model){
			$attributes = $model->getAttributes();
			if($attributes){
				$attributeCategories = $attributes->map(function($attribute){
					return $attribute->getCategory();
				});
				return $acc->addMultiple($attributeCategories->toArray(), true);
			} else {
				return $acc;
			}
		}, new Collection());

		$attrsRepository = $this->em->getRepository(VariableAttribute::class);
		$cheapestOfCategories = $categories->reduce(function($acc, $category, $index) use (&$attrsRepository){
			$acc->set($category->getId(), $attrsRepository->findCheapest($category));
			return $acc;
		}, new Collection());

		$helper = $this->uploaderHelper;
		return $productsCol->map(function($product) use (&$cheapestOfCategories){
			return $this->getModelInfos($product, $cheapestOfCategories);
		});
	}

	public function getPossibleAttributeForProduct(Model $product){
		$categories = Collection::fromIterable($product->getAttributes()->map(function($attribute){
			return $attribute->getCategory();
		}));
		$attrsRepository = $this->em->getRepository(VariableAttribute::class);
		return $categories->reduce(function($acc, $category, $index) use (&$attrsRepository){
			$acc->set($category->getId(), $attrsRepository->findByCategory($category));
			return $acc;
		}, new Collection());
	}

	private function getModelInfos(Model $product, Collection $attrsChosen) {
		// Defaults
		$sum = 0;
		$modelInfos = [
			'entity' => $product,
			'price' => false,
			'image' => $this->uploaderHelper->asset($product, 'imageFile'),
			'file' => $this->uploaderHelper->asset($product, 'modelFile'),
		];
		$modelInfos['image'] = $modelInfos['image'] ?: '/assets/images/no-image.jpg';

		foreach ($product->getAttributes() as $attribute) {
			$categoryId = $attribute->getCategory()->getId();
			if(!isset($attrsChosen[$categoryId])){
				throw new \Exception(sprintf('Missing attribute "%s" on product "%s" (%d)', $attribute->getName(), $product->getTitle(), $product->getId()));
			}

			// Get attribute, then add it to the price
			$attrChosen = $attrsChosen[$categoryId];
			$sum += $attrChosen->getPrice() * $attribute->getFactor();
		}
		$sum += $product->getBasePrice();
		$modelInfos['price'] = round($sum, 2);
		return $modelInfos;
	}
}
