<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Selectable;

use App\Entity\VariableAttribute;

/**
* @ORM\Entity(repositoryClass="App\Repository\VariableAttributeCategoryRepository")
*/
class VariableAttributeCategory extends \App\GenericClass\BaseEntity
{
	/**
	* @ORM\Id
	* @ORM\GeneratedValue
	* @ORM\Column(type="integer")
	*/
	private $id;

	/**
	* @ORM\Column(type="string")
	*/
	private $name;

	/**
	* @ORM\OneToMany(targetEntity="App\Entity\ProductAttribute", mappedBy="category", cascade={"persist"})
	*/
	protected $productAttributes;

	/**
	* @ORM\OneToMany(targetEntity="App\Entity\VariableAttribute", mappedBy="category", cascade={"persist"})
	*/
	protected $attributes;

	// ## Get / Set

	// ID
	public function getId(): int {
		return $this->id;
	}

	// Name
	public function getName(): string {
		return $this->name;
	}
	public function setName(string $name): self {
		$this->name = $name;

		return $this;
	}

	// Product attributes
	public function getProductAttributes(): Selectable {
		return $this->productAttributes;
	}
	public function addProductAttribute(ProductAttribute $productAttribute) {
		$this->addToCol('productAttributes', $productAttribute);
		$productAttribute->setCategory($this);

		return $this;
	}
	public function removeProductAttribute(ProductAttribute $productAttribute): self {
		$this->productAttributes->removeElement($productAttribute);
		$productAttribute->setCategory(null);

		return $this;
	}
	public function setProductAttributes(\ArrayAccess $productAttributes): self {
		$this->productAttributes = self::ensureArrayCollection($productAttributes);
		$this->productAttributes->map(function($productAttribute){
			return $productAttribute->setCategory($this);
		});

		return $this;
	}

	// Attributes
	public function getAttributes(): Selectable {
		return $this->attributes;
	}
	public function addAttribute(VariableAttribute $attribute) {
		$this->addToCol('attributes', $attribute);
		$attribute->setCategory($this);

		return $this;
	}
	public function removeAttribute(VariableAttribute $attribute): self {
		$this->attributes->removeElement($attribute);
		$attribute->setCategory(null);

		return $this;
	}
	public function setAttributes(\ArrayAccess $attributes): self {
		$this->attributes = self::ensureArrayCollection($attributes);
		$this->attributes->map(function($attribute){
			return $attribute->setCategory($this);
		});

		return $this;
	}

	// ## Other
	public function __construct() {
		$this->attributes = new ArrayCollection();
	}

	public function __toString() {
		return sprintf('%s (%s)', $this->getName(), $this->getId());
	}
}
