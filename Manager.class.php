<?php
class Manager {
    private $pdo;

    // Constructeur prenant l'objet de connexion PDO en paramètre
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Méthode pour insérer un utilisateur dans la base de données
    public function inserer(Utilisateur $utilisateur) {
        // Vérifier si une image a été téléchargée
        if (isset($_FILES['photoBase64']) && $_FILES['photoBase64']['error'] === UPLOAD_ERR_OK) {
            // Lire le contenu de l'image téléchargée
            $imageContent = file_get_contents($_FILES['photoBase64']['tmp_name']);
    
            // Encoder l'image en base64
            $imageBase64 = base64_encode($imageContent);
    
            // Stocker l'image base64 dans l'objet utilisateur
            $utilisateur->setPhotoBase64($imageBase64);
        }
    
        // Requête d'insertion dans la base de données
        $query = "INSERT INTO cv_utilisateurs (nom, prenom, email, telephone, adresse, photoBase64, description, experience, formation, competences,centresInteret,langues) 
                  VALUES (:nom, :prenom, :email, :telephone, :adresse, :photoBase64, :description, :experience, :formation, :competences, :centresInteret, :langues)";
    
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute(array(
                ':nom' => $utilisateur->getNom(),
                ':prenom' => $utilisateur->getPrenom(),
                ':email' => $utilisateur->getEmail(),
                ':telephone' => $utilisateur->getTelephone(),
                ':adresse' => $utilisateur->getAdresse(),
                ':photoBase64' => $utilisateur->getPhotoBase64(),
                ':description' => $utilisateur->getDescription(),
                ':experience' => $utilisateur->getExperience(),
                ':formation' => $utilisateur->getFormation(),
                ':competences' => $utilisateur->getCompetences(),
                ':centresInteret' => $utilisateur->getCentresInteret(),
                ':langues' => $utilisateur->getLangues(),
            ));
        } catch(PDOException $e) {
            echo "Erreur d'insertion de l'utilisateur : " . $e->getMessage();
        }
    }
    
    // Méthode pour récupérer un utilisateur par son ID
    public function recuperer() {
        $query = "SELECT * FROM cv_utilisateurs ORDER BY id_cv DESC LIMIT 1";
        $statement = $this->pdo->prepare($query);
        try {
            $statement->execute();
            return $statement->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Gérez l'erreur comme vous le souhaitez (log, affichage, etc.)
            echo "Erreur lors de la récupération de l'utilisateur : " . $e->getMessage();
            return false;
        }
    }

    // Méthode pour ajouter un utilisateur (inscription)
    public function ajouter(array $donnees) {
        $sql = "INSERT INTO comptes_utilisateurs (nom, prenom, email, mot_de_passe) VALUES (:nom, :prenom, :email, :mot_de_passe)";
        try {
            $stmt = $this->pdo->prepare($sql);

            // Liaison des valeurs des paramètres avec les valeurs saisies dans le formulaire
            $stmt->bindParam(':nom', $donnees['nom']);
            $stmt->bindParam(':prenom', $donnees['prenom']);
            $stmt->bindParam(':email', $donnees['email']);
            $stmt->bindParam(':mot_de_passe',  $donnees['mot_de_passe']);

            // Exécution de la requête préparée
            $stmt->execute();
            // Affichage d'un message de succès
            echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
        } catch(PDOException $e) {
            // En cas d'erreur, affichage d'un message d'erreur
            echo "Erreur lors de l'inscription: " . $e->getMessage();
        }
    }

    public function verifierAuthentification($email, $motdepasse) {
        $requete = $this->pdo->prepare("SELECT mot_de_passe,email FROM inscri WHERE email = :email");
    
        // Bind des valeurs des paramètres
        $requete->bindvalue(':email', $email);
        // Exécution de la requête préparée
        $requete->execute();
    
        // Récupération des résultats
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] );
            $mot_de_passe = trim($_POST['mot_de_passe'] );
        }
        return ( $mot_de_passe== $resultat['mot_de_passe']);
    }
    function insererCouleurTemplate($couleurTemplate)
    {

            // Requête SQL d'insertion
            $requete = "INSERT INTO template_cv (couleur) VALUES (:couleur)";
            $statement = $this->pdo->prepare($requete);
    
            // Liaison des paramètres
            $statement->bindParam(':couleur', $couleurTemplate, PDO::PARAM_STR);
    
            // Exécution de la requête
            $statement->execute();
    
            // Fermeture de la connexion
            $pdo = null;
    
    
    }
    public function recupererDerniereCouleurTemplate()
    {
 
            $requete = "SELECT couleur FROM template_cv ORDER BY id_template DESC LIMIT 1";
            $statement = $this->pdo->prepare($requete);

            $statement->execute();

            // Récupérer le résultat de la requête
            $resultat = $statement->fetch(PDO::FETCH_ASSOC);
            return $resultat;

    }
}

