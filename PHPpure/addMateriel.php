<?php
require_once '../PHPpure/connexion.php';

if (isset($_POST['ajouterMateriel'])) {
    // si les champs sont vides mettre un message d'erreur
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['motDePasse'])) {
        echo "Veuillez remplir tous les champs";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
        exit();
    }

    $photo = $_POST['photo'];
    $designation = $_POST['designation'];
    $date_achat = $_POST['date_achat'];
    $quantite  = $_POST['quantite'];
    $descriptif  = $_POST['descriptif'];
    $type  = $_POST['type'];
    $etat  = $_POST['etat'];
    $lien_demo  = $_POST['lien_demo'];


    // si l'utilisateur existe déjà
    $sql = "SELECT * FROM materiel WHERE designation = :designation";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'designation' => $designation
    ]);
    $mater = $stmt->fetch();

    $sql2 = "SELECT * FROM materiel WHERE typeM = :typeM";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        'typeM' => $type
    ]);
    $mater2 = $stmt2->fetch();

    if ($mater || $mater2) {
        echo "Le matériel existe déjà";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
    } else {
        // Insertion dans user_
        $sql = "INSERT INTO materiel (designation, photo, dateAchat, quantité, descriptif, typeM, etat, lien_demo)
            VALUES (:designation, :photo, :dateAchat, :quantité, :descriptif, :typeM, :etat, :lien_demo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'designation' => $designation,
            'photo' => $photo,
            'dateAchat' => $date_achat,
            'quantité' => $quantite,
            'descriptif' => $descriptif,
            'typeM' => $type,
            'etat' => $etat,
            'lien_demo' => $lien_demo
        ]);

        // Récupère l'ID nouvellement inséré
        $lastInsertId = $pdo->lastInsertId();
        $space = 6 - strlen($lastInsertId);
        $fill = str_repeat('0', $space);
        $ref = 'REF' . $fill . $lastInsertId;


        // Insertion de la ref
        $sql3 = "INSERT INTO materiel (RefernceM) VALUES (:id)";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([
            'id' => $ref
        ]);

        header('Location: ../PHP/index.php');
    }
}
