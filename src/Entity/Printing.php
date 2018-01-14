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

    // add your own fields
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Model")
     * @ORM\JoinColumn(nullable=false)
     */
	private $model;
    /**
     * @ORM\Column(type="smallint")
     */
	private $status;
}
