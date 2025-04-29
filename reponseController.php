<?php

// Au début de votre ReponseController.php
require_once('../../config.php'); // Ajustez le chemin selon l'emplacement de votre fichier config.php 
include '../../model/reponse.php'; 



class ReponseController
{
    
    // ✅ Récupérer toutes les réponses par consultation
    public function getReponsesByConsultationId($id_consultation)
    {
        $sql = "SELECT * FROM reponses WHERE id_consultation = :id_consultation";
        $db = config::getConnexion();

        try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id_consultation', $id_consultation, PDO::PARAM_INT);
            $stmt->execute();
            
            // Création d'un tableau d'objets Reponse
            $reponses = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $reponse = new Reponse(
                    $row['id_reponse'],
                    $row['contenu'],
                    $row['date_reponse'],
                    $row['id_consultation'],
                    $row['id_utilisateur']
                );
                $reponses[] = $reponse;
            }
            return $reponses;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }


    public function getConsultationsAvecReponses() {
        $sql = "SELECT 
                    c.id_consultation,
                    c.titre,
                    c.description,
                    c.date_consultation,
                    c.type,
                    c.statut,
                    c.id_utilisateur,
                    r.id_reponse,
                    r.contenu AS contenu_reponse,
                    r.date_reponse
                FROM 
                    consultations c
                LEFT JOIN 
                    reponse r ON c.id_consultation = r.id_consultation";
    
         $db = config::getConnexion();
         $stmt = $db->prepare($sql);
         $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getConsultationById($id_consultation) {
        try {
            $db = new PDO('mysql:host=localhost;dbname=projet', 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
            $query = "SELECT * FROM consultations WHERE id_consultation = :id";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':id', $id_consultation, PDO::PARAM_INT);
            $stmt->execute();
    
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Database error: " . $e->getMessage());
            return false;
        }
    }
        // ✅ Ajouter une réponse
        public function ajouterReponse($reponse)
        {
            $sql = "INSERT INTO reponses ( contenu, date_reponse, id_consultation, id_utilisateur) 
                    VALUES ( :contenu, :date_reponse, :id_consultation, :id_utilisateur)";
    
            $db = config::getConnexion();
    
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':contenu', $reponse->getContenu());
                $stmt->bindValue(':date_reponse', $reponse->getdate_reponse());
                $stmt->bindValue(':id_consultation', $reponse->getid_consultation());
                $stmt->bindValue(':id_utilisateur', $reponse->getid_utilisateur());
                $stmt->execute();
            } catch (PDOException $e) {
                die("Erreur lors de l'ajout : " . $e->getMessage());
            }
        }
    
        // ✅ Afficher toutes les réponses (back_office)
        public function afficherReponsesBack()
        {
            $sql = "SELECT * FROM reponses";
            $db = config::getConnexion();
    
            try {
                $stmt = $db->prepare($sql);
                $stmt->execute();
    
                $reponses = [];
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $reponse = new Reponse(
                        $row['id_reponse'],
                        $row['contenu'],
                        $row['date_reponse'],
                        $row['id_consultation'],
                        $row['id_utilisateur']
                    );
                    $reponses[] = $reponse;
                }
                return $reponses;
            } catch (PDOException $e) {
                die("Erreur : " . $e->getMessage());
            }
        }
    
        // ✅ Supprimer une réponse
        public function supprimerReponse($id_reponse) {
            $sql = "DELETE FROM reponses WHERE id_reponse = :id_reponse";
            $db = config::getConnexion(); // ou ta connexion PDO
            try {
                $query = $db->prepare($sql);
                $query->bindValue(':id_reponse', $id_reponse, PDO::PARAM_INT);
                $query->execute();
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
        
    
        // ✅ Récupérer une réponse par son ID
        public function recupererReponse($id_reponse)
        {
            $sql = "SELECT * FROM reponses WHERE id_reponse = :id";
            $db = config::getConnexion();
    
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindParam(':id', $id_reponse, PDO::PARAM_INT);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
                if ($row) {
                    return new Reponse(
                        $row['id_reponse'],
                        $row['contenu'],
                        $row['date_reponse'],
                        $row['id_consultation'],
                        $row['id_utilisateur']
                    );
                }
                return null;
            } catch (PDOException $e) {
                die("Erreur récupération : " . $e->getMessage());
            }
        }
    
        // ✅ Modifier une réponse
        public function modifierReponse($reponse)
        {
            $sql = "UPDATE reponses 
                    SET contenu = :contenu, 
                        date_reponse = :date_reponse,
                        id_consultation = :id_consultation,
                        id_utilisateur = :id_utilisateur
                    WHERE id_reponse = :id_reponse";
    
            $db = config::getConnexion();
    
            try {
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':contenu', $reponse->getContenu());
                $stmt->bindValue(':date_reponse', $reponse->getdate_reponse());
                $stmt->bindValue(':id_consultation', $reponse->getid_consultation());
                $stmt->bindValue(':id_utilisateur', $reponse->getid_utilisateur());
                $stmt->bindValue(':id_reponse', $reponse->getid_reponse());
                $stmt->execute();
            } catch (PDOException $e) {
                die("Erreur modification : " . $e->getMessage());
            }
        }
        public function updateConsultationStatus($id_consultation, $statut) {
            try {
                $db = config::getConnexion();
                
                $query = "UPDATE consultations SET statut = :statut WHERE id_consultation = :id_consultation";
                $stmt = $db->prepare($query);
                $stmt->bindParam(':statut', $statut);
                $stmt->bindParam(':id_consultation', $id_consultation);
                
                return $stmt->execute();
            } catch (PDOException $e) {
                die("Erreur de mise à jour du statut : " . $e->getMessage());
            }
        }   
        // Add these methods to your ConsultationController class

    
}
?>
