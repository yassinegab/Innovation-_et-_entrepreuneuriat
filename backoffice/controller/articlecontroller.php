<?php
require_once(__DIR__ . '/../../config.php');


require_once(__DIR__ . '/../model/articlemodel.php');


class ArticleController {

    

    public function afficher() {
        $sql = "SELECT * FROM article";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public function ajout($article) {
        try {
            $db = config::getConnexion();
            $sql = "INSERT INTO article (nom_article, Date_de_soumission, Catégorie)
                    VALUES (:nom, :date, :categorie)";
            
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':nom', $article->getNomArticle());
            $stmt->bindValue(':date', $article->getDateSoumission());
            $stmt->bindValue(':categorie', $article->getCategorie());

            if ($stmt->execute()) {
                echo "✅ Article ajouté avec succès !";
            } else {
                echo "❌ Erreur lors de l'ajout.";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function modification($article) {
        try {
            $db = config::getConnexion();
            $sql = "UPDATE article SET 
                        nom_article = :nom,
                        Date_de_soumission = :date,
                        Catégorie = :categorie
                    WHERE ID_Article = :id";
            
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $article->getId());
            $stmt->bindValue(':nom', $article->getNomArticle());
            $stmt->bindValue(':date', $article->getDateSoumission());
            $stmt->bindValue(':categorie', $article->getCategorie());

            if ($stmt->execute()) {
                echo "✅ Article modifié avec succès !";
            } else {
                echo "❌ Erreur lors de la modification.";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }

    public function supp($id) {
        try {
            $db = config::getConnexion();
            $sql = "DELETE FROM article WHERE ID_Article= :id";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            echo "✅ Article supprimé avec succès !";
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>
