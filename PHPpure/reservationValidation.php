<?php
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['modifier'])) {
        $idR = $_POST['idR'] ?? null;
        $comment = $_POST['com'] ?? '';
        $status = $_POST['status'] ?? 0;
    
        if (!$idR) {
            die('ID réservation manquant');
        }
    
        // Mettre à jour le statut dans la table reservations
        $sql = "UPDATE reservations SET valide = :status, commentaires = :comment WHERE idR = :idR";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':status' => $status,
            ':comment' => $comment,
            ':idR' => $idR
        ]);
    
        // Récupérer l'idM depuis la table concerne
        $sql1 = "SELECT idM FROM concerne WHERE idR = :idR";
        $stmt = $pdo->prepare($sql1);
        $stmt->execute([':idR' => $idR]);
        $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
    
        // Récupérer la quantite depuis la table reservations
        $sql2 = "SELECT quantite FROM reservations WHERE idR = :idR";
        $stmt = $pdo->prepare($sql2);
        $stmt->execute([':idR' => $idR]);
        $row2 = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($row1 && $row2) {
            $idM = $row1['idM'];
            $quantite = $row2['quantite'];
    
            switch ((int)$status) {
                case 1: // validé : retirer du stock
                    $sqlUpdate = "UPDATE materiel SET quantité = quantité - :quantite WHERE idM = :idM";
                    break;
    
                case 3: // terminé : remettre en stock
                    $sqlUpdate = "UPDATE materiel SET quantité = quantité + :quantite WHERE idM = :idM";
                    break;
    
                default:
                    $sqlUpdate = null;
                    break;
            }
    
            if ($sqlUpdate) {
                $stmt = $pdo->prepare($sqlUpdate);
                $stmt->execute([
                    ':quantite' => $quantite,
                    ':idM' => $idM
                ]);
            }
        }     
        

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

        $sql2 = "UPDATE materiel SET quantité = quantité + 1 WHERE idM = :idM";
        $stmt = $pdo->prepare($sql2);
        $stmt->execute([
            ':idM' => $row['idM']
        ]);

    }
    header('Location: ../PHP/listeDesReservations.php');
        exit;
}
