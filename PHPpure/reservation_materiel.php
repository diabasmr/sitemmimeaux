<?php
session_start();
require_once("../PHPpure/connexion.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST['selected-date'];
    $horaireD = $_POST['horaireD'];
    $horaireF = $_POST['horaireF'];
    $motif = $_POST['motif'];
    $signature = $_POST['signature'];
    $userId = $_SESSION["user"]["id"];
    $user_ids = $_POST['user_ids'];
    $commentaire = "rien";
    $document = "rien";
    $materiel_id = $_POST['materiel'];

    // creer date debut = date + horaire debut et date fin = date + horaire fin
    $dateDebut = $date . " " . $horaireD;
    $dateFin = $date . " " . $horaireF;

    if (!isset($_POST['acceptation'])) {
        die("Veuillez accepter les conditions.");
    }
    if ($horaireD >= $horaireF) {
        die("Veuillez entrer un créneau d'horaire valide.");
    } //JS

    // Vérifie que les champs ne sont pas vides
    if (empty($date) || empty($horaireD) || empty($horaireF)  || empty($motif) || empty($signature)) {
        die("Tous les champs sont requis.");
    }

    // Insertion de la réservation
    $requete = $pdo->prepare("INSERT INTO reservations (date_debut, date_fin, valide, motif, commentaires, signatureElectronique, documentAdministrateur) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $requete->execute([$dateDebut, $dateFin, 0, $motif, $commentaire, $signature, $document]);

    // Récupérer l'ID de la réservation créée
    $idReservation = $pdo->lastInsertId();

    // Insérer l'utilisateur dans la réservation
    foreach ($user_ids as $userId) {
        $requete = $pdo->prepare("INSERT INTO reservation_users (id, idR) VALUES (?, ?)");
        $requete->execute([$userId, $idReservation]);
    }

    // Insérer le matériel dans la réservation
    $requete = $pdo->prepare("INSERT INTO concerne (idR, idM) VALUES (?, ?)");
    $requete->execute([$idReservation, $materiel_id]);

    header("Location: ../PHP/reservation_materiel.php?success=1"); // page de succès
    exit();
}
