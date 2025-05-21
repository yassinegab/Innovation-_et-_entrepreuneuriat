<?php
class Inscription {
    private ?int $id_inscription;
    private ?int $id_eve;
    private ?int $id_uti;
    private ?string $statut;
    private ?string $email;
    
    public function __construct($id_inscription = null, $id_eve = null, $id_uti = null, $statut = null, $email = null) {
        $this->id_inscription = $id_inscription;
        $this->id_eve = $id_eve;
        $this->id_uti = $id_uti;
        $this->statut = $statut;
        $this->email = $email;
    }
    
    // Getters
    public function getIdInscription() { return $this->id_inscription; }
    public function getIdEvenement() { return $this->id_eve; }
    public function getIdUtilisateur() { return $this->id_uti; }
    public function getStatut() { return $this->statut; }
    public function getMail() { return $this->email; }
    
    // Setters
    public function setIdInscription($id_inscription) { $this->id_inscription = $id_inscription; }
    public function setIdEvenement($id_eve) { $this->id_eve = $id_eve; }
    public function setIdUtilisateur($id_uti) { $this->id_uti = $id_uti; }
    public function setStatut($statut) { $this->statut = $statut; }
    public function setMail($email) { $this->email = $email; }
}
?>
