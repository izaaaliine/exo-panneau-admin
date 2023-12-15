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
                <li class="accueil">Accueil</li>
                <li class="user">Utilisateur</li>
                <li class="parametres">Paramètres</li>
                <?php if ($isLoggedIn) { ?>
                    <li class="deconnexion"><a href="?logout">Déconnexion</a></li>
                <?php } else { ?>
                    <li class="connexion"><a href="connexion.php">Connexion</a></li>
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
        if (!empty($message)) {
            echo $message;
        }
        ?>
    </div>
</body>
</html>
