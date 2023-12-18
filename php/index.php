<?php
session_start();

$_SESSION['user'] = ["identifiant" => "Izaline", "motDePasse" => "1234"];
$message = "";


// Vérifier si le formulaire de connexion a été soumis
if (isset($_POST['submit'])) {
    $identifiantSoumis = $_POST['identifiant'];
    $motDePasseSoumis = $_POST['motDePasse'];

    if ($identifiantSoumis == $_SESSION['user']['identifiant'] && $motDePasseSoumis == $_SESSION['user']['motDePasse']) {
        // Connexion réussie
        $_SESSION['is_logged_in'] = true;
        $message = '<div class="connect"><p>Vous êtes connecté</p></div>';
    } else {
        // Connexion échouée
        $_SESSION['is_logged_in'] = false; // Assurez-vous de mettre à jour la session
        $message = '<div class="disconnect"><p>Veuillez rentrer vos identifiants</p></div>';
    }
}
$message2 = '<div class="pleaseconnect"><p>Veuillez vous connecter pour accéder à ce service </p></div>';

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="formulaire">
        <div class="navBar">
            <ul>
                <?php if ($isLoggedIn) { ?>
                    <li class="accueil"><a href="page?home">Accueil</a></li>
                    <li class="user"><a href="page?user">Utilisateur</a></li>
                    <li class="parametres"><a href="page?setting">Paramètres</a></li>
                    <li class="deconnexion"><a href="page?logout">Déconnexion</a></li>
                <?php } else { ?>
                    <li class="accueil"><a href="page?home">Accueil</a></li>
                    <li class="user"><a href="page?user">Utilisateur</a></li>
                    <li class="parametres"><a href="page?setting">Paramètres</a></li>
                    <li class="connexion"><a href="page?connect">Connexion</a></li>

                <?php } ?>
            </ul>
        </div>
        <div class="form">
            <form method="POST">
                <label for="identifiant">Identifiant</label>
                <input type="text" id="identifiant" name="identifiant">

                <label for="motDePasse">Mot de passe</label>
                <input type="password" id="motDePasse" name="motDePasse">

                <input type="submit" name="submit" value="Se connecter">
            </form>
        </div>

        <?php
        // Afficher la div du message après le formulaire
         if (!empty($message2)) {
            echo $message2;
        } elseif (!empty($message)) {
            echo $message;
        }
        ?>
    </div>
</body>
</html>
