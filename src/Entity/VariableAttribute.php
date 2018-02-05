<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

use App\Entity\VariableAttributeCategory;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VariableAttributeRepository")
 */
class VariableAttribute extends \App\GenericClass\BaseEntity
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="float")
	 */
	private $price;

	/**
	 * @ORM\Column(type="boolean")
	 */
	private $available;

	/**
	 * @ORM\Column(type="string")
	 */
	private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\VariableAttributeCategory", inversedBy="attributes", cascade={"persist"})
	 */
	private $category;


	// ## Get / Set

	// ID
	public function getId(): int {
		return $this->id;
	}

	// Price
	public function getPrice(): float {
		return $this->price;
	}
	public function setPrice(float $price): self {
		$this->price = $price;

		return $this;
	}

	// Available
	public function isAvailable(): bool {
		return $this->available;
	}
	public function setAvailable(bool $available): self {
		$this->available = $available;

		return $this;
	}

	public function getName(): string {
		return $this->name;
	}
	public function setName(string $name): self {
		$this->name = $name;

		return $this;
	}

	public function getCategory() {
		return $this->category;
	}
	public function setCategory(VariableAttributeCategory $category = null): self {
		$this->category = $category;

		return $this;
	}


	// ## Other
	public function __construct() {
	}
	public function __toString() {
		return sprintf('%s (%s, %s€/unit)', $this->getName(), $this->getId(), $this->getPrice());
	}
}
