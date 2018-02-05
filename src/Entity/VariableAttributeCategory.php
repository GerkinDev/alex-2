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
	* @ORM\OneToMany(targetEntity="App\Entity\VariableAttribute", mappedBy="category", cascade={"persist"})
	*/
	protected $attributes;

	// ## Get / Set

	// ID
	public function getId(): int {
		return $this->id;
	}

	public function getName(): string {
		return $this->name;
	}
	public function setName(string $name): self {
		$this->name = $name;

		return $this;
	}

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
