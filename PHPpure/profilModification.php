<?php
require_once('connexion.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_SESSION['user']['id'];
    if (isset($_POST['modifier_nomPrenom'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $sql = "UPDATE user_ SET nom = :nom, prenom = :prenom WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $nom,
            ':prenom' => $prenom,
            ':id' => $id
        ]);
        header('Location: ../PHP/profil.php');
        exit;
    }
    if (isset($_POST['modifier_email'])) {
        $email = $_POST['email'];
        $id = $_SESSION['user']['id'];
        $sql = 'UPDATE user_ SET email = :email WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':id' => $id
        ]);
        header('Location: ../PHP/profil.php');
        exit;
    }

    if (isset($_POST['modifier_tel'])) {
        $tel = $_POST['tel'];
        // enlever la partie +33
        $tel = substr($tel, 3);
        $id = $_SESSION['user']['id'];
        $sql = 'UPDATE user_ SET telephone = :tel WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':tel' => $tel,
            ':id' => $id
        ]);
        header('Location: ../PHP/profil.php');
        exit;
    }
    if (isset($_POST['modifier_password'])) {
        $password = $_POST['password'];
        $newPassword = $_POST['newPassword'];
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        $sql = 'SELECT mot_de_passe FROM user_ WHERE id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':id' => $id
        ]);
        $result = $stmt->fetch();
        if (password_verify($password, $result['mot_de_passe'])) {
            $sql = 'UPDATE user_ SET mot_de_passe = :newPassword WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':newPassword' => $newPasswordHash,
                ':id' => $id
            ]);
            header('Location: ../PHP/profil.php');
            exit;
        } else {
            echo 'Mot de passe incorrect';
        }
    }
    if (isset($_POST['modifier_other'])) {
        if ($_SESSION['user']['role'] == "Etudiant(e)") {
            $adresse = $_POST['adresse'];
            $numeroEtudiant = $_POST['numeroEtudiant'];
            if ($numeroEtudiant != 'Non renseigné') {
                $id = $_SESSION['user']['id'];
                $sql = 'UPDATE user_ SET adresse = :adresse WHERE id = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':adresse' => $adresse,
                    ':id' => $id
                ]);
                $sql1 = 'UPDATE etudiant SET numeroEtudiant = :numeroEtudiant WHERE id = :id';
                $stmt1 = $pdo->prepare($sql1);
                $stmt1->execute([
                    ':numeroEtudiant' => $numeroEtudiant,
                    ':id' => $id
                ]);
                header('Location: ../PHP/profil.php');
                exit;
            } else {
                $sql = 'UPDATE user_ SET adresse = :adresse WHERE id = :id';
                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    ':adresse' => $adresse,
                    ':id' => $id
                ]);
                header('Location: ../PHP/profil.php');
                exit;
            }
        } else {
            $adresse = $_POST['adresse'];
            $id = $_SESSION['user']['id'];
            $sql = 'UPDATE user_ SET adresse = :adresse WHERE id = :id';
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':adresse' => $adresse,
                ':id' => $id
            ]);
            header('Location: ../PHP/profil.php');
            exit;
        }
    }
}
