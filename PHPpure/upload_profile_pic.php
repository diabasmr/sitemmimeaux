<?php
session_start();
require_once "connexion.php";

// vérifier si l'utilisateur est connecté et que son ID est disponible dans la session
if (isset($_SESSION['user']['id'])) {
    // récupérer l'ID de l'utilisateur connecté
    $userId = $_SESSION['user']['id'];

    // vérifier si un fichier est uploadé et si aucune erreur n'a eu lieu
    if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
        // récupérer les informations du fichier uploadé
        $fileTmpPath = $_FILES['avatar']['tmp_name'];
        $fileName = $_FILES['avatar']['name'];
        $fileSize = $_FILES['avatar']['size'];
        $fileType = $_FILES['avatar']['type'];

        // extraire l'extension du fichier
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // types d'extensions autorisées
        $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

        // vérifier si l'extension du fichier est autorisée
        if (in_array($fileExt, $allowedExts)) {
            // vérifier la taille du fichier (limité à 40Mo)
            if ($fileSize < 40 * 1024 * 1024) {
                // créer un répertoire d'upload si nécessaire
                $uploadDir = '../uploads/avatars/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // générer un nom de fichier avec l'ID de l'utilisateur et l'extension
                $newFileName = $userId . '.' . $fileExt;
                $uploadPath = $uploadDir . $newFileName;

                // si le fichier est correctement uploadé
                if (move_uploaded_file($fileTmpPath, $uploadPath)) {
                    // mettre à jour le chemin de l'avatar dans la base de données
                    $sql = "UPDATE user_ SET avatar = :avatar WHERE id = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':avatar' => $uploadPath,
                        ':id' => $userId
                    ]);

                    // recharger les données de l'utilisateur pour mettre à jour la session sans avoir a se reconnecter
                    $stmt = $pdo->prepare("SELECT * FROM user_ WHERE id = :id");
                    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $user['profil'] = $user['avatar'];
                    $_SESSION['user'] = $user;

                    // rediriger vers la page d'accueil
                    header("Location: ../PHP/index.php");
                    exit();
                } else {
                    // si le fichier n'a pas pu étre uploadé
                    echo "Erreur lors du déplacement du fichier.";
                }
            } else {
                // si le fichier est trop lourd
                echo "Fichier trop lourd (max 40Mo).";
            }
        } else {
            // si l'extension du fichier n'est pas autorisée
            echo "Format non autorisé (jpg, jpeg, png, gif).";
        }
    } else {
        // si aucun fichier n'a éte uploadé
        echo "Aucun fichier reçu.";
    }
} else {
    // si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
    echo "Vous devez être connecté.";
    header("Location: ../PHP/connexion.html");
}
