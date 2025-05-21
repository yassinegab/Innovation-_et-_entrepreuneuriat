<?php
class Reponse {
    private $id_reponse;
    private $contenu;
    private $date_reponse;
    private $id_consultation;
    private $id_utilisateur; // Celui qui a répondu (consultant par exemple)

    public function __construct($id_reponse, $contenu, $date_reponse, $id_consultation, $id_utilisateur) {
        $this->id_reponse = $id_reponse;
        $this->contenu = $contenu;
        $this->date_reponse = $date_reponse;
        $this->id_consultation = $id_consultation;
        $this->id_utilisateur = $id_utilisateur;
    }

    // Getters
    public function getid_reponse() {
        return $this->id_reponse;
    }

    public function getcontenu() {
        return $this->contenu;
    }

    public function getdate_reponse() {
        return $this->date_reponse;
    }

    public function getid_consultation() {
        return $this->id_consultation;
    }

    public function getid_utilisateur() {
        return $this->id_utilisateur;
    }

    // Setters
    public function setid_reponse($id_reponse) {
        $this->id_reponse = $id_reponse;
    }

    public function setcontenu($contenu) {
        $this->contenu = $contenu;
    }

    public function setdate_reponse($date_reponse) {
        $this->date_reponse = $date_reponse;
    }

    public function setid_consultation($id_consultation) {
        $this->id_consultation = $id_consultation;
    }

    public function setid_utilisateur($id_utilisateur) {
        $this->id_utilisateur = $id_utilisateur;
    }
}
?>
