<?php
namespace CRM\Domain;

class Consigne extends GlobalDomain {

    private $id;
    private $prospect;
    private $date;
    private $commentaire;

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
    public function getCommentaire(){
        return $this->commentaire;
    }

    public function setId($id)
    {
       $this->id=$id;
    }

    public function setProspect(Prospect $prospect)
    {
        $this->prospect = $prospect;
    }

    public function setDate(\DateTime $date)
    {
        $this->date=$date;
    }

    public function init() {
	return $this;
    }

    public function setCommentaire($commentaire)
    {
        $this->commentaire=$commentaire;
    }
}
