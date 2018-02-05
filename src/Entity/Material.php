<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\GenericClass\ICartAttribute;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MaterialRepository")
 */
class Material implements ICartAttribute, \JsonSerializable
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


	
	public function jsonSerialize()
	{
		return array(
			'id' => $this->id,
			'name'=> $this->name,
			'price'=> $this->price,
		);
	}
}
