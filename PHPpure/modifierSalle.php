<?php
if (isset($_POST['validmodifier'])) {
    $idS = $_POST['idS'] ?? null;
    $nb_pc = $_POST['nb_pc'] ?? null;
    $nb_places = $_POST['nb_places'] ?? null;
    $description = $_POST['description'] ?? null;
    $typeUse = $_POST['typeUse'] ?? null;

    if (!$idR || !$idS) {
        $_SESSION['error'] = "ID réservation ou salle manquant";
        header("Location: ../PHP/listeDesReservations.php");
        exit();
    }

    // Mise à jour de la réservation
    $sql = "UPDATE reservations SET nb_pc = :nb_pc AND nb_places = nb_places AND description = :description AND typeUse = :typeUse WHERE idS = :idS";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':nb_pc' => $nb_pc,
        ':nb_places' => $nb_places,
        ':description' => $description,
        ':typeUse' => $typeUse,
        ':idS' => $idS
    ]);

    // Notification pour l'utilisateur
    $_SESSION['success'] = "La réservation a été modifiée avec succès.";
    header("Location: ../PHP/listeDesReservations.php");
    exit();
}
