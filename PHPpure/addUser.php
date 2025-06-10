<?php
require_once '../PHPpure/connexion.php';

if (isset($_POST['ajouterUtilisateur'])) {
    // si les champs sont vides mettre un message d'erreur
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['motDePasse'])) {
        echo "Veuillez remplir tous les champs";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
        exit();
    }

    $pseudo = $_POST['prenom'] . '.' . $_POST['nom'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motDePasse = $_POST['motDePasse'];
    $role = $_POST['role'];
    $date_inscription = date('Y-m-d'); // Ajouté
    $valable = 1; // Si tu veux que l'utilisateur soit activé directement


    // si l'utilisateur existe déjà
    $sql = "SELECT * FROM user_ WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'email' => $email
    ]);
    $user = $stmt->fetch();

    $sql2 = "SELECT * FROM user_ WHERE pseudo = :pseudo";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        'pseudo' => $pseudo
    ]);
    $user2 = $stmt2->fetch();

    if ($user || $user2) {
        echo "L'utilisateur existe déjà";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
    } else {
        // Insertion dans user_
        $sql = "INSERT INTO user_ (pseudo, nom, prenom, email, mot_de_passe, date_inscription, valable)
            VALUES (:pseudo, :nom, :prenom, :email, :motDePasse, :date_inscription, :valable)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'pseudo' => $pseudo,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'motDePasse' => $motDePasse,
            'date_inscription' => $date_inscription,
            'valable' => $valable
        ]);

        // Récupère l'ID nouvellement inséré
        $lastInsertId = $pdo->lastInsertId();

        // Insertion dans la table de rôle
        $sql3 = "INSERT INTO $role (id) VALUES (:id)";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([
            'id' => $lastInsertId
        ]);

        header('Location: ../PHP/index.php');
    }
}
?>