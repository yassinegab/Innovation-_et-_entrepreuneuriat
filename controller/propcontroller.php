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
            echo "✅ Commande supprimée avec succès !";
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
}
?>