<?php
require_once(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/../model/articlemodel.php');

class ArticleController
{

    public function afficher()
    {
        $sql = "SELECT * FROM article";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function ajout($article)
    {
        try {
            $db = config::getConnexion();
            $sql = "INSERT INTO article 
                    (nom_article, Date_de_soumission, Catégorie, contenu, image)
                    VALUES 
                    (:nom, :date, :categorie, :contenu, :image)";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':nom', $article->getNomArticle());
            $stmt->bindValue(':date', $article->getDateSoumission());
            $stmt->bindValue(':categorie', $article->getCategorie());
            $stmt->bindValue(':contenu', $article->getContenu());
            $stmt->bindValue(':image', $article->getImage());



            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur SQL dans ajout(): " . $e->getMessage());
            return false;
        }
    }
    public function modification($article)
    {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE article SET
                    nom_article = :nom,
                    Date_de_soumission = :date,
                    Catégorie = :categorie,
                    contenu = :contenu,
                    image = :image
                    WHERE ID_Article = :id";

            $stmt = $db->prepare($sql);

            // Bind parameters
            $stmt->bindValue(':id', $article->getId(), PDO::PARAM_INT);
            $stmt->bindValue(':nom', $article->getNomArticle());
            $stmt->bindValue(':date', $article->getDateSoumission());
            $stmt->bindValue(':categorie', $article->getCategorie());
            $stmt->bindValue(':contenu', $article->getContenu());
            $stmt->bindValue(':image', $article->getImage());


            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Erreur SQL dans modification(): " . $e->getMessage());
            return false;
        }
    }
    public function getArticleById($id)
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT * FROM article WHERE ID_Article = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                return new Article(
                    $row['ID_Article'],
                    $row['nom_article'],
                    $row['Date_de_soumission'],
                    $row['Catégorie'],
                    $row['contenu'],
                    $row['image'],
                    $row['views'],
                    $row['likes'],
                );
            }
            return null;
        } catch (Exception $e) {
            error_log("Erreur dans getArticleById(): " . $e->getMessage());
            return null;
        }
    }

    public function getTrendingArticles($limit = 3)
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT *, (views * 0.6 + likes * 0.4) AS trending_score 
                    FROM article 
                    ORDER BY trending_score DESC 
                    LIMIT :limit";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_FUNC, function (
                $id,
                $nom_article,
                $date_soumission,
                $categorie,
                $contenu,
                $image,
                $views,
                $likes
            ) {
                return new Article(
                    $id,
                    $nom_article,
                    $date_soumission,
                    $categorie,
                    $contenu,
                    $image,
                    $views,
                    $likes,

                );
            });
        } catch (Exception $e) {
            error_log("Erreur dans getTrendingArticles(): " . $e->getMessage());
            return [];
        }
    }

    public function getRecentArticles($limit = 5)
    {
        try {
            $db = config::getConnexion();
            $sql = "SELECT * FROM article 
                    ORDER BY Date_de_soumission DESC 
                    LIMIT :limit";

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_FUNC, function (
                $id,
                $nom_article,
                $date_soumission,
                $categorie,
                $contenu,
                $image,
                $views,
                $likes
            ) {
                return new Article(
                    $id,
                    $nom_article,
                    $date_soumission,
                    $categorie,
                    $contenu,
                    $image,
                    $views,
                    $likes,

                );
            });
        } catch (Exception $e) {
            error_log("Erreur dans getRecentArticles(): " . $e->getMessage());
            return [];
        }
    }

    public function supp($id)
    {
        try {
            $db = config::getConnexion();
            $article = $this->getArticleById($id);

            if ($article && $article->getImage()) {
                $imagePath = __DIR__ . '/../../uploads/' . basename($article->getImage());
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $sql = "DELETE FROM article WHERE ID_Article = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erreur dans supp(): " . $e->getMessage());
            return false;
        }
    }
    public function incrementViews($articleId)
    {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE article SET views = views + 1 WHERE ID_Article = :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $articleId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error incrementing views: " . $e->getMessage());
            return false;
        }
    }
    
}

