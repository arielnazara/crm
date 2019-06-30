<?php
namespace CRM\Domain;


class Entreprise extends GlobalDomain {

    private $id;
    private $raisonSocial;
    private $nomCommercial;
    private $siret;
    private $rue;
    private $addresse;
    private $codePostal;
    private $ville;
    private $pays;
    private $email;

    public function getId()
    {
        return $this->id;
    }
    public function getRaisonSocial(){
        return $this->raisonSocial;
    }
    public function getNomCommercial(){
        return $this->nomCommercial;
    }
    public function getSiret(){
        return $this->siret;
    }
    public function getRue(){
        return $this->rue;
    }
    public function getAddresse(){
        return $this->addresse;
    }
    public function getCodePostal(){
        return $this->codePostal;
    }
    public function getVille(){
        return $this->ville;
    }
    public function getPays(){
        return $this->pays;
    }
    public function getEmail(){
        return $this->email;
    }

    //set
    public function setId($id){
        $this->id=$id;
    }
    public function setRaisonSocial($raisonSocial){
        $this->raisonSocial=$raisonSocial;
    }
    public function setNomCommercial($nomCommercial){
        $this->nomCommercial=$nomCommercial;
    }
    public function setSiret($siret){
        $this->siret=$siret;
    }
    public function setRue($rue){
        $this->rue=$rue;
    }
    public function setAddresse($Addresse){
        $this->addresse=$Addresse;
    }
    public function setCodePostal($codePostal){
        $this->codePostal=$codePostal;
    }
    public function setVille($ville){
        $this->ville=$ville;
    }
    public function setPays($pays){
        $this->pays=$pays;
    }
    public function setEmail($email){
        $this->email=$email;
    }

}