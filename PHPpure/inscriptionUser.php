<?php
session_start();
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $nom = trim($_POST['nom']);
    echo $nom;
    $prenom = trim($_POST['prenom']);
    echo $prenom;
    $pseudo = trim($_POST['pseudo']);
    echo $pseudo;
    $date_naissance = trim($_POST['date_naissance']);
    echo $date_naissance;
    $adresse = trim($_POST['adresse']);
    echo $adresse;
    $email = trim($_POST['email']);
    echo $email;
    $mdp = trim($_POST['mdp']);
    echo $mdp;
    $confirme_mdp = trim($_POST['confirme_mdp']);
    echo $confirme_mdp;


    // Date d'inscription
    $date_inscription = date('Y-m-d');

    // Par défaut, le compte n'est pas validé (valable = 0)
    $valable = 0;

    // Vérification que tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($date_naissance) || empty($email) || empty($mdp) || empty($confirme_mdp)) {
        $error = "Veuillez remplir tous les champs";
        exit;
    }

    // Vérification si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM user_ WHERE email = :email");
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetch()) {
        $error = "Cette adresse email est déjà utilisée.";
        exit;
    }

    // Vérification si le pseudo existe déjà
    $stmt = $pdo->prepare("SELECT * FROM user_ WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetch()) {
        $error = "Cet utilisateur existe déjà.";
        exit;
    }

    // Hashage mdp
    $mot_de_passe_hash = password_hash($mdp, PASSWORD_DEFAULT);

    try {
        // Début de la transaction
        $pdo->beginTransaction();

        // Insertion dans la table user_
        $sql = "INSERT INTO user_ (nom, prenom, pseudo, email, mot_de_passe,   valable, date_de_naissance, adresse) 
                VALUES (:nom, :prenom, :pseudo, :email, :mot_de_passe,  :valable, :date_de_naissance, :adresse)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nom' => $nom,
            'prenom' => $prenom,
            'pseudo' => $pseudo,
            'email' => $email,
            'mot_de_passe' => $mot_de_passe_hash,
            'valable' => $valable,
            'date_de_naissance' => $date_naissance,
            'adresse' => $adresse
        ]);

        // Récupération de l'ID de l'utilisateur nouvellement créé
        $userId = $pdo->lastInsertId();

        // Validation de la transaction
        $pdo->commit();
        header('Location: ../PHP/inscription_confirme.html');
        exit();
    } catch (Exception $e) {
        // En cas d'erreur, annulation de la transaction
        $pdo->rollBack();
        $error = "Une erreur est survenue lors de l\'inscription : " . $e->getMessage();
        exit;
    }
}
