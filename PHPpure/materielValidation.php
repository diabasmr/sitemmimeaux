<?php
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $idM = $_POST['idM'] ?? null;
        if (!$idM) {
            die('ID réservation manquant');
        }

        // Récupérer l'image actuelle
        $sql = "SELECT photo FROM materiel WHERE idM = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idM]);
        $oldImage = $stmt->fetch(PDO::FETCH_ASSOC);

        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../materiel/';
            $fileName = basename($_FILES['photo']['name']);
            $targetFile = $fileName;
        
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photo = basename($targetFile); // nom fichier à stocker en BDD
            } else {
                // gestion erreur upload
                if ($oldImage) {
                    $photo = $oldImage['photo'];
                }else{
                    $photo = 'rien';
                }
            }
        } else {
            if ($oldImage) {
                $photo = $oldImage['photo'];
            }else{
                $photo = 'rien';
            }
        }

        $designation = $_POST['designation'];
        $date_achat = $_POST['date_achat'];
        $quantite  = $_POST['quantite'];
        $descriptif  = $_POST['descriptif'];
        $type  = $_POST['type'];
        $etat  = $_POST['etat'];
        $lien_demo  = $_POST['lien_demo'];

        $sql = "UPDATE materiel SET photo = :img, designation = :designation, dateAchat = :date_achat, quantité = :quantite, descriptif = :descriptif, typeM = :type, etat = :etat, lien_demo = :lien_demo  WHERE idM = :idM";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':img' => $photo,
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
    }
    header('Location: ../PHP/listeDuMateriel.php'); //pour eviter le reload
        exit;
}
