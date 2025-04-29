<?php

 include '../../model/evenmodel.php';
include dirname(__DIR__) . '/config.php';


class EvenementController
{

    public function addEvenement($even)
{
    $sql = "INSERT INTO evenement (nom, date, lieu, capacite) 
                VALUES (:nom, :date, :lieu, :capacite)";
    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':nom', $even->getNom());
        $query->bindValue(':date', $even->getDate());
        $query->bindValue(':lieu', $even->getLieu());
        $query->bindValue(':capacite', $even->getCapacite());
        $query->execute();
        echo "okayyyyy";
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
public function listeven()
{
    $db = config::getConnexion();
    $sql = "SELECT * FROM evenement";
    $query = $db->prepare($sql);
    $query->execute();
    $result = $query->fetchAll();
    return $result ?: []; // Retourne un tableau vide si aucun résultat n'est trouvé
}
public function getEventById($id)
{
    $db = config::getConnexion();
    $sql = "SELECT * FROM evenement WHERE id_ev = :id";
    
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id', $id, PDO::PARAM_INT);
        $query->execute();
        $event = $query->fetch(PDO::FETCH_ASSOC);
        return $event ?: null; // Retourne null si aucun événement trouvé
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
        return null;
    }
}


  public function deleteeven($id)
{
    $sql = "DELETE FROM evenement WHERE id_ev= :id";
  $db = config::getConnexion();
  try {
    $query = $db->prepare($sql);
    $query->bindValue(':id', $id);
    $query->execute();
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
}
public function updateven($id, $nom, $date, $lieu, $capacite)
{
    $sql = "UPDATE evenement 
    SET nom = :nom, date = :date, lieu = :lieu, capacite = :capacite 
    WHERE id_ev = :id_ev";

    $db = config::getConnexion();
    try {
        $query = $db->prepare($sql);
        $query->bindValue(':id_ev', $id);
        $query->bindValue(':nom', $nom);
        $query->bindValue(':date', $date);
        $query->bindValue(':lieu', $lieu);
        $query->bindValue(':capacite', $capacite);
        $query->execute();
    } catch (Exception $e) {
        die("Error: " . $e->getMessage());
    }
}
 

}

?>