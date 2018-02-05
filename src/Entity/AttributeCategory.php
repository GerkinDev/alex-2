<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\Attribute;

/**
* @ORM\Entity(repositoryClass="App\Repository\AttributeCategoryRepository")
*/
class AttributeCategory
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
	* @ORM\OneToMany(targetEntity="App\Entity\Attribute", mappedBy="category")
	*/
	private $attributes;


	public function __construct() {
		$this->attributes = new ArrayCollection();
	}

	public function getId() {
		return $this->id;
	}

	public function getName() {
		return $this->name;
	}

	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	public function getAttributes() {
		return $this->attributes;
	}
	public function addAttribute(Attribute $attribute) {
		$this->attributes->add($attribute);

		return $this;
	}
	public function setAttributes(array $attributes) {
		$this->attributes = new ArrayCollection($attributes);

		return $this;
	}
}
