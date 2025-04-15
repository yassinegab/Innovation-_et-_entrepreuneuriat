<?php
class Article {
    private $id;
    private $nom_article;
    private $date_soumission;
    private $categorie;

    public function __construct($id = null, $nom_article = "", $date_soumission = "", $categorie = "") {
        $this->id = $id;
        $this->nom_article = $nom_article;
        $this->date_soumission = $date_soumission;
        $this->categorie = $categorie;
    }

    // Getters
    public function getId() { return $this->id; }
    public function getNomArticle() { return $this->nom_article; }
    public function getDateSoumission() { return $this->date_soumission; }
    public function getCategorie() { return $this->categorie; }

    // Setters
    public function setId($id) { $this->id = $id; }
    public function setNomArticle($nom_article) { $this->nom_article = $nom_article; }
    public function setDateSoumission($date_soumission) { $this->date_soumission = $date_soumission; }
    public function setCategorie($categorie) { $this->categorie = $categorie; }
}
?>
