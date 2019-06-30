<?php
namespace CRM\Domain;

class Tache extends GlobalDomain {
	
	private $id;
	private $prospect;
	private $date;
	private $dateRappel;
	private $dateFin;
	private $objet;
	private $description;
	private $user;
	private $isComplete = false;

    public function __construct(array $data = []) {
        $this->date = new \DateTime();
        parent::__construct($data);
    }

	public function getId(){
		return $this->id;
	}
	public function getProspect(){
		return $this->prospect;
	}
	public function getDate(){
		return $this->date;
	}
	public function getDateRappel(){
		return $this->dateRappel;
	}
	public function getDateFin(){
		return $this->dateFin;
	}
	public function getObjet(){
		return $this->objet;
	}
	public function getDescription(){
		return $this->description;
	}
	public function getIsComplete(){
		return $this->isComplete;
	}

	public function getUser() {
        return $this->user;
    }

	public function setId($id){
		$this->id=$id;
	}
	public function setProspect(Prospect $prospect){
		$this->prospect=$prospect;
	}
	public function setDate(\DateTime $date){
		$this->date=$date;
	}
	public function setDateRappel(\DateTime $dateRappel){
		$this->dateRappel=$dateRappel;
	}
	public function setDateFin(\DateTime $dateFin){
		$this->dateFin=$dateFin;
	}
	public function setObjet($objet){
		$this->objet=$objet;
	}
	public function setDescription($description){
		$this->description=$description;
	}
	public function setIsComplete($isComplete){
		$this->isComplete=$isComplete;
	}
	public function setUser(User $user) {
        $this->setUser($user);
    }



}
