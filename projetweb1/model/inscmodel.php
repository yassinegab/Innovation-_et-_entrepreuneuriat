<?php
class Inscription
{
    private $id_inscription;
    private $id_evenement;
    private $id_utilisateur;
    private $statut;

    public function __construct($id_inscription = null, $id_evenement = 0, $id_utilisateur = 0, $statut = "")
    {
        $this->id_inscription = $id_inscription;
        $this->id_evenement = $id_evenement;
        $this->id_utilisateur = $id_utilisateur;
        $this->statut = $statut;
    }

    // Getters
    public function getIdInscription() { return $this->id_inscription; }
    public function getIdEvenement() { return $this->id_evenement; }
    public function getIdUtilisateur() { return $this->id_utilisateur; }
    public function getStatut() { return $this->statut; }

    // Setters
    public function setIdInscription($id_inscription) { $this->id_inscription = $id_inscription; }
    public function setIdEvenement($id_evenement) { $this->id_evenement = $id_evenement; }
    public function setIdUtilisateur($id_utilisateur) { $this->id_utilisateur = $id_utilisateur; }
    public function setStatut($statut) { $this->statut = $statut; }
}
?>
