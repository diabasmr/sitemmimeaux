<?php
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $idM = $_POST['idM'] ?? null;
        $designation = $_POST['designation'];
        $date_achat = $_POST['date_achat'];
        $quantite  = $_POST['quantite'];
        $descriptif  = $_POST['descriptif'];
        $type  = $_POST['type'];
        $etat  = $_POST['etat'];
        $lien_demo  = $_POST['lien_demo'];

        if (!$idM) {
            die('ID réservation manquant');
        }

        $sql = "UPDATE materiel SET designation = :designation, dateAchat = :date_achat, quantité = :quantite, descriptif = :descriptif, typeM = :type, etat = :etat, lien_demo = :lien_demo  WHERE idM = :idM";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':designation' => $designation,
            ':date_achat' => $date_achat,
            ':quantite' => $quantite,
            ':descriptif' => $descriptif,
            ':type' => $type,
            ':etat' => $etat,
            ':lien_demo' => $lien_demo,
            ':idM' => $idM
        ]);
        

        
    } else if (isset($_POST['supprimer'])) {
        $idM = $_POST['idM'] ?? null;

        if (!$idM) {
            die('ID réservation manquant');
        }

        $sql = "DELETE FROM materiel WHERE idM = :idM";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':idM' => $idM
        ]);

        header('Location: ../PHP/listeDuMateriel.php');
        exit;
    }
    header('Location: ../PHP/listeDuMateriel.php'); //pour eviter le reload
        exit;
}
