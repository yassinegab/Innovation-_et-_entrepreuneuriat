<?php
class Evenement
{
    private $id_evenement;
    private $nom;
    private $date;
    private $lieu;
    private $capacite;
    private $categorie;
    private $prix;
    private $espace_fumeur;
    private $accompagnateur_autorise;
    private $image;

    public function __construct(
        $id_evenement = null, 
        $nom = "", 
        $date = "", 
        $lieu = "", 
        $capacite = 0,
        $categorie = "",
        $prix = 0,
        $espace_fumeur = false,
        $accompagnateur_autorise = false,
        $image=NULL
    ) {
        $this->id_evenement = $id_evenement;
        $this->nom = $nom;
        $this->date = $date;
        $this->lieu = $lieu;
        $this->capacite = $capacite;
        $this->categorie = $categorie;
        $this->prix = $prix;
        $this->espace_fumeur = $espace_fumeur;
        $this->accompagnateur_autorise = $accompagnateur_autorise;
        $this->image=$image;
    }

    // Getters existants
    public function getIdEvenement() { return $this->id_evenement; }
    public function getNom() { return $this->nom; }
    public function getDate() { return $this->date; }
    public function getLieu() { return $this->lieu; }
    public function getCapacite() { return $this->capacite; }
    
    // Nouveaux getters
    public function getCategorie() { return $this->categorie; }
    public function getPrix() { return $this->prix; }
    public function getEspaceFumeur() { return $this->espace_fumeur; }
    public function getAccompagnateurAutorise() { return $this->accompagnateur_autorise; }
     public function getimage() { return $this->image; }

    // Setters existants
    public function setIdEvenement($id_evenement) { $this->id_evenement = $id_evenement; }
    public function setNom($nom) { $this->nom = $nom; }
    public function setDate($date) { $this->date = $date; }
    public function setLieu($lieu) { $this->lieu = $lieu; }
    public function setCapacite($capacite) { $this->capacite = $capacite; }
    
    // Nouveaux setters
    public function setCategorie($categorie) { $this->categorie = $categorie; }
    public function setPrix($prix) { $this->prix = $prix; }
    public function setEspaceFumeur($espace_fumeur) { $this->espace_fumeur = $espace_fumeur; }
    public function setAccompagnateurAutorise($accompagnateur_autorise) { $this->accompagnateur_autorise = $accompagnateur_autorise; }
}
?>
