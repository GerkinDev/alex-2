<?php

namespace App\Entity;

use FOSUserBundleModelUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User extends BaseUser
{
	/**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
	protected $id;

	// add your own fields


	public function __construct()
	{
		parent::__construct();
	}
}
