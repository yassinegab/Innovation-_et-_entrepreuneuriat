<?php
require_once('../../config.php'); // Assure-toi que ce fichier contient bien la classe config avec getConnexion()

class ConsultationController
{
    
    
    // ✅ Ajouter une consultation (utilisateur)
    public function ajouterConsultation($id_consultation, $titre, $description, $date_consultation, $type, $statut, $id_utilisateur)
    {
        $sql = "INSERT INTO consultations (id_consultation, titre, description, date_consultation, type, statut, id_utilisateur) 
                VALUES (:id_consultation, :titre, :description, :date_consultation, :type, :statut, :id_utilisateur)";
        
        $db = config::getConnexion();
        
        try {
            $statut = 'pending';
            $query = $db->prepare($sql);
            $query->bindValue(':id_consultation', $id_consultation);
            $query->bindValue(':titre', $titre);
            $query->bindValue(':description', $description);
            $query->bindValue(':date_consultation', $date_consultation);
            $query->bindValue(':type', $type);
            $query->bindValue(':statut', $statut);
            $query->bindValue(':id_utilisateur', $id_utilisateur);

            return $query->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout : " . $e->getMessage();
            return false;
        }
    }

    // ✅ Récupérer toutes les consultations (fetch + fetchAll)
    public function listeConsultations()
    {
        $sql = "SELECT * FROM consultations";
        $db = config::getConnexion();

        try {
            return $db->query($sql);
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }


    public function getAllConsultations()
    {
        $sql = "SELECT * FROM consultations";
        $db = config::getConnexion();
    
        try {
            $query = $db->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC); // Ensure it returns an associative array
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Supprimer une consultation
    public function supprimerConsultation($id_consultation)
    {
        $sql = "DELETE FROM consultations WHERE id_consultation = :id_consultation";
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_consultation', $id_consultation);
            $query->execute();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Récupérer une consultation par ID
    public function getConsultationById($id_consultation)
    {
        $sql = "SELECT * FROM consultations WHERE id_consultation = :id_consultation";
        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_consultation', $id_consultation);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    // ✅ Mettre à jour une consultation
    public function updateConsultation($id_consultation, $titre, $description, $date_consultation, $type, $statut)
    {
        $sql = "UPDATE consultations 
                SET titre = :titre, 
                    description = :description, 
                    date_consultation = :date_consultation, 
                    type = :type, 
                    statut = :statut 
                WHERE id_consultation = :id_consultation";
        
        $db = config::getConnexion();

        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_consultation', $id_consultation);
            $query->bindValue(':titre', $titre);
            $query->bindValue(':description', $description);
            $query->bindValue(':date_consultation', $date_consultation);
            $query->bindValue(':type', $type);
            $query->bindValue(':statut', $statut);
            return $query->execute();
        } catch (PDOException $e) {
            error_log("Erreur lors de la mise à jour : " . $e->getMessage());
            return false;
        }
    }
    // Dans votre ReponseController.php

   






    
// Méthode pour rechercher une consultation par ID
public function rechercherConsultationParId($id_consultation)
{
    $sql = "SELECT * FROM consultations WHERE id_consultation = :id_consultation";
    $db = config::getConnexion();

    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id_consultation', $id_consultation);
        $query->execute();
        
        $result = $query->fetch(PDO::FETCH_ASSOC);
        
        if (!$result) {
            return null; // Aucune consultation trouvée avec cet ID
        }
        
        return $result;
    } catch (PDOException $e) {
        echo "Erreur lors de la recherche : " . $e->getMessage();
        return null;
    }
}









public function listeConsultationsSorted($column = 'id_consultation', $order = 'ASC') {
    $pdo = config::getConnexion();
    try {
        // Sécuriser le nom des colonnes
        $allowedColumns = ['id_consultation', 'titre', 'date_consultation', 'type', 'statut', 'id_utilisateur'];
        if (!in_array($column, $allowedColumns)) {
            $column = 'id_consultation';
        }

        $sql = "SELECT * FROM consultations ORDER BY $column $order";
        $query = $pdo->prepare($sql);
        $query->execute();

        return $query->fetchAll();
    } catch (PDOException $e) {
        die('Erreur: ' . $e->getMessage());
    }
}

    
}
?>
