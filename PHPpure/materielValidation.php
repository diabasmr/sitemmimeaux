<?php
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit'])) {
        $idM = $_POST['idM'] ?? null;
        if (!$idM) {
            die('ID réservation manquant');
        }

        // Récupérer l'image actuelle
        $sql = "SELECT photo FROM materiel WHERE idM = ? LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idM]);
        $oldImage = $stmt->fetch(PDO::FETCH_ASSOC);

        // Valeur par défaut
        $newImage = $oldImage['photo'] ?? null;

        $targetDir = "../materiel/";
        $inputName = "image";

        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $originalName = $_FILES[$inputName]['name'];

            // Comparer avec l'image existante
            if ($originalName !== $oldImage['photo']) {
                $extension = pathinfo($originalName, PATHINFO_EXTENSION);
                $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '', pathinfo($originalName, PATHINFO_FILENAME));
                $newName = $safeName . '_image_' . uniqid() . "." . $extension;
                $targetFile = $targetDir . $newName;

                // Déplacer uniquement si différent
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                   $newImage = $newName;
                   echo $newImage;
                }
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
            ':img' => $newImage,
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
