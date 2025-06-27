<?php
session_start();
require_once '../PHPpure/connexion.php';
if (isset($_POST['validmodifier'])) {
    $idS = $_POST['idS'] ?? null;
    $nb_pc = $_POST['nb_pc'] ?? null;
    $nb_places = $_POST['nb_places'] ?? null;
    $description = $_POST['description'] ?? null;
    $typeUse = $_POST['typeUse'] ?? null;
    $etat = $_POST['etat'] ?? null;
    if (!$idS) {
        $_SESSION['error'] = "ID salle manquant";
        header("Location: ../PHP/salles.php");
        exit();
    }

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

    // Correction de la requête SQL
    $sql = "UPDATE salle 
            SET nb_pc = :nb_pc, 
                capacite = :nb_places, 
                description = :description, 
                type = :typeUse,
                etat = :etat,
                photo = :photo
            WHERE idS = :idS";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nb_pc' => $nb_pc,
        ':nb_places' => $nb_places,
        ':description' => $description,
        ':typeUse' => $typeUse,
        ':etat' => $etat,
        ':photo' => $photo,
        ':idS' => $idS
    ]);

    $_SESSION['success'] = "La salle a été modifiée avec succès.";
    header("Location: ../PHP/salles.php");
    exit();
}
if (isset($_POST['supprimersalle'])) {
    $idS = $_POST['idS'] ?? null;
    if (!$idS) {
        $_SESSION['error'] = "ID salle manquant";
        header("Location: ../PHP/salles.php");
        exit();
    }

    // Correction de la requête SQL
    $sql = "DELETE FROM salle WHERE idS = :idS";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':idS' => $idS]);

    $_SESSION['success'] = "La salle a été supprimée avec succès.";
    header("Location: ../PHP/salles.php");
    exit();
}
