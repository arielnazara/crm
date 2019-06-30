<?php
namespace CRM\Domain;

class Contact extends GlobalDomain {

	private $id;
	private $entreprise;
	private $civilite;
	private $nom;
	private $prenom;
	private $fonction;
	private $telephone;
	private $email;
	private $isDefault = false;

	public function getId(){
		return $this->id;
	}
	public function getEntreprise(){
		return $this->entreprise;
	}
	public function getCivilite(){
		return $this->civilite;
	}
	public function getNom(){
		return $this->nom;
	}
	public function getPrenom(){
		return $this->prenom;
	}
	public function getFonction(){
		return $this->fonction;
	}
	public function getTelephone(){
		return $this->telephone;
	}
	public function getEmail(){
		return $this->email;
	}
	public function getIsDefault(){
		return $this->isDefault;
	}

	public function setId($id){
		$this->id=$id;
	}
	public function setEntreprise(Entreprise $entreprise){
		$this->entreprise =$entreprise;
	}
	public function setCivilite($civilite){
		$this->civilite=$civilite;
	}
	public function setNom($nom){
		$this->nom=$nom;
	}
	public function setPrenom($prenom){
		$this->prenom=$prenom;
	}
	public function setfonction($fonction){
		$this->fonction=$fonction;
	}
	public function setTelephone($telephone){
		$this->telephone=$telephone;
	}
	public function setEmail($email){
		$this->email=$email;
	}
	public function setIsDefault($isDefault){
		$this->isDefault=$isDefault;
	}
}