<?php
require_once '../PHPpure/connexion.php';

if (isset($_POST['ajouterMateriel'])) {
    // si les champs sont vides mettre un message d'erreur
    if (empty($_POST['date_achat']) || empty($_POST['designation']) || empty($_POST['quantite']) || empty($_POST['descriptif']) || empty($_POST['type']) || empty($_POST['etat']) || empty($_POST['lien_demo'])) {
        echo "Veuillez remplir tous les champs";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/listeDuMateriel.php'; }, 3000);</script>";
        exit();
    }
    //AJOUT PHOTO
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = '../materiel/';
        $fileName = basename($_FILES['photo']['name']);
        $targetFile =  $fileName;
    
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
            $photo = basename($targetFile); // nom fichier à stocker en BDD
        } else {
            // gestion erreur upload
            $photo = null;
        }
    } else {
        $photo = null;
    }
    
    $designation = $_POST['designation'];
    $date_achat = $_POST['date_achat'];
    $quantite  = $_POST['quantite'];
    $descriptif  = $_POST['descriptif'];
    $type  = $_POST['type'];
    $etat  = $_POST['etat'];
    $lien_demo  = $_POST['lien_demo'];


    // si le materiel existe déjà
    $sql = "SELECT * FROM materiel WHERE designation = :designation";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'designation' => $designation
    ]);
    $mater = $stmt->fetch();

    if ($mater) {
        echo "Le matériel existe déjà";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
    } else {
        // Insertion dans materiel
        $sql = "INSERT INTO materiel (designation, photo, dateAchat, quantité, descriptif, typeM, etat, lien_demo)
            VALUES (:designation, :photo, :dateAchat, :quantite, :descriptif, :typeM, :etat, :lien_demo)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'designation' => $designation,
            'photo' => $photo,
            'dateAchat' => $date_achat,
            'quantite' => $quantite,
            'descriptif' => $descriptif,
            'typeM' => $type,
            'etat' => $etat,
            'lien_demo' => $lien_demo
        ]);

        // Récupère l'ID nouvellement inséré
        $lastInsertId = $pdo->lastInsertId();
        $space = 6 - strlen($lastInsertId);
        $fill = str_repeat('0', $space);
        $ref = 'REF' . $fill . $lastInsertId;


        // Insertion de la ref
        $sql3 = "UPDATE materiel SET refernceM = :ref WHERE idM = :id";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([
            'ref' => $ref,
            'id' => $lastInsertId
]);

        header('Location: ../PHP/listeDuMateriel.php');
    }
}
header('Location: ../PHP/listeDuMateriel.php');
