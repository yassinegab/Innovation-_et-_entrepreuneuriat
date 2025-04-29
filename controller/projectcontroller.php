<?php
require_once __DIR__ . '/../config.php';

require_once __DIR__ . '/../modal/projectmodal.php';
class projectcontroller{
    public function projet(){
        $sql = "SELECT * FROM projet";
        $db=config::getConnexion();
        try {
            $liste=$db->query($sql);
            return $liste;
        }
        catch(Exception $e){
            die("error: ".$e->getMessage());}

}


public function addproject($project) {
    $sql = "INSERT INTO projet (nom_projet, descriptionn, domaine, date_creation, besoin)
            VALUES (:nom_projet, :descriptionn, :domaine, :date_creation, :besoin)";
    
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':nom_projet', $project->get_nomp());
        $query->bindValue(':descriptionn', $project->get_desc());
        $query->bindValue(':domaine', $project->get_domaine());
        $query->bindValue(':date_creation', $project->get_date());
        $query->bindValue(':besoin', $project->get_besoin());
        
        $query->execute();
    } catch (Exception $e) {
        die('Erreur lors de l\'ajout : ' . $e->getMessage());
    }
}

public function updateproject($project) {
    $sql = "UPDATE projet SET 
                nom_projet = :nom_projet, 
                descriptionn = :descriptionn, 
                domaine = :domaine, 
                date_creation = :date_creation, 
                besoin = :besoin 
            WHERE id_projet = :id_projet";

    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id_projet', $project->get_idp());
        $query->bindValue(':nom_projet', $project->get_nomp());
        $query->bindValue(':descriptionn', $project->get_desc());
        $query->bindValue(':domaine', $project->get_domaine());
        $query->bindValue(':date_creation', $project->get_date());
        $query->bindValue(':besoin', $project->get_besoin());
        $query->execute();
    } catch (Exception $e) {
        die('Erreur lors de la mise à jour : ' . $e->getMessage());
    }
}

public function deleteproject($id) {
    $sql = "DELETE FROM projet WHERE id_projet = :id";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id);
        $query->execute();
    } catch (Exception $e) {
        die('Erreur suppression : ' . $e->getMessage());
    }
}



}
?>