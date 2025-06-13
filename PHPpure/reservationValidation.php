<?php
require_once('connexion.php');
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
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
                    $message = "Nous vous informons que votre réservation a été validée.\n\nConsultez le pdf de celle-ci sur votre tableau de bord ReZoom\nNous vous y indiquons les mesures à suivre.";
                    break;
    
                case 3: // terminé : remettre en stock
                    $sqlUpdate = "UPDATE materiel SET quantité = quantité + :quantite WHERE idM = :idM";
                    $message = "Nous vous informons que votre réservation a été marqué comme terminée.\n\nSi vous avez des questions, n'hésitez pas à nous contacter.\n\n";
                    break;
    
                default:
                    $sqlUpdate = null;
                    $message = "Nous vous informons que votre réservation a été refusée ou marquée en attente.\n\nLes raisons possibles peuvent être :\n- Indisponibilité du matériel\n- Demande d’annulation de votre part\n- Autres contraintes organisationnelles\n\nSi vous avez des questions, n'hésitez pas à nous contacter.";
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
                $mail->Username = 'materiel.iut@gmail.com'; // Remplace par ton e-mail Gmail
                $mail->Password = 'obmv hoac gbrw ftwz';     // Utilise un mot de passe d’application
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('materiel.iut@gmail.com', 'IUT Support');
                $mail->addAddress($email, "$Nom $Prenom");
                $mail->addReplyTo('materiel.iut@gmail.com', 'IUT Support');

                $mail->Subject = 'Annulation de votre réservation';
                $mail->Body = "Bonjour $Nom $Prenom,\n\n$message\n\nCordialement,\nL'équipe IUT Meaux";

                $mail->send();

            } catch (Exception $e) {
                echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            }

        }

    } else if (isset($_POST['supprimer'])) {
        $idR = $_POST['idR'] ?? null;

        if (!$idR) {
            die('ID réservation manquant');
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
                $mail->Username = 'materiel.iut@gmail.com'; // Remplace par ton e-mail Gmail
                $mail->Password = 'obmv hoac gbrw ftwz';     // Utilise un mot de passe d’application
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('materiel.iut@gmail.com', 'IUT Support');
                $mail->addAddress($email, "$Nom $Prenom");
                $mail->addReplyTo('materiel.iut@gmail.com', 'IUT Support');

                $mail->Subject = 'Annulation de votre réservation';
                $mail->Body = "Bonjour $Nom $Prenom,\n\nNous vous informons que votre réservation a été annulée.\n\nLes raisons possibles peuvent être :\n- Indisponibilité du matériel\n- Demande d’annulation de votre part\n- Autres contraintes organisationnelles\n\nSi vous avez des questions, n'hésitez pas à nous contacter.\n\nCordialement,\nL'équipe IUT Meaux";

                $mail->send();

            } catch (Exception $e) {
                echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            }

        }

        }
        header('Location: ../PHP/listeDesReservations.php');
            exit;
        }
