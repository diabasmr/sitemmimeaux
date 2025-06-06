<?php
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modifier'])) {
        $idR = $_POST['idR'] ?? null;
        $status = $_POST['status'] ?? 0;

        if (!$idR) {
            die('ID réservation manquant');
        }

        $sql = "UPDATE reservations SET valide = :status WHERE idR = :idR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':idR' => $idR
        ]);

        header('Location: ../PHP/listeDesReservations.php');
        exit;
    } else if (isset($_POST['supprimer'])) {
        $idR = $_POST['idR'] ?? null;

        if (!$idR) {
            die('ID réservation manquant');
        }

        $sql = "DELETE FROM reservations WHERE idR = :idR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idR' => $idR
        ]);

        header('Location: ../PHP/listeDesReservations.php');
        exit;
    }
}
