<?php 
class project{
    
    private $id_projet;
    private $nom_projet;
    private $descriptionn;
    private $domaine;
    private $date_creation;
    private $besoin; 
    
    public function __construct($id_projet, $nom_projet, $descriptionn,$domaine,$date_creation,$besoin){

        $this->id_projet = $id_projet;
        $this->nom_projet = $nom_projet;
        $this->descriptionn = $descriptionn;
        $this->domaine = $domaine;
        $this->date_creation = $date_creation;
        $this->besoin = $besoin; }
        
        public function get_idp(){ return $this->id_projet;}
        public function get_nomp(){ return $this->nom_projet;}
        public function get_desc(){ return $this->descriptionn;}
        public function get_domaine(){return $this->domaine;}
        public function get_date(){return $this->date_creation;}
        public function get_besoin(){ return $this->besoin;}
        
        public function set_idp($id_projet)
        {$this->id_projet=$id_projet;}
        public function set_nomp($nom_projet)
        {$this->nom_projet=$nom_projet;}
        public function set_desc($descriptionn)
        {$this->descriptionn=$descriptionn;}
        public function set_domaine($domaine)
        {$this->domaine=$domaine;}
        public function set_date($date_creation)
        {$this->date_creation=$date_creation;}
        public function set_besoin($besoin)
        { $this->besoin=$besoin;}
        
    
}
?>