<?php
namespace CRM\Domain;

use CRM\Service\Hydrator;
use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface {
    use Hydrator;

	private $id;
	private $site;
	private $nom;
	private $prenom;
	private $username;
	private $password;
	private $salt;
	private $role;
	private $email;

    public function __construct(array $data = []) {
        if ( !empty($data) ) {
            $this->hydrate($data);
        }
    }

	public function getId(){
		return $this->id;
	}
	public function setId($id){
		$this->id=$id;
	}
	public function getSite(){
		return $this->site;
	}
	public function setSite(Site $site){
		$this->site = $site;
	}
	public function getNom(){
		return $this->nom;
	}
	public function setNom($nom){
		$this->nom=$nom;
	}
	public function getPrenom(){
		return $this->prenom;
	}
	public function setPrenom($prenom){
		$this->prenom=$prenom;
	}
	public function getUsername(){
		return $this->username;
	}
	public function setUsername($username){
		$this->username=$username;
        return $this;
	}
	public function getPassword(){
		return $this->password;
	}
	public function setPassword($password){
		$this->password=$password;
        return $this;
	}
	public function getSalt(){
		return $this->salt;
	}
	public function setSalt($salt){
		$this->salt=$salt;
        return $this;
	}
	public function getRole(){
		return $this->role;
	}
	public function setRole($role){
		$this->role=$role;
        return $this;
	}
	public function getEmail(){
		return $this->email;
	}
	public function setEmail($email){
		$this->email=$email;
	}

    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}