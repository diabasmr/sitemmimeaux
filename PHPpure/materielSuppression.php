<?php
session_start();
require_once('connexion.php');
if (isset($_POST['supprimer'])) {
    $idM = $_POST['idM'] ?? null;

    if (!$idM) {
        $_SESSION['error'] = "ID réservation manquant";
        header("Location: ../PHP/listeDuMateriel.php");
    }

    $sql = "DELETE FROM favori_materiel WHERE idM = :idM";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idM' => $idM
    ]);

    $sql = "DELETE FROM materiel WHERE idM = :idM";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idM' => $idM
    ]);
    $_SESSION['success'] = "Le matériel a été supprimé.";
}
header('Location: ../PHP/listeDuMateriel.php'); //pour eviter le reload
exit;
