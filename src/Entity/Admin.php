<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\Admin\UserInterface;

/**
* @ORM\Entity(repositoryClass="App\Repository\UserRepository")
*/
class Admin implements UserInterface, \Serializable
{
	/**
	* @ORM\Column(type="integer")
	* @ORM\Id
	* @ORM\GeneratedValue(strategy="AUTO")
	*/
	private $id;

	/**
	* @ORM\Column(type="string")
	*/
	private $fname;

	/**
	* @ORM\Column(type="string")
	*/
	private $lname;

	/**
	* @ORM\Column(type="string", length=64)
	*/
	private $password;

	/**
	* @ORM\Column(type="string", length=60, unique=true)
	* @Assert\Email(
	*     message = "The email '{{ value }}' is not a valid email.",
	*     checkMX = true
	* )
	*/
	private $email;

	/**
	* @ORM\Column(type="array")
	*/
	private $roles;

	/**
	* @ORM\Column(name="is_active", type="boolean")
	*/
	private $isActive;

	public function __construct($username = null, $password = null, $salt = null, $roles = [ 'ROLE_SUPER_ADMIN' ]) {
		$this->isActive = true;
		$this->username = $username;
		$this->password = $password;
		$this->salt = $salt;
		$this->roles = $roles;
		// may not be needed, see section on salt below
		// $this->salt = md5(uniqid('', true));
	}

	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}


	public function getFirstName() {
		return $this->fname;
	}
	public function setFirstName($fname) {
		$this->fname = $fname;
		return $this;
	}

	public function getLastName() {
		return $this->lname;
	}
	public function setLastName($lname) {
		$this->lname = $lname;
		return $this;
	}

	public function getSalt() {
		// you *may* need a real salt depending on your encoder
		// see section on salt below
		return null;
	}

	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
		return $this;
	}
	public function setRawPassword($rawPassword, $encoder) {
		$this->setPassword($encoder->encodePassword($this, $rawPassword));
		return $this;
	}

	public function getRoles() {
		return $this->roles;
	}

	public function getUsername() {
		return $this->getEmail();
	}

	public function eraseCredentials() {
	}

	/** @see \Serializable::serialize() */
	public function serialize() {
		return serialize(array(
			$this->id,
			$this->email,
			$this->password,
			// see section on salt below
			// $this->salt,
		));
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			$this->email,
			$this->password,
			// see section on salt below
			// $this->salt
		) = unserialize($serialized);
	}
}
