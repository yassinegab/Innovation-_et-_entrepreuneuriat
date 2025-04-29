<?php
include_once('../../config.php');
include '../../model/inscmodel.php';


class InscriptionController {
    public function addInscription($inscription) {
        $db = config::getConnexion();
    
        try {
            // 1. Récupérer la capacité de l’événement
            $queryCap = $db->prepare("SELECT capacite FROM evenement WHERE id_ev = :id");
            $queryCap->bindValue(':id', $inscription->getIdEvenement());
            $queryCap->execute();
            $capacite = $queryCap->fetchColumn();
    
            // 2. Compter les inscriptions déjà faites
            $queryCount = $db->prepare("SELECT COUNT(*) FROM inscription WHERE id_eve = :id");
            $queryCount->bindValue(':id', $inscription->getIdEvenement());
            $queryCount->execute();
            $nbInscrits = $queryCount->fetchColumn();
    
            // 3. Comparer
            if ($nbInscrits >= $capacite) {
                // Capacité atteinte, refuser l'inscription
                return "full";
            }
    
            // 4. Insertion si tout est OK
            $sql = "INSERT INTO inscription (id_eve, id_uti, statut) 
                    VALUES (:id_eve, :id_uti, :statut)";
            $query = $db->prepare($sql);
            $query->bindValue(':id_eve', $inscription->getIdEvenement());
            $query->bindValue(':id_uti', $inscription->getIdUtilisateur());
            $query->bindValue(':statut', $inscription->getStatut());
            $query->execute();
    
            return $db->lastInsertId();
    
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return false;
        }
    }
    
    function afficherInscriptionsAvecEvenement() {
        $sql = "SELECT i.*, e.nom, e.date, e.lieu ,e.capacite
                FROM inscription i 
                JOIN evenement e ON i.id_eve = e.id_ev";
    
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die('Erreur: ' . $e->getMessage());
        }
    }
        public function deleteinsc($id)
        {
            $sql = "DELETE FROM inscription WHERE id_inscription = :id"; 
            $db = config::getConnexion();
        
            try {
                $query = $db->prepare($sql);
                $query->bindValue(':id', $id, PDO::PARAM_INT); // on précise le type pour sécuriser
                $query->execute();
            } catch (Exception $e) {
                die("Erreur : " . $e->getMessage());
            }
        }
        public function updateStatut($id_inscription, $nouveau_statut) {
            try {
                $pdo = config::getConnexion();
                $query = $pdo->prepare('UPDATE inscription SET statut = :statut WHERE id_inscription = :id_inscription');
                $query->execute([
                    'statut' => $nouveau_statut,
                    'id_inscription' => $id_inscription
                ]);
                return true;
            } catch (PDOException $e) {
                echo 'Erreur: ' . $e->getMessage();
                return false;
            }
        }
        
        

    }
    
   /* public function afficherInscriptions() {
        $sql = "SELECT * FROM inscription";
        $db = config::getConnexion();
        try {
            $query = $db->query($sql);
            $inscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
            return $inscriptions;
        } catch (Exception $e) {
            echo "Erreur: " . $e->getMessage();
            return [];
        }
    }*/
    

    








