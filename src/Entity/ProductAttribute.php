<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use App\Entity\AttributeCategory;

/**
* @ORM\Entity(repositoryClass="App\Repository\ProductAttributeRepository")
*/
class ProductAttribute
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
	* @ORM\Column(type="integer")
	*/
	private $factor;

	/**
	* @ORM\OneToOne(targetEntity="App\Entity\AttributeCategory")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $category;

	/**
	* @ORM\ManyToMany(targetEntity="App\Entity\Model", inversedBy="attributes")
	* @ORM\JoinColumn(nullable=true)
	*/
	private $models;

	public function __construct() {
		$this->models = new ArrayCollection();
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

	public function getFactor() {
		return $this->factor;
	}
	public function setFactor($factor) {
		$this->factor = $factor;

		return $this;
	}

	public function getCategory() {
		return $this->category;
	}
	public function setCategory(AttributeCategory $category) {
		$this->category = $category;

		return $this;
	}

	public function getModels() {
		return $this->models;
	}
	public function addModel(Model $model) {
		if ($this->models->contains($model)) {
			return;
		}

		$this->models->add($model);

		return $this;
	}
	public function setModels(array $models) {
		$this->models = new ArrayCollection($models);

		return $this;
	}
}
