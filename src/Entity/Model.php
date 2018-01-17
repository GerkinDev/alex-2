<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
 */
class Model
{
	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	// add your own fields
	/**
	 * @ORM\Column(type="string", nullable=false)
	 */
	private $file;
	/**
	 * @ORM\Column(type="string")
	 */
	private $title;
	/**
   * @Gedmo\Slug(fields={"title"})
	 * @ORM\Column(type="string")
	 */
	private $slug;
	/**
	 * @ORM\Column(type="boolean")
	 */
	private $public;
	/**
	 * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="models")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $creator;
	/**
	 * @ORM\Column(type="float")
	 */
	private $price;

	public function getTitle(){
		return $this->title;
	}
}
