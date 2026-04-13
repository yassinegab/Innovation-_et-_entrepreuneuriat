<?php

class Proposition {
    private $idProposition;
    private $titre;
    private $description;
    private $type;
    private $dateSoumission;
    private $statut;
    private $idUtilisateur;

    // 🔨 Constructeur
    public function __construct($idProposition, $titre, $description, $type, $dateSoumission, $statut, $idUtilisateur) {
        $this->idProposition = $idProposition;
        $this->titre = $titre;
        $this->description = $description;
        $this->type = $type;
        $this->dateSoumission = $dateSoumission;
        $this->statut = $statut;
        $this->idUtilisateur = $idUtilisateur;
    }

    // ✅ Getters
    public function getIdProposition() {
        return $this->idProposition;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getType() {
        return $this->type;
    }

    public function getDateSoumission() {
        return $this->dateSoumission;
    }

    public function getStatut() {
        return $this->statut;
    }

    public function getIdUtilisateur() {
        return $this->idUtilisateur;
    }

    // ✍️ Setters
    public function setIdProposition($idProposition) {
        $this->idProposition = $idProposition;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setType($type) {
        $this->type = $type;
    }

    public function setDateSoumission($dateSoumission) {
        $this->dateSoumission = $dateSoumission;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }

    public function setIdUtilisateur($idUtilisateur) {
        $this->idUtilisateur = $idUtilisateur;
    }
}

?>
