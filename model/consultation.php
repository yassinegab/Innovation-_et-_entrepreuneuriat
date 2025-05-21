<?php
class Consultation {
    private $id_consultation;

    private $titre;

    private $description;

    private $date_consultation;
    private $type;
    private $statut;

    private $id_utilisateur;
 
    

    public function __construct($id_consultation, $titre, $description, $date_consultation, $type, $statut, $id_utilisateur) {
        $this->id_consultation = $id_consultation;
        $this->titre = $titre;
        $this->description = $description;
        $this->date_consultation = $date_consultation;
        $this->type = $type;
        $this->statut = $statut;
        $this->id_utilisateur = $id_utilisateur;
    }

    // Getters
    public function getid_consultation() {
        return $this->id_consultation;
    }
    public function gettitre() {
        return $this->titre;
    }
    public function getdescription() {
        return $this->description;
    }
    public function getDateConsultation() {
        return $this->date_consultation;
    }

    public function gettype() {
        return $this->type;
    }

    public function getstatut() {
        return $this->statut;
    }

  
    public function getid_utilisateur() {
        return $this->id_utilisateur;
    }



    // Setters
    public function setid_consultation($id_consultation) {
        $this->id_consultation = $id_consultation;
    }

    public function settitre($titre) {
        $this->titre = $titre;
    }
    public function setdescription($description) {
        $this->description = $description;
    }

 
    public function setDateConsultation($date_consultation) {
        $this->date_consultation = $date_consultation;
    }
    public function settype($type) {
        $this->type = $type;
    }
    public function set_statut($statut) {
        $this->statut = $statut;
    }
 
    public function setid_utilisateur($id_utilisateur) {
        $this->id_utilisateur = $id_utilisateur;
    }
 

}
?>
