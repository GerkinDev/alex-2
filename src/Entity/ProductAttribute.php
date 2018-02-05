<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Selectable;

use App\Entity\VariableAttributeCategory;

/**
* @ORM\Entity(repositoryClass="App\Repository\ProductAttributeRepository")
*/
class ProductAttribute extends \App\GenericClass\BaseEntity
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
	private $name = '';

	/**
	* @ORM\Column(type="float")
	*/
	private $factor = 0;

	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\VariableAttributeCategory", inversedBy="productAttributes", cascade={"persist"})
	*/
	private $category;

	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\Model", inversedBy="attributes", cascade={"persist"})
	*/
	private $model;

	// ## Get / Set

	// ID
	public function getId(): ?int {
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

	// Factor
	public function getFactor(): float {
		return $this->factor;
	}
	public function setFactor(float $factor): self {
		$this->factor = $factor;

		return $this;
	}

	// Category
	public function getCategory(): ?VariableAttributeCategory {
		return $this->category;
	}
	public function setCategory(VariableAttributeCategory $category = null): self {
		$this->category = $category;

		return $this;
	}

	// Model
	public function getModel(): ?Model {
		return $this->model;
	}
	public function setModel(Model $model): self {
		$this->model = $model;

		return $this;
	}

	// ## Other
	public function __toString() {
		return sprintf('%s (%s, %.3f units)', $this->getName(), $this->getId(), $this->getFactor());
	}
}
