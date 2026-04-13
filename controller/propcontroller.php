<?php
require_once(__DIR__ . '/../config.php');
require_once(__DIR__ . '/../model/propmodel.php');
class propcontroller{


    public function afficher()
    {
        $sql="SELECT * FROM proposition";
        $db=config::getConnexion();
        try{
            $liste=$db->query($sql);
            return $liste;
        }catch(Exception $e){die('error'.$e->getMessage());}
    }
    public function afficherById($idProposition)
{
    $sql = "SELECT * FROM proposition WHERE ID_Proposition = :idProposition";
    $db = config::getConnexion();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idProposition', $idProposition);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        die('error: ' . $e->getMessage());
    }
}

public function afficherByIdfetchall($idProposition)
{
    $sql = "SELECT * FROM proposition WHERE ID_Proposition = :idProposition";
    $db = config::getConnexion();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':idProposition', $idProposition);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        die('error: ' . $e->getMessage());
    }
}

    public function afficheruser($id)
{
    $db = config::getConnexion();
    $sql = "SELECT * FROM user WHERE iduser = :id";

    try {
        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        $req->execute();
        return $req->fetch(PDO::FETCH_ASSOC); // retourne un seul user
    } catch (Exception $e) {
        die('❌ Erreur : ' . $e->getMessage());
    }
}
public function ajouterproposition($proposition)
    {
        try {
            $db = config::getConnexion(); 
            $sql = "INSERT INTO proposition ( ID_Proposition, Titre, Description, Type, Date_Soumission,Statut,ID_Utilisateur)
                    VALUES ( :id, :titre, :desc, :type,:date,:stat,:idut)";
            
            $stmt = $db->prepare($sql);
            
            // Bind des valeurs
            
            $stmt->bindValue(':id',$proposition->getIdProposition());
            $stmt->bindValue(':titre', $proposition->getTitre());
            $stmt->bindValue(':desc', $proposition->getDescription());
            $stmt->bindValue(':type', $proposition->getType());
            $stmt->bindValue(':date', $proposition->getDateSoumission());
            $stmt->bindValue(':stat', $proposition->getStatut());
            $stmt->bindValue(':idut', $proposition->getIdUtilisateur());
            
            // Exécution de la requête
            if ($stmt->execute()) {
                echo "✅ prop ajoutée avec succès !";
            } else {
                echo "❌ Erreur lors de l'ajout de la prop.";
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    }
    public function suppprop($id)
    {
        try {
            $db = config::getConnexion();
            $sql = "DELETE FROM proposition WHERE ID_Proposition = :id";
            $req = $db->prepare($sql);
            $req->bindValue(':id', $id);
            $req->execute();
           // echo "✅ Commande supprimée avec succès !";
        } catch (Exception $e) {
            die("❌ Erreur : " . $e->getMessage());
        }
    }
    public function modify($proposition)
{
    try {
        $db = config::getConnexion();
        // Correct the SQL query by adding the SET clause for the field you want to modify
        $sql = "UPDATE proposition SET 
            Titre = :titre, 
            Description = :desc, 
            Type = :type, 
            Date_Soumission = :date
        WHERE ID_Proposition = :id";

        $req = $db->prepare($sql);
        $req->bindValue(':id', $proposition->getIdProposition());
        $req->bindValue(':titre',  $proposition->getTitre());
        $req->bindValue(':date',  $proposition->getDateSoumission());
        $req->bindValue(':desc', $proposition->getDescription());
        $req->bindValue(':type',  $proposition->getType());
        $req->execute();
        
        echo "✅ Commande modifiée avec succès !";
    } catch (Exception $e) {
        die("❌ Erreur : " . $e->getMessage());
    }
}

public function modify2($statut,$id)
{
    try {
        $db = config::getConnexion();
        // Correct the SQL query by adding the SET clause for the field you want to modify
        $sql = "UPDATE proposition SET 
            Statut = :stat
        WHERE ID_Proposition = :id";

        $req = $db->prepare($sql);
        $req->bindValue(':id', $id);
        $req->bindValue(':stat',  $statut);
       
        $req->execute();
        
        echo "✅ Commande modifiée avec succès !";
    } catch (Exception $e) {
        die("❌ Erreur : " . $e->getMessage());
    }
}
public function ajouterdemande($id,$idprop,$role,$dateDebut,$dateFin,$type)
{
    try {
        $db = config::getConnexion(); 
        $sql = "INSERT INTO demandes ( id_user,id_proposition,role,date_debut,date_fin,type)
                VALUES ( :id, :idprop,:role,:date1,:date2,:type)";
        
        $stmt = $db->prepare($sql);
        
        // Bind des valeurs
        
        $stmt->bindValue(':id',$id);
        $stmt->bindValue(':idprop', $idprop);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':date1', $dateDebut);
        $stmt->bindValue(':date2', $dateFin);
        $stmt->bindValue(':type', $type);
       
        
        // Exécution de la requête
        if ($stmt->execute()) {
            echo "✅ prop ajoutée avec succès !";
        } else {
            echo "❌ Erreur lors de l'ajout de la prop.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
public function affichernotifById($idProposition)
{
    $sql = "SELECT d.* 
            FROM demandes d
            INNER JOIN proposition p ON d.id_proposition = p.ID_Proposition
            WHERE p.ID_Utilisateur = :idProposition";
    $db = config::getConnexion();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idProposition', $idProposition);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        die('error: ' . $e->getMessage());
    }
}
public function affichernotifByitsId($idProposition)
{
    $sql = "SELECT * 
            FROM demandes where id_demande=:idProposition";
    $db = config::getConnexion();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idProposition', $idProposition);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        die('error: ' . $e->getMessage());
    }
}

public function deleteDemande($idDemande) {
    try {
        // Connexion à la base de données
        $db = config::getConnexion();

        // Requête SQL pour supprimer une demande
        $sql = "DELETE FROM demandes WHERE id_demande = :idDemande";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idDemande', $idDemande);
        $stmt->execute();

        echo "✅ Demande supprimée avec succès !";
    } catch (Exception $e) {
        die('❌ Erreur : ' . $e->getMessage());
    }
}
public function inserermsg($idsender,$idreceiver,$message)
{
    
    try {
        $db = config::getConnexion(); 
        $sql = "INSERT INTO messages ( id_sender,id_receiver,conetnu)
                VALUES ( :idsender, :idreceiver,:contenu)";
        
        $stmt = $db->prepare($sql);
        
        // Bind des valeurs
        
        $stmt->bindValue(':idsender',$idsender);
        $stmt->bindValue(':idreceiver', $idreceiver);
        $stmt->bindValue(':contenu', $message);
       
       
        
        // Exécution de la requête
        if ($stmt->execute()) {
            echo "✅ msg ajoutée avec succès !";
        } else {
            echo "❌ Erreur lors de l'ajout de la msg.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

}
public function selectmsg($id)
{
    $sql = "SELECT * 
            FROM messages where id_sender=:id or id_receiver=:id order by date ASC";
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



public function inserernotifmeet($idsender,$idreceiver,$room)
{
    
    try {
        $db = config::getConnexion(); 
        $sql = "INSERT INTO notification_meet ( idsender,idreceiver,roomurl)
                VALUES ( :idsender, :idreceiver,:contenu)";
        
        $stmt = $db->prepare($sql);
        
        // Bind des valeurs
        
        $stmt->bindValue(':idsender',$idsender);
        $stmt->bindValue(':idreceiver', $idreceiver);
        $stmt->bindValue(':contenu', $room);
       
       
        
        // Exécution de la requête
        if ($stmt->execute()) {
            echo "✅ msg ajoutée avec succès !";
        } else {
            echo "❌ Erreur lors de l'ajout de la msg.";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }

}
public function selectmeet($id)
{
    $sql = "SELECT * 
            FROM notification_meet where  idreceiver=:id";
    $db = config::getConnexion();
    try {
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    } catch (Exception $e) {
        die('error: ' . $e->getMessage());
    }
}
public function deletemeet($idDemande) {
    try {
        // Connexion à la base de données
        $db = config::getConnexion();

        // Requête SQL pour supprimer une demande
        $sql = "DELETE FROM notification_meet WHERE idreceiver = :idDemande";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':idDemande', $idDemande);
        $stmt->execute();

        echo "✅ Demande supprimée avec succès !";
    } catch (Exception $e) {
        die('❌ Erreur : ' . $e->getMessage());
    }
}
}
?>