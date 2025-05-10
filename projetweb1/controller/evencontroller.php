<?php
require_once __DIR__ . '/../model/evenmodel.php';
include dirname(__DIR__) . '/config.php';

class EvenementController {
    private $conn;

    public function __construct() {
        $this->conn = config::getConnexion();
    }

    // Ajouter un événement
    public function addEvenement($evenement) {
        try {
            $query = "INSERT INTO evenement (nom, date, lieu, capacite, categorie, prix, espace_fumeur, accompagnateur_autorise) 
                      VALUES (:nom, :date, :lieu, :capacite, :categorie, :prix, :espace_fumeur, :accompagnateur_autorise)";
            
            $stmt = $this->conn->prepare($query);
            
            // Stocker les valeurs dans des variables temporaires
            $nom = $evenement->getNom();
            $date = $evenement->getDate();
            $lieu = $evenement->getLieu();
            $capacite = $evenement->getCapacite();
            $categorie = $evenement->getCategorie();
            $prix = $evenement->getPrix();
            $espace_fumeur = $evenement->getEspaceFumeur();
            $accompagnateur_autorise = $evenement->getAccompagnateurAutorise();
            
            // Utiliser les variables temporaires pour bindParam
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':lieu', $lieu);
            $stmt->bindParam(':capacite', $capacite);
            $stmt->bindParam(':categorie', $categorie);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':espace_fumeur', $espace_fumeur, PDO::PARAM_BOOL);
            $stmt->bindParam(':accompagnateur_autorise', $accompagnateur_autorise, PDO::PARAM_BOOL);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'événement: " . $e->getMessage();
            return false;
        }
    }

    // Récupérer toutes les catégories distinctes
    public function getAllCategories() {
        try {
            $query = "SELECT DISTINCT categorie FROM evenement WHERE categorie IS NOT NULL ORDER BY categorie";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $categories = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if (!empty($row['categorie'])) {
                    $categories[] = $row['categorie'];
                }
            }
            
            return $categories;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des catégories: " . $e->getMessage();
            return [];
        }
    }

    // Les autres méthodes du contrôleur
    // Récupérer tous les événements
    public function listeven() {
        try {
            $query = "SELECT * FROM evenement ORDER BY date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des événements: " . $e->getMessage();
            return [];
        }
    }

    // Supprimer un événement
    public function deleteeven($id) {
        try {
            $query = "DELETE FROM evenement WHERE id_ev = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'événement: " . $e->getMessage();
            return false;
        }
    }

    // Récupérer un événement par son ID
    public function getEventById($id) {
        try {
            $query = "SELECT * FROM evenement WHERE id_ev = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération de l'événement: " . $e->getMessage();
            return null;
        }
    }

    // Rechercher des événements
    public function searchEvents($keyword) {
        try {
            $keyword = '%' . $keyword . '%';
            $query = "SELECT * FROM evenement 
                      WHERE nom LIKE :keyword 
                      OR lieu LIKE :keyword 
                      OR categorie LIKE :keyword 
                      ORDER BY date DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la recherche d'événements: " . $e->getMessage();
            return [];
        }
    }

    // Filtrer par catégorie
    public function filterByCategory($category) {
        try {
            $query = "SELECT * FROM evenement WHERE categorie = :category ORDER BY date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':category', $category);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors du filtrage par catégorie: " . $e->getMessage();
            return [];
        }
    }

    // Filtrer par espace fumeur
    public function filterByEspaceFumeur($espace_fumeur) {
        try {
            $query = "SELECT * FROM evenement WHERE espace_fumeur = :espace_fumeur ORDER BY date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':espace_fumeur', $espace_fumeur, PDO::PARAM_BOOL);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors du filtrage par espace fumeur: " . $e->getMessage();
            return [];
        }
    }

    // Filtrer par accompagnateur autorisé
    public function filterByAccompagnateurAutorise($accompagnateur_autorise) {
        try {
            $query = "SELECT * FROM evenement WHERE accompagnateur_autorise = :accompagnateur_autorise ORDER BY date DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':accompagnateur_autorise', $accompagnateur_autorise, PDO::PARAM_BOOL);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors du filtrage par accompagnateur autorisé: " . $e->getMessage();
            return [];
        }
    }

    // Mettre à jour un événement
    public function updateven($id, $nom, $date, $lieu, $capacite, $categorie = null, $prix = 0, $espace_fumeur = 0, $accompagnateur_autorise = 0) {
        try {
            $query = "UPDATE evenement 
                      SET nom = :nom, date = :date, lieu = :lieu, capacite = :capacite, 
                          categorie = :categorie, prix = :prix, espace_fumeur = :espace_fumeur, 
                          accompagnateur_autorise = :accompagnateur_autorise 
                      WHERE id_ev = :id";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':lieu', $lieu);
            $stmt->bindParam(':capacite', $capacite);
            $stmt->bindParam(':categorie', $categorie);
            $stmt->bindParam(':prix', $prix);
            $stmt->bindParam(':espace_fumeur', $espace_fumeur, PDO::PARAM_BOOL);
            $stmt->bindParam(':accompagnateur_autorise', $accompagnateur_autorise, PDO::PARAM_BOOL);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour de l'événement: " . $e->getMessage();
            return false;
        }
    }

    // Dupliquer un événement
    public function duplicateEvent($id) {
        try {
            // Récupérer l'événement à dupliquer
            $event = $this->getEventById($id);
            
            if (!$event) {
                return false;
            }
            
            // Créer une copie avec un nouveau nom
            $newName = $event['nom'] . ' (copie)';
            
            $query = "INSERT INTO evenement (nom, date, lieu, capacite, categorie, prix, espace_fumeur, accompagnateur_autorise) 
                      VALUES (:nom, :date, :lieu, :capacite, :categorie, :prix, :espace_fumeur, :accompagnateur_autorise)";
            
            $stmt = $this->conn->prepare($query);
            
            $stmt->bindParam(':nom', $newName);
            $stmt->bindParam(':date', $event['date']);
            $stmt->bindParam(':lieu', $event['lieu']);
            $stmt->bindParam(':capacite', $event['capacite']);
            $stmt->bindParam(':categorie', $event['categorie']);
            $stmt->bindParam(':prix', $event['prix']);
            $stmt->bindParam(':espace_fumeur', $event['espace_fumeur'], PDO::PARAM_BOOL);
            $stmt->bindParam(':accompagnateur_autorise', $event['accompagnateur_autorise'], PDO::PARAM_BOOL);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de la duplication de l'événement: " . $e->getMessage();
            return false;
        }
    }
}
?>
