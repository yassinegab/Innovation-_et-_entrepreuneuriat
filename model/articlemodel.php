<?php
class Article
{
    private $id;
    private $nom_article;
    private $date_soumission;
    private $categorie;
    private $contenu;
    private $image;
    private $views;
    private $likes;



    public function __construct(
        $id = null,
        $nom_article = "",
        $date_soumission = "",
        $categorie = "",
        $contenu = "",
        $image = "",
        $views = 0,
        $likes = 0,

    ) {
        $this->id             = $id;
        $this->nom_article    = $nom_article;
        $this->date_soumission = $date_soumission;
        $this->categorie      = $categorie;
        $this->contenu        = $contenu;
        $this->image          = $image;
        $this->views          = $views;
        $this->likes          = $likes;
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getNomArticle()
    {
        return $this->nom_article;
    }
    public function getDateSoumission()
    {
        return $this->date_soumission;
    }
    public function getCategorie()
    {
        return $this->categorie;
    }
    public function getContenu()
    {
        return $this->contenu;
    }
    public function getImage()
    {
        return $this->image;
    }
    public function getViews()
    {
        return $this->views;
    }
    public function getLikes()
    {
        return $this->likes;
    }



    // Setters
    public function setId($id)
    {
        $this->id = $id;
    }
    public function setNomArticle($nom_article)
    {
        $this->nom_article = $nom_article;
    }
    public function setDateSoumission($date_soumission)
    {
        $this->date_soumission = $date_soumission;
    }
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
    }
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }
    public function setImage($image)
    {
        $this->image = $image;
    }
    public function setViews($views)
    {
        $this->views = $views;
    }
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }



    public function getExcerpt($maxLength = 200)
    {
        // Remove HTML tags from the contenu property
        $cleanContent = strip_tags($this->contenu);

        // Trim to max length
        if (strlen($cleanContent) > $maxLength) {
            $excerpt = substr($cleanContent, 0, $maxLength);
            $lastSpace = strrpos($excerpt, ' ');
            return substr($excerpt, 0, $lastSpace) . '...';
        }
        return $cleanContent;
    }
}
