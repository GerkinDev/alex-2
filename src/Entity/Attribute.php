<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttributeRepository")
 */
class Attribute
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
     * @ORM\ManyToOne(targetEntity="App\Entity\AttributeCategory", inversedBy="attributes")
     * @ORM\JoinColumn(nullable=true)
     */
	private $category;



	public function getId() {
		return $this->id;
	}

	public function getPrice() {
		return $this->price;
	}
	public function setPrice($price) {
		$this->price = $price;
		return $this;
	}

	public function isAvailable() {
		return $this->available;
	}
	public function setAvailable($available) {
		$this->available = $available;
		return $this;
	}

	public function getName() {
		return $this->name;
	}
	public function setName($name) {
		$this->name = $name;
		return $this;
	}

	public function getCategory()
	{
		return $this->category;
	}
	public function setCategory($category)
	{
		$this->category = $category;

		return $this;
	}


	public function jsonSerialize() {
		return array(
			'id' => $this->id,
			'name'=> $this->name,
			'price'=> $this->price,
		);
	}
}
