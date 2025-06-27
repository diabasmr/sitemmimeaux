<?php
session_start();
require_once("../PHPpure/connexion.php");
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['selected-date'];
    $date_demande = $_POST['date_demande'];
    $horaireD = $_POST['horaireD'];
    $horaireF = $_POST['horaireF'];
    $motif = $_POST['motif'];
    $signature = $_POST['signature'];
    $userId = $_SESSION["user"]["id"];
    $user_ids = $_POST['user_ids'];
    $commentaire = $_POST['commentaire'];
    $motif = $_POST['motif'];
    $document = "rien";
    $materiel_id = $_POST['materiel'];
    $quantite = abs((int)$_POST['quantite']);
    $valid = $_SESSION['user']['role'] == 'Enseignant(e)' ? 1 : 0;

    // creer date debut = date + horaire debut et date fin = date + horaire fin
    $dateDebut = $date . " " . $horaireD;
    $dateFin = $date . " " . $horaireF;
    //motif
    $motif = $motif . " - " . $commentaire;

    // Vérifie que les champs ne sont pas vides
    if (empty($quantite) || empty($date)  || empty($date_demande) || empty($horaireD) || empty($horaireF)  || empty($motif) || empty($signature)) {
        $_SESSION['error'] = "Tous les champs sont requis.";
        header("Location: ../PHP/reservation_materiel.php");
        exit();
    }

    // Insertion de la réservation
    $requete = $pdo->prepare("INSERT INTO reservations (quantite, date_demande, date_debut, date_fin, valide, motif, commentaires, signatureElectronique, documentAdministrateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $requete->execute([$quantite, $date_demande, $dateDebut, $dateFin, $valid, $motif, $commentaire, $signature, $document]);

    // Récupérer l'ID de la réservation créée
    $idReservation = $pdo->lastInsertId();

    // Insérer l'utilisateur dans la réservation
    foreach ($user_ids as $userId) {
        $requete = $pdo->prepare("INSERT INTO reservation_users (id, idR) VALUES (?, ?)");
        $requete->execute([$userId, $idReservation]);
    }

    // Insérer le matériel dans la réservation si prof
    $requete = $pdo->prepare("INSERT INTO concerne (idR, idM) VALUES (?, ?)");
    $requete->execute([$idReservation, $materiel_id]);

    //MAIL
    require '../PHPMailer-master/src/PHPMailer.php';
    require '../PHPMailer-master/src/SMTP.php';
    require '../PHPMailer-master/src/Exception.php';

    $stmt = $pdo->prepare("SELECT * FROM `user_` AS u JOIN `reservation_users` AS ru ON ru.id = u.id WHERE ru.idR = ?");
    $stmt->execute([$idReservation]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $email = $user['email'];
        $Pseudo = $user['pseudo'];
        $Nom = $user['nom'];
        $Prenom = $user['prenom'];

        // Envoi de l'e-mail avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'iut.rezoom@gmail.com';
            $mail->Password = 'veta utze kwrk elbf';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('iut.rezoom@gmail.com', 'ReZoom Support');
            $mail->addAddress('iut.rezoom@gmail.com', 'ReZoom Support');
            $mail->addReplyTo($email, "$Nom $Prenom");

            $mail->Subject = 'Nouvelle réservation de matériel';
            $mail->Body = "Une nouvelle réservation ReZoom a été enregistrée.\n\nVeuillez en prendre connaissance:\n\nUtilisateur: $Nom $Prenom\nDate: $date - $horaireD:$horaireF\n\nReZoom";

            $mail->send();
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            header("Location: ../PHP/reservation_materiel.php");
            exit();
        }
    }

    // Insérer la notification pour l'admin
    $requete = $pdo->prepare("INSERT INTO notifications (idR, notif) VALUES (?, 1)");
    $requete->execute([$idReservation]);
    $_SESSION['success'] = 'Votre réservation a été enregistrée'; // Indiquer que la réservation a été réussie
    header("Location: ../PHP/materiels.php"); // page de succès 
    exit();
}
