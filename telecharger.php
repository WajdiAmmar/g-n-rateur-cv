<?php
require_once("connexion.php");
require_once 'vendor/autoload.php'; // Chargement de Dompdf

use Dompdf\Dompdf;

function chargerClasse($classname)
{
    require $classname . '.class.php';
}

spl_autoload_register('chargerClasse');

$dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST;

try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

$utilisateurManager = new Manager($pdo);
// Récupération du dernier utilisateur

$utilisateurDernier = $utilisateurManager->recuperer();
$derniercouleur = $utilisateurManager->recupererDerniereCouleurTemplate();

$backgroundStyle = "";
if ($derniercouleur['couleur'] == "#2B2B2B") {
    $backgroundStyle = "background-color: #0774BB;";
} else if ($derniercouleur['couleur'] == "#1da5c0") {
    // Sinon, utiliser la couleur choisie
    $backgroundStyle = "background-color: #123b50;";
} else if ($derniercouleur['couleur'] == "#ba475a") {
    $backgroundStyle = "background-color:#ff6c6e ;";
}

// Vérifier si des données ont été récupérées
if ($utilisateurDernier) {
    // Génération du contenu HTML du CV en utilisant les données de l'utilisateur
    $html = '
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>CV de ' . $utilisateurDernier['nom'] . ' ' . $utilisateurDernier['prenom'] . '</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: #fff;
                font-family: Arial, sans-serif;
            }
            .container {
                width: 794px;
                margin: 0 auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
            .left-section {
                float: left;
                width: 30%;
                background-color:'.$derniercouleur['couleur'].';
                padding: 40px;
                height: 650px;
            }
            .right-section {
                margin-top: 0px;
                float: right;
                width: 50%;
                margin-right: 80px;
            }
            .profile-picture img {
                width: 200px;
                height: 200px;
                border-radius: 50%;
                margin-bottom: 20px;
            }
            .contact-info {
                list-style: none;
                padding: 0;
                margin: 0;
                font-size: 14px;
                text-align: left;
                color: #fff;
            }
            .section {
                margin-bottom: 20px;
                padding-bottom: 20px;
                border-bottom: 1px solid #ccc;
            }
            h2 {
                font-size: 18px;
                margin-bottom: 10px;
            }
            .s1 {
                padding: 10px;
                width: 420px;
                margin-bottom: 30px;
                color:#fff;
                '. $backgroundStyle .';
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 35px;
                text-align: center;
                font-weight: bolder;
            }
            #p1 {
                color: #ffffff;
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
            }
            #p2 {
                color: #ffffff;
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
            }
            .s3 {
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
                font-weight: bolder;
                margin-left: 15px;
            }
            .li {
                color: #ffffff;
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
                font-weight: bolder;
                margin-left: 15px;
            }
            @media print {
                body {
                    margin: 0;
                    padding: 0;
                }
                .container {
                    width: 794px;
                    margin: 0 auto;
                    padding: 20px;
                    box-shadow: none;
                }
                .left-section, .right-section {
                    width: 100%;
                    float: none;
                }
            }
        </style>
    </head>
    <body>
    <div class="container">
        <div class="cv">
            <div class="left-section">
                <div class="profile-picture">
                    <img src="data:image/jpeg;base64,' . $utilisateurDernier['photoBase64'] . '" alt="Photo de profil">
                </div>
                <section>
                    <ul class="contact-info">
                        <li class="li">Email : ' . $utilisateurDernier['email'] . '</li>
                        <li class="li">Téléphone : ' . $utilisateurDernier['telephone'] . '</li>
                        <li class="li">Adresse : ' . $utilisateurDernier['adresse'] . '</li>
                    </ul>
                </section>
                <section class="s2">
                    <h2 class="li">Formation</h2>
                    <ul>
                        <li class="li">' . $utilisateurDernier['formation'] . '</li>
                    </ul>
                </section>
                <section class="s2">
                    <h2 class="li">Langues</h2>
                    <ul>
                        <li class="li">' . $utilisateurDernier['langues'] . '</li>
                    </ul>
                </section>
                <section class="s2">
                    <h2 class="li">Centres d\'intérêt</h2>
                    <ul>
                        <li class="li">' . $utilisateurDernier['centresInteret'] . '</li>
                    </ul>
                </section>
            </div>
            <div class="right-section">
                <section class="s1">
                    <p>' . $utilisateurDernier['nom'] . ' ' . $utilisateurDernier['prenom'] . '</p>
                    <p id="p2">Description</p>
                    <p id="p1">' . $utilisateurDernier['description'] . '</p>
                </section>
                <section class="s3">
                    <h2>Compétences</h2>
                    <ul>
                        <li>' . $utilisateurDernier['competences'] . '</li>
                    </ul>
                </section>
                <section class="s3">
                    <h2>Expérience Professionnelle</h2>
                    <ul>
                        <li>' . $utilisateurDernier['experience'] . '</li>
                    </ul>
                </section>
            </div>
        </div>
    </div>
    </body>
    </html>';

    // Création d'une instance de Dompdf
    $dompdf = new Dompdf();
    $dompdf->loadHtml($html);

    // Rendu du PDF
    $dompdf->render();

    // Téléchargement du PDF
    $dompdf->stream("cv_utilisateur.pdf");
    exit();
} else {
    echo "Aucune donnée d'utilisateur n'a été récupérée.";
}
?>
