<?php
class Commentaire
{
    private $id;
    private $article_id;
    private $auteur;
    private $contenu;
    private $date_publication;
    private $likes;
    private $parent_id;
    private $status;
    private $isRead;
    // private $articleTitle;
    private $user_id;

    public function __construct(
        $id = null,
        $article_id = null,
        $user_id = null, // ← Déplacé en position 3
        $contenu = "",
        $date_publication = "",
        $likes = 0,
        $parent_id = null,
        $status = 'pending',
        $isRead = 0,
        // $articleTitle = null,
        $auteur = "" // ← Déplacé en dernière position
    ) {
        $this->id = $id;
        $this->article_id = $article_id;
        $this->user_id = $user_id; // ← L'ID numérique
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
        $this->likes = $likes;
        $this->parent_id = $parent_id;
        $this->status = $status;
        $this->isRead = $isRead;
        // $this->articleTitle = $articleTitle;
        $this->auteur = $auteur; // ← Le nom d'utilisateur
    }

    // Getters
    public function getId()
    {
        return $this->id;
    }
    public function getArticleId()
    {
        return $this->article_id;
    }
    public function getAuteur()
    {
        return $this->auteur;
    }
    public function getContenu()
    {
        return $this->contenu;
    }
    public function getDatePublication()
    {
        return $this->date_publication;
    }
    public function getLikes()
    {
        return $this->likes;
    }
    public function getParentId()
    {
        return $this->parent_id;
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function getIsRead()
    {
        return $this->isRead;
    }
    // public function getArticleTitle()
    // {
    //     return $this->articleTitle;
    // }
    public function getUserId()
    {
        return $this->user_id;
    }

    // Setters
    public function setArticleId($article_id)
    {
        $this->article_id = $article_id;
    }
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;
    }
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;
    }
    public function setDatePublication($date)
    {
        $this->date_publication = $date;
    }
    public function setLikes($likes)
    {
        $this->likes = $likes;
    }
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }
    // public function setArticleTitle($title)
    // {
    //     $this->articleTitle = $title;
    // }
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setAllFromArray(array $data)
    {
        foreach ($data as $key => $value) {
            $method = 'set' . str_replace('_', '', ucwords($key, '_'));
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
