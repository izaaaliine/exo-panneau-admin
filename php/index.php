<?php
session_start();

// Initialisation de la session si elle n'existe pas encore
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        "identifiant" => "Izaline",
        "motDePasse" => "1234",
        "nom" => "Dhalluin",
        "prenom" => "Izaline",
        "age" => 25,
        "role" => "Apprenante"
    ];
}

$message = "";
$settingsForm = "";

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
        $_SESSION['is_logged_in'] = false;
        $message = '<div class="disconnect"><p>Mot de passe ou Identifiant incorrect</p></div>';
    }
}

// Vérifier si l'utilisateur est connecté
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];

// Traitement de la déconnexion
if (isset($_GET['page']) && $_GET['page'] == 'logout') {
    // Détruire la session
    session_destroy();

    // Rediriger vers la page d'accueil
    header('Location: ?page=home');
    exit;
}

// Vérifier si l'utilisateur est connecté après la déconnexion
$isLoggedIn = isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'];

// Récupérer les valeurs actuelles de l'utilisateur
$currentNom = isset($_SESSION['user']['nom']) ? $_SESSION['user']['nom'] : '';
$currentPrenom = isset($_SESSION['user']['prenom']) ? $_SESSION['user']['prenom'] : '';
$currentAge = isset($_SESSION['user']['age']) ? $_SESSION['user']['age'] : '';
$currentRole = isset($_SESSION['user']['role']) ? $_SESSION['user']['role'] : '';

// Vérifier si l'utilisateur est connecté pour afficher les paramètres
if ($isLoggedIn && isset($_GET['page']) && $_GET['page'] == 'settings') {
    // Traitement de la modification des paramètres
    if (isset($_POST['update'])) {
        // Mettez à jour les paramètres ici
        $_SESSION['user']['nom'] = $_POST['nom'];
        $_SESSION['user']['prenom'] = $_POST['prenom'];
        $_SESSION['user']['age'] = $_POST['age'];
        $_SESSION['user']['role'] = $_POST['role'];

        // Ajouter un drapeau pour indiquer que les paramètres ont été mis à jour
        $_SESSION['user']['updated'] = true;

        $message = '<div class="update-success"><p>Paramètres mis à jour avec succès</p></div>';
    }

    // Afficher le formulaire de modification des paramètres
    $settingsForm = '
        <form method="POST">
            <label for="nom">Nom</label>
            <input type="text" id="nom" name="nom" value="' . htmlspecialchars($currentNom) . '">

            <label for="prenom">Prénom</label>
            <input type="text" id="prenom" name="prenom" value="' . htmlspecialchars($currentPrenom) . '">

            <label for="age">Âge</label>
            <input type="text" id="age" name="age" value="' . htmlspecialchars($currentAge) . '">

            <label for="role">Rôle</label>
            <input type="text" id="role" name="role" value="' . htmlspecialchars($currentRole) . '">

            <input type="submit" name="update" value="Mettre à jour">
        </form>
    ';
}

// Vérifier si l'utilisateur n'est pas connecté et tente d'accéder à certaines pages
if (!$isLoggedIn && (isset($_GET['page']) && ($_GET['page'] == 'settings' || $_GET['page'] == 'user'))) {
    $message2 = '<div class="pleaseconnect"><p>Veuillez vous connecter pour accéder à ce service</p></div>';
}

// Vérifier si la page est "Accueil" et si les informations ont été modifiées
if (isset($_GET['page']) && $_GET['page'] == 'home' && isset($_SESSION['user']['updated']) && $_SESSION['user']['updated']) {
    $message = '<div class="update-success"><p>Informations mises à jour. Bienvenue ' . htmlspecialchars($currentPrenom) . ' ' . htmlspecialchars($currentNom) . '</p></div>';
    
    // Réinitialiser le drapeau
    $_SESSION['user']['updated'] = false;
}
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
            <?php if ($isLoggedIn) { ?>
                <ul>
                    <a href="?page=home"><li class="user">Accueil</li></a>
                    <a href="?page=user"><li class="user">Utilisateur</li></a>
                    <a href="?page=settings"><li class="parametres">Paramètres</li></a>
                    <a href="?page=logout"><li class="deconnexion">Déconnexion</li></a>
                </ul>
            <?php } else { ?>
                <ul>
                    <a href="?page=home"><li class="user">Accueil</li></a>
                    <a href="?page=user"><li class="user">Utilisateur</li></a>
                    <a href="?page=settings"><li class="parametres">Paramètres</li></a>
                    <a href="?page=connect"><li class="connexion">Connexion</li></a>
                </ul>
            <?php } ?>
        </div>

        <?php if (!$isLoggedIn) { ?>
            <div class="form">
                <form method="POST">
                    <label for="identifiant">Identifiant</label>
                    <input type="text" id="identifiant" name="identifiant">

                    <label for="motDePasse">Mot de passe</label>
                    <input type="password" id="motDePasse" name="motDePasse">

                    <input type="submit" name="submit" value="Se connecter">
                </form>
            </div>
        <?php } ?>

        <div class="message">
            <?php
            // Afficher la div du message après le formulaire
            if ($isLoggedIn && isset($_GET['page']) && ($_GET['page'] == 'home' || $_GET['page'] == 'settings')) {
                echo $message;

                // Afficher le message de bienvenue sur la page "Accueil"
                if ($_GET['page'] == 'home') {
                    echo '<div class="welcome"><p>Bienvenue ' . htmlspecialchars($currentPrenom) . ' ' . htmlspecialchars($currentNom) . '</p></div>';
                }
            } elseif ($isLoggedIn && isset($_GET['page']) && $_GET['page'] == 'user') {
                // Afficher les informations de l'utilisateur
                echo $message; // Inclure le message de bienvenue
                echo '<div class="user-info">';
                echo '<p>Nom : ' . htmlspecialchars($currentNom) . '</p>';
                echo '<p>Prénom : ' . htmlspecialchars($currentPrenom) . '</p>';
                echo '<p>Âge : ' . htmlspecialchars($currentAge) . '</p>';
                echo '<p>Rôle : ' . htmlspecialchars($currentRole) . '</p>';
                echo '</div>';
            } else {
                // Afficher la div du message après le formulaire
                if (!empty($message2)) {
                    echo $message2;
                } elseif (!empty($message)) {
                    echo $message;
                }
            }

            // Afficher le formulaire de modification des paramètres
            if (!empty($settingsForm)) {
                echo $settingsForm;
            }
            ?>
        </div>
    </div>
</body>
</html>
