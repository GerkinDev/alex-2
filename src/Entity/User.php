<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;

/**
* @ORM\Entity(repositoryClass="App\Repository\UserRepository")
*/
class User implements UserInterface, \Serializable
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
	* @ORM\Column(type="string")
	*/
	private $salt;

	/**
	* @ORM\Column(type="string", length=60, unique=true)
	* @Assert\Email(
	*     message = "The email '{{ value }}' is not a valid email.",
	*     checkMX = true
	* )
	*/
	private $email;

	/**
	* @ORM\Column(type="string")
	*/
	private $role;

	/**
	* @ORM\Column(name="is_active", type="boolean")
	*/
	private $isActive;

	public function __construct($username = null, $password = null, $salt = null, $role = 'ROLE_USER' ) {
		$this->isActive = true;
		$this->username = $username;
		$this->password = $password;
		$this->salt = $salt;
		$this->role = $role;
		$this->salt = md5(uniqid('', true));
	}
	
	// Id
	public function getId(){
		return $this->id;
	}

	// FirstName
	public function getFirstName() {
		return $this->fname;
	}
	public function setFirstName($fname) {
		$this->fname = $fname;
		return $this;
	}

	// LastName
	public function getLastName() {
		return $this->lname;
	}
	public function setLastName($lname) {
		$this->lname = $lname;
		return $this;
	}

	// Password
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
	
	// Salt
	public function getSalt() {
		return $this->salt;
	}
	public function setSalt($salt) {
		$this->salt = $salt;
		return $this;
	}

	// Email
	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
		return $this;
	}

	public function getRole() {
		return $this->role;
	}
	public function getRoles() {
		return [$this->role];
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
			$this->salt,
		));
	}

	/** @see \Serializable::unserialize() */
	public function unserialize($serialized) {
		list (
			$this->id,
			$this->email,
			$this->password,
			$this->salt
		) = unserialize($serialized);
	}
}
