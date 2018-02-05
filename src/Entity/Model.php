<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use App\Entity\Material;
use App\GenericClass\ICartItem;

/**
* @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
* @Vich\Uploadable
*/
class Model
{
	/**
	* @ORM\Id
	* @ORM\GeneratedValue
	* @ORM\Column(type="integer")
	*/
	private $id;

	/**
	* @ORM\Column(type="string", length=255, nullable=true)
	* @var string
	*/
	private $model;
	/**
	* @Vich\UploadableField(mapping="models_files", fileNameProperty="model")
	* @var File
	*/
	private $modelFile;

	/**
	* @ORM\Column(type="string", length=255, nullable=true)
	* @var string
	*/
	private $image;
	/**
	* @Vich\UploadableField(mapping="models_images", fileNameProperty="image")
	* @var File
	*/
	private $imageFile;

	/**
	* @ORM\Column(type="datetime")
	* @var \DateTime
	*/
	private $updatedAt;

	/**
	* @ORM\Column(type="string")
	* @Gedmo\Translatable
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
	* @ORM\ManyToMany(targetEntity="App\Entity\ProductAttribute", mappedBy="models")
	*/
	private $attributes;


	public function __construct() {
		$this->updatedAt = new \DateTime();
	}


	public function getId() {
		return $this->id;
	}

	public function setModelFile(File $model = null) {
		$this->modelFile = $model;

		// VERY IMPORTANT:
		// It is required that at least one field changes if you are using Doctrine,
		// otherwise the event listeners won't be called and the file is lost
		if ($model) {
			// if 'updatedAt' is not defined in your entity, use another property
			$this->updatedAt = new \DateTime('now');
		}
	}
	public function getModelFile() {
		return $this->modelFile;
	}
	public function setModel($model) {
		$this->model = $model;
	}
	public function getModel() {
		return $this->model;
	}

	public function setImageFile(File $image = null) {
		$this->imageFile = $image;

		// VERY IMPORTANT:
		// It is required that at least one field changes if you are using Doctrine,
		// otherwise the event listeners won't be called and the file is lost
		if ($image) {
			// if 'updatedAt' is not defined in your entity, use another property
			$this->updatedAt = new \DateTime('now');
		}
	}
	public function getImageFile() {
		return $this->imageFile;
	}
	public function setImage($image) {
		$this->image = $image;
	}
	public function getImage() {
		return $this->image;
	}

	public function getTitle() {
		return $this->title;
	}
	public function setTitle($title) {
		$this->title = $title;
		return $this;
	}

	public function getSlug() {
		return $this->slug;
	}
	public function setSlug($slug) {
		$this->slug = $slug;
		return $this;
	}

	public function isPublic() {
		return $this->public;
	}
	public function setPublic($public) {
		$this->public = $public;
		return $this;
	}

	public function getCreator() {
		return $this->creator;
	}
	public function setCreator($creator) {
		$this->creator = $creator;
		return $this;
	}

	public function getAttributes() {
		return $this->attributes;
	}
	public function addAttribute(ProductAttribute $attribute) {
        if ($this->attributes->contains($attribute)) {
            return;
		}

		$this->attributes->add($attribute);

		return $this;
	}
	public function setAttributes(array $attributes) {
		$this->attributes = new ArrayCollection($attributes);

		return $this;
	}
}
