<?php
class Evenement
{
    private $id_evenement;
    private $nom;
    private $date;
    private $lieu;
    private $capacite;

    public function __construct($id_evenement = null, $nom = "", $date = "", $lieu = "", $capacite = 0) {
        $this->id_evenement = $id_evenement;
        $this->nom = $nom;
        $this->date = $date;
        $this->lieu = $lieu;
        $this->capacite = $capacite;
    }

    // Getters
    public function getIdEvenement() { return $this->id_evenement; }
    public function getNom() { return $this->nom; }
    public function getDate() { return $this->date; }
    public function getLieu() { return $this->lieu; }
    public function getCapacite() { return $this->capacite; }

    // Setters
    public function setIdEvenement($id_evenement) { $this->id_evenement = $id_evenement; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDate($date) { $this->date = $date; }
    public function setLieu($lieu) { $this->lieu = $lieu; }
    public function setCapacite($capacite) { $this->capacite = $capacite; }
}
?>