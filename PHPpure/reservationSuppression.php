<?php
session_start();
require_once('connexion.php');
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['supprimer'])) {
    $idR = $_POST['idR'] ?? null;

    if (!$idR) {
        $_SESSION['error'] = "ID réservation manquant";
        header("Location: ../PHP/listeDesReservations.php");
        exit();
    }

    //suppression reservation
    $sql = "DELETE FROM reservations WHERE idR = :idR";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':idR' => $idR
    ]);

    //MAIL
    require '../PHPMailer-master/src/PHPMailer.php';
    require '../PHPMailer-master/src/SMTP.php';
    require '../PHPMailer-master/src/Exception.php';

    $stmt = $pdo->prepare("SELECT * FROM `user_` AS u JOIN `reservation_users` AS r ON r.id = u.id WHERE r.idR = ?");
    $stmt->execute([$idR]);
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
            $mail->Password = 'veta utze kwrk elbf'; // Utilise un mot de passe d’application
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('iut.rezoom@gmail.com', 'ReZoom Support');
            $mail->addAddress($email, "$Nom $Prenom");
            $mail->addReplyTo('iut.rezoom@gmail.com', 'ReZoom Support');

            $mail->Subject = 'Annulation de votre réservation';
            $mail->Body = "Bonjour $Nom $Prenom,\n\nNous vous informons que votre réservation a été annulée.\n\nLes raisons possibles peuvent être :\n- Indisponibilité du matériel\n- Demande d’annulation de votre part\n- Autres contraintes organisationnelles\n\nSi vous avez des questions, n'hésitez pas à nous contacter.\n\nCordialement,\nL'équipe ReZoom";

            $mail->send();
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            header("Location: ../PHP/listeDesReservations.php");
            exit();
        }
    }

    //Supprimer la notification pour l'admin
    $requete = $pdo->prepare("DELETE FROM notifications WHERE idR = ?");
    $requete->execute([$idR]);
    $_SESSION['success'] = "La réservation a été supprimée avec succès.";
}
header('Location: ../PHP/listeDesReservations.php');
exit();
