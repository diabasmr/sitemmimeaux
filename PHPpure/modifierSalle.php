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

    // Correction de la requête SQL
    $sql = "UPDATE salle 
            SET nb_pc = :nb_pc, 
                capacite = :nb_places, 
                description = :description, 
                type = :typeUse,
                etat = :etat
            WHERE idS = :idS";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nb_pc' => $nb_pc,
        ':nb_places' => $nb_places,
        ':description' => $description,
        ':typeUse' => $typeUse,
        ':etat' => $etat,
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
