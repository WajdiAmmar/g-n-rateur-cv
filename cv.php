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
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
if (isset($_POST['color'])) {
    $selectedColor = $_POST['color'];}
  
    
    $backgroundStyle = "";
    if ($selectedColor == "#2B2B2B") {
        $backgroundStyle = "background-color: #0774BB;";
    } else if ($selectedColor == "#1da5c0")   {
        // Sinon, utiliser la couleur choisie
        $backgroundStyle = "background-color: #123b50;";
    }
    else if ($selectedColor == "#ba475a")
    {
        $backgroundStyle = "background-color:#ff6c6e ;";
    }
// Créer une instance de l'objet Utilisateur
$utilisateur = new Utilisateur();

// Hydrater l'objet Utilisateur avec les données du formulaire
$utilisateur->hydrate($_POST);

// Créer une instance du gestionnaire d'utilisateurs
$utilisateurManager = new Manager($pdo);

$utilisateurManager->insererCouleurTemplate($selectedColor);

// Insérer l'utilisateur dans la base de données
$utilisateurManager->inserer($utilisateur);

$utilisateurDernier = $utilisateurManager->recuperer();

if (!empty($utilisateurDernier)) {
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <style>
            body {
                font-family: "Arial", sans-serif;
                background-color: #f4f4f4;
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }
            header {
                background-color: #f4f4f4;
                color: #fff;
                padding: 10px;
                text-align: right;
            }
            .container {
                max-width: 750px;
                margin: 20px auto;
                padding: 20px;
                background-color: #fff;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                display: flex;
                flex-wrap: wrap;
                justify-content: space-between;
            }
            .left-section {
                flex: 1;
                background-color:  <?php echo $selectedColor; ?>;
                color: #fff;
                padding: 40px;
                margin-bottom: 20px;
            }
            .right-section {
                flex: 1;
                background-color:  ;
                margin-bottom: 20px;
            }
            .profile-picture {
                text-align: center;
                margin-bottom: 20px;
            }
            .profile-picture img {
                width: 200px;
                height: 200px;
                border-radius: 50%;
            }
            .contact-info {
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
                font-weight: bolder;
            }
            .contact-info ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            section {
                margin-bottom: 20px;
                padding-bottom: 20px;
            }
            h2 {
                padding-bottom: 10px;
            }
            ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            li {
                margin-bottom: 15px;
            }
            strong {
                font-weight: bold;
            }
            .s1 {
                padding: 10px;
                width: 420px;
                margin-bottom: 30px;
               <?php echo  $backgroundStyle; ?>;
                color: #ffffff;
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
            .s2 {
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
                font-weight: bolder;
            }
            .s3 {
                font-family: Comic Sans MS, Comic Sans, cursive;
                font-size: 15px;
                text-align: left;
                font-weight: bolder;
                margin-left: 15px;
            }
            .cv {
                display: flex;
                justify-content: flex-end;
            }
            .btn {
                display: inline-block;
                padding: 10px 20px;
                background-color: #02818c;
                color: white;
                text-decoration: none;
                font-size: 20px;
                margin-top: 10px;
                margin-right: 10px;
                border: none;
                border-radius: 10px;
                cursor: pointer;
                box-shadow: 8px 8px 10px #919090;
            }

            .btn:hover {
                background-color: hsl(189, 63%, 76%);
            }
        </style>
    </head>
    <body>
        <header>
            <form action="telecharger.php" method="post">
                <input class="btn" type="submit" value="Télécharger PDF">
            </form>
        </header>
        <div class="container">
            <div class="cv">
                <div class="left-section">
                    <section class="profile-picture">
                    <img src="data:image/jpeg;base64,<?= isset($utilisateurDernier['photoBase64']) ? $utilisateurDernier['photoBase64'] : '' ?>" alt="Photo de profil">

                    </section>
                    <section>
                        <ul class="contact-info">
                            <li>Email : <?= $utilisateurDernier['email'] ?></li>
                            <li>Téléphone : <?= $utilisateurDernier['telephone'] ?></li>
                            <li>Adresse : <?= $utilisateurDernier['adresse'] ?></li>
                        </ul>
                    </section>
                    <section class="s2">
                        <h2>Formation</h2>
                        <ul>
                            <li><?= $utilisateurDernier['formation'] ?></li>
                        </ul>
                    </section>
                    <section class="s2">
                        <h2>Langues</h2>
                        <ul>
                            <li><?= $utilisateurDernier['langues'] ?></li>
                        </ul>
                    </section>
                    <section class="s2">
                        <h2>Centres d'intérêt</h2>
                        <ul>
                            <li><?= $utilisateurDernier['centresInteret'] ?></li>
                        </ul>
                    </section>
                </div>
                <div class="right-section">
                    <section class="s1">
                        <p><?= $utilisateurDernier['nom'] . ' ' . $utilisateurDernier['prenom'] ?></p>
                        <p id="p2">Description</p>
                        <p id="p1"><?= $utilisateurDernier['description'] ?></p>
                    </section>
                    <section class="s3">
                        <h2>Compétences</h2>
                        <ul>
                            <li><?= $utilisateurDernier['competences'] ?></li>
                        </ul>
                    </section>
                    <section class="s3">
                        <h2>Expérience Professionnelle</h2>
                        <ul>
                            <li><?= $utilisateurDernier['experience'] ?></li>
                        </ul>
                    </section>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    echo "Aucun utilisateur trouvé.";
}
?>
