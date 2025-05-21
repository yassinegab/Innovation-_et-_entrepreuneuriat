<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../modal/suivimodal.php';

class suivicontroller {

    public function getAllSuivis() {
        $sql = "SELECT * FROM suivi_projet";
        $db = config::getConnexion();
        try {
            $liste = $db->query($sql);
            return $liste;
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }

    public function addSuivi($suivi) {
        $sql = "INSERT INTO suivi_projet (id_projet, etat, commentaire, date_suivi, taux_avancement, tache)
                VALUES (:id_projet, :etat, :commentaire, :date_suivi, :taux_avancement, :tache)";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_projet', $suivi->get_id_projet());
            $query->bindValue(':etat', $suivi->get_etat());
            $query->bindValue(':commentaire', $suivi->get_commentaire());
            $query->bindValue(':date_suivi', $suivi->get_date_suivi());
            $query->bindValue(':taux_avancement', $suivi->get_taux_avancement());
            $query->bindValue(':tache', $suivi->get_tache());
            $query->execute();
        } catch (Exception $e) {
            die('Erreur lors de l\'ajout du suivi : ' . $e->getMessage());
        }
    }

    public function deleteSuivi($id_suivi) {
        $sql = "DELETE FROM suivi_projet WHERE id_suivi = :id";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id', $id_suivi);
            $query->execute();
        } catch (Exception $e) {
            die('Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function updateSuivi($suivi) {
        $sql = "UPDATE suivi_projet SET
                    etat = :etat,
                    commentaire = :commentaire,
                    date_suivi = :date_suivi,
                    taux_avancement = :taux_avancement,
                    tache = :tache
                WHERE id_suivi = :id_suivi";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_suivi', $suivi->get_id_suivi());
            $query->bindValue(':etat', $suivi->get_etat());
            $query->bindValue(':commentaire', $suivi->get_commentaire());
            $query->bindValue(':date_suivi', $suivi->get_date_suivi());
            $query->bindValue(':taux_avancement', $suivi->get_taux_avancement());
            $query->bindValue(':tache', $suivi->get_tache());
            $query->execute();
        } catch (Exception $e) {
            die('Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }

    public function getSuivisByProjet($id_projet) {
        $sql = "SELECT * FROM suivi_projet WHERE id_projet = :id_projet ORDER BY date_suivi DESC";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_projet', $id_projet);
            $query->execute();
            return $query->fetchAll();
        } catch (Exception $e) {
            die("Erreur lors de la récupération des suivis : " . $e->getMessage());
        }
    }

    public function getProjectsWithSuivis() {
        $sql = "SELECT 
    p.id_projet,
    p.nom_projet,
    p.domaine,
    p.date_creation,
    p.besoin,
    p.id_user,
    s.etat,
    s.date_suivi,
    s.commentaire,
    s.taux_avancement
FROM projet p
LEFT JOIN suivi_projet s ON p.id_projet = s.id_projet
ORDER BY p.id_projet, s.date_suivi;
";
        
        $db = config::getConnexion();
        try {
            $result = $db->query($sql);
            return $result->fetchAll();
        } catch (Exception $e) {
            die("Erreur lors de la récupération : " . $e->getMessage());
        }
    }

    public function getSuiviById($id_suivi) {
        $sql = "SELECT * FROM suivi_projet WHERE id_suivi = :id_suivi";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_suivi', $id_suivi, PDO::PARAM_INT);
            $query->execute();
            return $query->fetch();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
    public function deleteAllSuivisByProject($id_projet) {
        $sql = "DELETE FROM suivi_projet WHERE id_projet = :id_projet";
        $db = config::getConnexion();
        try {
            $query = $db->prepare($sql);
            $query->bindValue(':id_projet', $id_projet);
            $query->execute();
        } catch (Exception $e) {
            die('Erreur suppression suivis : ' . $e->getMessage());
        }
    }
    
}
?>
