<?php
session_start();
require_once '../PHPpure/connexion.php';
if (isset($_POST['validajout'])) {
    $salle = $_POST['salle'] ?? null;
    $nb_pc = $_POST['nb_pc'] ?? null;
    $nb_places = $_POST['nb_places'] ?? null;
    $description = $_POST['description'] ?? null;
    $typeUse = $_POST['typeUse'] ?? null;


    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../materiel/';
        $fileName = basename($_FILES['photo']['name']);
        $targetFile =  $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photo = basename($targetFile); // nom fichier à stocker en BDD
        } else {
            // gestion erreur upload
            $photo = null;
        }
    } else {
        $photo = null;
    }

    // Vérification des champs requis
    if (!$salle || !$nb_pc || !$nb_places || !$description || !$typeUse || !$photo) {
        $_SESSION['error'] = "Tous les champs sont requis.";
        header("Location: ../PHP/salles.php");
        exit();
    }

    // Correction de la requête SQL
    $sql = "INSERT INTO salle (nom, nb_pc, capacite, description, type, etat, photo) 
            VALUES (:nom, :nb_pc, :capacite, :description, :type, :etat, :photo)";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nom' => $salle,
        ':nb_pc' => $nb_pc,
        ':capacite' => $nb_places,
        ':description' => $description,
        ':type' => $typeUse,
        ':etat' => 'Disponible', // Valeur par défaut pour l'état
        ':photo' => $photo
    ]);

    $_SESSION['success'] = "La salle a été ajoutée avec succès.";
    header("Location: ../PHP/salles.php");
    exit();
}
