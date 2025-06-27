<?php
session_start();
require_once("../PHPpure/connexion.php");
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// -- TABLE salle
// CREATE TABLE `salle` (
//   `idS` int(11) NOT NULL AUTO_INCREMENT,
//   `nom` varchar(100) NOT NULL,
//   `type` varchar(50) DEFAULT NULL,
//   `capacite` int(11) DEFAULT NULL,
//   `photo` varchar(100) DEFAULT NULL,
//   `etat` varchar(50) DEFAULT NULL,
//   `description` varchar(200) DEFAULT NULL,
//   PRIMARY KEY (`idS`)
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

// -- TABLE d'association salle ↔ réservation
// CREATE TABLE `concerne_salle` (
//   `idS` int(11) NOT NULL,
//   `idR` int(11) NOT NULL,
//   PRIMARY KEY (`idS`, `idR`),
//   FOREIGN KEY (`idS`) REFERENCES `salle` (`idS`) ON DELETE CASCADE ON UPDATE CASCADE,
//   FOREIGN KEY (`idR`) REFERENCES `reservations` (`idR`) ON DELETE CASCADE ON UPDATE CASCADE
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

// -- EXEMPLES DE DONNÉES
// INSERT INTO `salle` (`nom`, `type`, `capacite`, `photo`, `etat`, `description`) VALUES
// ('Salle A101', 'Amphi', 100, 'a101.jpg', 'Disponible', 'Grand amphithéâtre'),
// ('Salle B204', 'Réunion', 20, 'b204.jpg', 'Disponible', 'Salle de réunion équipée');

// -- Exemple de lien avec une réservation (à adapter selon tes id existants)
// -- INSERT INTO `concerne_salle` (`idS`, `idR`) VALUES (1, 2);


if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
    $date = $_POST['selected-date'];
    $date_demande = $_POST['date_demande'];
    $horaireD = $_POST['horaireD'];
    $horaireF = $_POST['horaireF'];
    $motif = $_POST['motif'];
    $signature = $_POST['signature'];
    $userId = $_SESSION["user"]["id"];
    $user_ids = $_POST['user_ids'];
    $commentaire = "rien";
    $document = "rien";
    $salle = $_POST['salle'];
    $valid = $_SESSION['user']['role'] == 'Enseignant(e)' ? 1 : 0;

    // creer date debut = date + horaire debut et date fin = date + horaire fin
    $dateDebut = $date . " " . $horaireD;
    $dateFin = $date . " " . $horaireF;

    if (!isset($_POST['acceptation'])) {
        $_SESSION['error'] = "Veuillez accepter les conditions.";
        header("Location: ../PHP/reservation_salle.php");
        exit();
    }
    if ($horaireD >= $horaireF) {
        $_SESSION['error'] = "Veuillez entrer un créneau d'horaire valide.";
        header("Location: ../PHP/reservation_salle.php");
        exit();
    } //JS

    // Vérifie que les champs ne sont pas vides
    if (empty($date) || empty($date_demande) || empty($horaireD) || empty($horaireF) || empty($motif) || empty($signature)) {
        $_SESSION['error'] = "Tous les champs sont requis.";
        header("Location: ../PHP/reservation_salle.php");
        exit();
    }

    // Insertion de la réservation
    $requete = $pdo->prepare("INSERT INTO reservations (date_demande, date_debut, date_fin, valide, motif, commentaires, signatureElectronique, documentAdministrateur) VALUES (?, ?, ?,  ?, ?, ?, ?, ?)");
    $requete->execute([$date_demande, $dateDebut, $dateFin, $valid, $motif, $commentaire, $signature, $document]);

    // Récupérer l'ID de la réservation créée
    $idReservation = $pdo->lastInsertId();

    // Insérer l'utilisateur dans la réservation

    foreach ($user_ids as $userId) {
        $requete = $pdo->prepare("INSERT INTO reservation_users (id, idR ) VALUES (?, ?)");
        $requete->execute([$userId, $idReservation]);
    }

    // Insérer la salle dans la réservation
    $requete = $pdo->prepare("INSERT INTO concerne_salle (idR, idS) VALUES (?, ?)");
    $requete->execute([$idReservation, $salle]);

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

            $mail->Subject = "Nouvelle réservation d'une salle";
            $mail->Body = "Une nouvelle réservation ReZoom a été enregistrée.\n\nVeuillez en prendre connaissance:\n\nUtilisateur: $Nom $Prenom\nDate: $date - $horaireD:$horaireF\n\nReZoom";

            $mail->send();
        } catch (Exception $e) {
            $_SESSION['error'] = "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            header("Location: ../PHP/reservation_salle.php");
            exit();
        }
    }

    // Insérer la notification pour l'admin
    $requete = $pdo->prepare("INSERT INTO notifications (idR, notif) VALUES (?, 1)");
    $requete->execute([$idReservation]);
    $_SESSION['success'] = 'Votre réservation a été enregistrée'; // Indiquer que la réservation a été réussie
    header("Location: ../PHP/salles.php"); // page de succès
    exit();
}
