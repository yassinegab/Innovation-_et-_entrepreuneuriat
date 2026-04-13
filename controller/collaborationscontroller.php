<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../model/collaborationsmodel.php');

class Collaborationscontroller{
    public function afficher()
    {
        $sql="SELECT * FROM collaboration";
        $db=config::getConnexion();
        try{
            $liste=$db->query($sql);
            return $liste;
        }catch(Exception $e){die('error'.$e->getMessage());}
    }
    
    public function selectcollabbyid($id)
    {
        $sql = "SELECT * 
                FROM collaboration where id_proposition=:id ";
        $db = config::getConnexion();
        try {
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result;
        } catch (Exception $e) {
            die('error: ' . $e->getMessage());
        }
    }
    
    public function suppcollab($id)
    {
        try {
            $db = config::getConnexion();
            $sql = "DELETE FROM collaboration WHERE id_collaboration = :id";
            $req = $db->prepare($sql);
            $req->bindValue(':id', $id);
            $req->execute();
            echo "✅ Commande supprimée avec succès !";
        } catch (Exception $e) {
            die("❌ Erreur : " . $e->getMessage());
        }
    }
    
    public function ajouter($collab) {
        try {
            // Connexion à la base de données
            $db = config::getConnexion();

            // Requête SQL pour insérer une nouvelle collaboration
            $sql = "INSERT INTO collaboration (id_proposition, collaborateur_id, role, date_debut, date_fin, type_collaboration, statut) 
                    VALUES (:idProposition, :collaborateurId, :role, :dateDebut, :dateFin, :typeCollaboration, :statut)";
            
            $req = $db->prepare($sql);
            $req->bindValue(':idProposition', $collab->getIdProposition());
            $req->bindValue(':collaborateurId', $collab->getCollaborateurId());
            $req->bindValue(':role', $collab->getRole());
            $req->bindValue(':dateDebut', $collab->getDateDebut());
            $req->bindValue(':typeCollaboration', $collab->getTypeCollaboration());
            $req->bindValue(':statut', $collab->getStatut());
            $req->bindValue(':dateFin', $collab->getDateFin());
            $req->execute();
           

            // Mettre à jour l'ID de la collaboration après insertion
            
            echo "✅ Collaboration ajoutée avec succès !";
           
        } catch (Exception $e) {
            die("❌ Erreur : " . $e->getMessage());
        }
    }

}