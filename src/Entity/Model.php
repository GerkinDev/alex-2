<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Selectable;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

use App\Entity\User;
use App\Entity\ProductAttribute;
use App\Entity\VariableAttribute;

/**
* @ORM\Entity(repositoryClass="App\Repository\ModelRepository")
* @Vich\Uploadable
*/
class Model extends \App\GenericClass\BaseEntity
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
	private $title = '';

	/**
	* @Gedmo\Slug(fields={"title"})
	* @ORM\Column(type="string")
	*/
	private $slug = '';

	/**
	* @ORM\Column(type="boolean")
	*/
	private $public = true;

	/**
	* @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="models")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $user = null;

	/**
	* @ORM\OneToMany(targetEntity="App\Entity\ProductAttribute", mappedBy="model", orphanRemoval=true, cascade={"persist"})
	*/
	protected $attributes;

	/**
	* @ORM\Column(type="float")
	*/
	private $basePrice = 0;


	// ## Get/Set

	// ID
	public function getId(): ?int {
		return $this->id;
	}

	// Model
	public function setModelFile(File $model = null): self {
		$this->modelFile = $model;

		// VERY IMPORTANT:
		// It is required that at least one field changes if you are using Doctrine,
		// otherwise the event listeners won't be called and the file is lost
		if ($model) {
			// if 'updatedAt' is not defined in your entity, use another property
			$this->updatedAt = new \DateTime('now');
		}

		return $this;
	}
	public function getModelFile(): ?File {
		return $this->modelFile;
	}
	public function setModel(string $model): self {
		$this->model = $model;

		return $this;
	}
	public function getModel(): ?string {
		return $this->model;
	}

	// Image
	public function setImageFile(File $image = null): self {
		$this->imageFile = $image;

		// VERY IMPORTANT:
		// It is required that at least one field changes if you are using Doctrine,
		// otherwise the event listeners won't be called and the file is lost
		if ($image) {
			// if 'updatedAt' is not defined in your entity, use another property
			$this->updatedAt = new \DateTime('now');
		}

		return $this;
	}
	public function getImageFile(): ?File {
		return $this->imageFile;
	}
	public function setImage(string $image): self {
		$this->image = $image;

		return $this;
	}
	public function getImage(): ?string {
		return $this->image;
	}

	// Title
	public function getTitle(): string {
		return $this->title;
	}
	public function setTitle(string $title): self {
		$this->title = $title;

		return $this;
	}

	// Slug
	public function getSlug(): string {
		return $this->slug;
	}
	public function setSlug(string $slug = null): self {
		$this->slug = $slug;

		return $this;
	}

	// Public
	public function isPublic(): bool {
		return $this->public;
	}
	public function setPublic(bool $public): self {
		$this->public = $public;

		return $this;
	}

	// User
	public function getUser(): ?User {
		return $this->user;
	}
	public function setUser(User $user): self {
		$this->user = $user;

		return $this;
	}

	// Attributes
	public function getAttributes(): Selectable {
		return $this->attributes;
	}
	public function addAttribute(ProductAttribute $attribute): self {
		$this->addToCol('attributes', $attribute);
		$attribute->setModel($this);

		return $this;
	}
	public function removeAttribute(ProductAttribute $attribute): self {
		$this->attributes->removeElement($attribute);
		$attribute->setModel(null);

		return $this;
	}
	public function setAttributes(\ArrayAccess $attributes): self {
		$this->attributes = self::ensureArrayCollection($attributes);
		$this->attributes->map(function ($attribute) {
			return $attribute->setModel($this);
		});

		return $this;
	}

	// Base price
	public function getBasePrice(): float {
		return $this->basePrice;
	}
	public function setBasePrice(float $basePrice): self {
		$this->basePrice = $basePrice;

		return $this;
	}

	// ## Other
	public function __construct() {
		$this->updatedAt  = new \DateTime();
		$this->attributes = new ArrayCollection();
	}

	public function __toString(): string {
		return sprintf('%s (%s)', $this->getTitle(), $this->getId());
	}
}
