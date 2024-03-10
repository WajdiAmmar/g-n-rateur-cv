<?php

class Utilisateur {
    private $nom;
    private $prenom;
    private $email;
    private $telephone;
    private $adresse;
    private $photoBase64;
    private $description;
    private $experience;
    private $formation;
    private $competences;
    private $langues;
    private $centresInteret;




    // Méthodes setters
    public function setNom($nom) {
        $this->nom = $nom;
    }

    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setTelephone($telephone) {
        $this->telephone = $telephone;
    }

    public function setAdresse($adresse) {
        $this->adresse = $adresse;
    }
    // Méthode pour définir les données de l'image base64
    public function setPhotoBase64($photoBase64) {
    $this->photoBase64 = $photoBase64;
    }
    public function setDescription($description) {
        $this->description = $description;
    }

    public function setExperience($experience) {
        $this->experience = $experience;
    }

    public function setFormation($formation) {
        $this->formation = $formation;
    }

    public function setCompetences($competences) {
        $this->competences = $competences;
    }


    public function setLangues($langues) {
        $this->langues = $langues;
    }

    public function setCentresInteret($centresInteret) {
        $this->centresInteret = $centresInteret;
    }
    

    // Méthodes getters
    public function getNom() {
        return $this->nom;
    }

    public function getPrenom() {
        return $this->prenom;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getTelephone() {
        return $this->telephone;
    }

    public function getAdresse() {
        return $this->adresse;
    }
     // Méthode pour récupérer les données de l'image base64
     public function getPhotoBase64() {
        return $this->photoBase64;
    }
    public function getDescription() {
        return $this->description;
    }

    public function getExperience() {
        return $this->experience;
    }

    public function getFormation() {
        return $this->formation;
    }

    public function getCompetences() {
        return $this->competences;
    }


    public function getLangues() {
        return $this->langues;
    }

    public function getCentresInteret() {
        return $this->centresInteret;
    }
   

    // Méthode d'hydratation
    public function hydrate(array $donnees) {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }
}
?>

