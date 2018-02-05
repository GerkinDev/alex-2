<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
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
	* Get the value of id
	*/
	public function getId() {
		return $this->id;
	}

	/**
	* Get the value of name
	*/
	public function getName() {
		return $this->name;
	}

	/**
	* Set the value of name
	*
	* @return  self
	*/
	public function setName($name) {
		$this->name = $name;

		return $this;
	}

	/**
	* Get the value of factor
	*/
	public function getFactor() {
		return $this->factor;
	}

	/**
	* Set the value of factor
	*
	* @return  self
	*/
	public function setFactor($factor) {
		$this->factor = $factor;

		return $this;
	}

	/**
	 * Get the category
	 */
	public function getCategory() {
		return $this->category;
	}

	/**
	 * Set the category
	 *
	 * @return  self
	 */
	public function setCategory(AttributeCategory $category) {
		$this->category = $category;

		return $this;
	}
}
