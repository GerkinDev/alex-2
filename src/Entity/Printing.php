<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrintingRepository")
 */
class Printing
{
	const WAITING_VALIDATION = 2;
	const WAITING_PROCESSING = 1;
	const DONE = 0;

	/**
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\OneToOne(targetEntity="App\Entity\Model")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $model;
	/**
	 * @ORM\Column(type="smallint")
	 */
	private $status;

	public function getId() {
		return $this->id;
	}

	public function getModel() {
		return $this->model;
	}
	public function setModel(Model $model) {
		$this->model = $model;
		return $this;
	}

	public function getStatus() {
		return $this->status;
	}
	public function setStatus($status) {
		$this->status = $status;
		return $this;
	}
}
