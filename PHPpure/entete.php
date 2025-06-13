<?php
// creation de la session de l'utilisateur
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: ../PHP/connexion-compte.php");
    exit();
}

// si l'utilisateur a cocher la case "se souvenir de moi"
if ($_SESSION['user']['rememberMe'] == true) {



    // detruire la session si l'utilisateur n'a pas interagis avec le site depuis 3jours pour 30jours
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 2592000)) {
        session_destroy();
        header("Location: ../PHP/connexion-compte.php");
        exit();
    }
} else {
    // detruire la session si l'utilisateur n'a pas interagis avec le site depuis 900 secondes pour 15min
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 900)) {
        session_destroy();
        header("Location: ../PHP/connexion-compte.php");
        exit();
    }
}

$_SESSION['last_activity'] = time();
