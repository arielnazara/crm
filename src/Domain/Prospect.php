<?php

namespace CRM\Domain;

class Prospect extends GlobalDomain {

	private $id;
	private $date;
	private $secteurActivite;
    private $contact;
    private $user;
    private $site;
	private $isLooked;

    public function __construct(array $data = []) {
        $this->date = new \DateTime();
        parent::__construct($data);
    }

	public function getId(){
		return $this->id;
	}
	public function getContact(){
		return $this->contact;
	}
	public function getUser(){
		return $this->user;
	}
	public function getSite(){
		return $this->site;
	}
	public function getDate(){
		return $this->date;
	}
	public function getSecteurActivite(){
		return $this->secteurActivite;
	}
	public function getIsLooked(){
		return $this->isLooked;
	}

	public function setId($id){
		$this->id=$id;
	}
	public function setContact(Contact $contact){
		$this->contact=$contact;
	}
	public function setUser(User $user){
		$this->user=$user;
	}
	public function setSite(Site $site){
		$this->site=$site;
	}
	public function setDate(\DateTime $date){
		$this->date = $date;
	}
	public function setSecteurActivite($secteurActivite){
		$this->secteurActivite = $secteurActivite;
	}
	public function setIsLooked($isLooked){
		$this->isLooked=$isLooked;
	}


}