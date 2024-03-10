<?php
require_once("connexion.php");
function chargerClasse($classname)
{
    require $classname . '.class.php';
}

spl_autoload_register('chargerClasse');

$dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion à la base de données réussie !";
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$utilisateurManager = new Manager($pdo);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    
    // Vérifiez ici si les champs sont vides ou non, ou effectuez d'autres validations selon vos besoins
    
    $donnees = array(
        'nom' => $nom,
        'prenom' => $prenom,
        'email' => $email,
        'mot_de_passe' => $mot_de_passe
    );

    $utilisateurManager->ajouter($donnees);
 
    header('location:accueil.html');
    
}

