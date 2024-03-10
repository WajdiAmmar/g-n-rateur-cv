<?php
require_once("connexion.php");

$dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mot_de_passe = trim($_POST['mot_de_passe'] ?? '');

    if (!empty($email) && !empty($mot_de_passe)) {
        $requete = $pdo->prepare("SELECT mot_de_passe FROM comptes_utilisateurs WHERE email = :email");
        $requete->bindParam(':email', $email);
        $requete->execute();
        $resultat = $requete->fetch(PDO::FETCH_ASSOC);

        if ( $mot_de_passe== $resultat['mot_de_passe']) {
            // Authentification réussie, afficher le contenu de form.html
            header('location:form.html');
            exit(); // Assure que le script s'arrête après l'affichage du contenu
        } else {
            echo "Email ou mot de passe incorrect !";
        }
    } else {
        echo "Veuillez fournir une adresse e-mail et un mot de passe.";
    }
}
?>


