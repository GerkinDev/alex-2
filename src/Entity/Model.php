<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
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
class Model implements ICartItem
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
	 * @ORM\Column(type="string")
	 */
	private $masses;


	public function __construct(){
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

	public function getMasses($decoded = false) {
		if($decoded){
			return json_decode($this->masses, true);
		} else {
			return $this->masses;
		}
	}
	public function setMasses($masses) {
		$this->masses = $masses;
		return $this;
	}
	public function getAttrsFactors(){
		return $this->getMasses(true);
	}

	public function computeModelInfos(UploaderHelper $helper, $materials = null){
		$sum = 0;
		$modelInfos = [
			'entity' => $this,
			'price' => false,
			'image' => $helper->asset($this, 'imageFile'),
			'file' => $helper->asset($this, 'modelFile'),
		];
		if(!$modelInfos['image']){
			$modelInfos['image'] = '/assets/images/no-image.jpg';
		}
		foreach($this->getMasses(true) as $piece => $mass){
			if($materials instanceof Material){
				$sum += $mass * $materials->getPrice();
			} else if(is_array($materials)){
				if(!isset($materials[$piece])){
					return $modelInfos;
				}
				$material = $materials[$piece];
				$sum += $mass * $material->getPrice();
			}
		}
		$modelInfos['price'] = $sum;
		return $modelInfos;
	}




	/** @see \Serializable::serialize() */
	/*public function serialize() {
		return serialize(array(
			$this->id,
			$this->model,
			$this->image,
			$this->updatedAt,
			$this->title,
			$this->slug,
			$this->public,
			$this->creator,
			$this->mass,
		));
	}*/

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			$this->model,
			$this->image,
			$this->updatedAt,
			$this->title,
			$this->slug,
			$this->public,
			$this->creator,
			$this->masses,
		) = unserialize($serialized);
	}
}
