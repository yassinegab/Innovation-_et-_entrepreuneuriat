<?php
class suivi {

    private $id_suivi;
    private $id_projet;
    private $etat;
    private $commentaire;
    private $date_suivi;
    private $taux_avancement;
    private $tache;  // ✅ Ajout de l’attribut tache

    public function __construct($id_suivi, $id_projet, $etat, $commentaire, $date_suivi, $taux_avancement, $tache) {
        $this->id_suivi = $id_suivi;
        $this->id_projet = $id_projet;
        $this->etat = $etat;
        $this->commentaire = $commentaire;
        $this->date_suivi = $date_suivi;
        $this->taux_avancement = $taux_avancement;
        $this->tache = $tache;  // ✅ Ajout au constructeur
    }

    public function get_id_suivi() {
        return $this->id_suivi;
    }

    public function get_id_projet() {
        return $this->id_projet;
    }

    public function get_etat() {
        return $this->etat;
    }

    public function get_commentaire() {
        return $this->commentaire;
    }

    public function get_date_suivi() {
        return $this->date_suivi;
    }

    public function get_taux_avancement() {
        return $this->taux_avancement;
    }

    public function get_tache() {  // ✅ Getter pour tache
        return $this->tache;
    }

    public function set_id_suivi($id_suivi) {
        $this->id_suivi = $id_suivi;
    }

    public function set_id_projet($id_projet) {
        $this->id_projet = $id_projet;
    }

    public function set_etat($etat) {
        $this->etat = $etat;
    }

    public function set_commentaire($commentaire) {
        $this->commentaire = $commentaire;
    }

    public function set_date_suivi($date_suivi) {
        $this->date_suivi = $date_suivi;
    }

    public function set_taux_avancement($taux_avancement) {
        $this->taux_avancement = $taux_avancement;
    }

    public function set_tache($tache) {  // ✅ Setter pour tache
        $this->tache = $tache;
    }
}
?>
