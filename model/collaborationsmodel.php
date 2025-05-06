<?php

class Collaboration {
    private $idCollaboration;
    private $idProposition;
    private $collaborateurId;
    private $role;
    private $dateDebut;
    private $dateFin;
    private $typeCollaboration;
    private $statut;

    // 🔨 Constructeur
    public function __construct($idCollaboration, $idProposition, $collaborateurId, $role, $dateDebut, $dateFin, $typeCollaboration, $statut) {
        $this->idCollaboration = $idCollaboration;
        $this->idProposition = $idProposition;
        $this->collaborateurId = $collaborateurId;
        $this->role = $role;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->typeCollaboration = $typeCollaboration;
        $this->statut = $statut;
    }

    // ✅ Getters
    public function getIdCollaboration() {
        return $this->idCollaboration;
    }

    public function getIdProposition() {
        return $this->idProposition;
    }

    public function getCollaborateurId() {
        return $this->collaborateurId;
    }

    public function getRole() {
        return $this->role;
    }

    public function getDateDebut() {
        return $this->dateDebut;
    }

    public function getDateFin() {
        return $this->dateFin;
    }

    public function getTypeCollaboration() {
        return $this->typeCollaboration;
    }

    public function getStatut() {
        return $this->statut;
    }

    // ✍️ Setters
    public function setIdCollaboration($idCollaboration) {
        $this->idCollaboration = $idCollaboration;
    }

    public function setIdProposition($idProposition) {
        $this->idProposition = $idProposition;
    }

    public function setCollaborateurId($collaborateurId) {
        $this->collaborateurId = $collaborateurId;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setDateDebut($dateDebut) {
        $this->dateDebut = $dateDebut;
    }

    public function setDateFin($dateFin) {
        $this->dateFin = $dateFin;
    }

    public function setTypeCollaboration($typeCollaboration) {
        $this->typeCollaboration = $typeCollaboration;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }
}

?>