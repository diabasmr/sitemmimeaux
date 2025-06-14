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
    $quantite = abs((int)$_POST['quantite']);
    $valid = $_SESSION['user']['role'] == 'Enseignant(e)' ? 1 : 0;

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
    if (empty($quantite) || empty($date) || empty($horaireD) || empty($horaireF)  || empty($motif) || empty($signature)) {
        die("Tous les champs sont requis.");
    }

    // Insertion de la réservation
    $requete = $pdo->prepare("INSERT INTO reservations (quantite, date_debut, date_fin, valide, motif, commentaires, signatureElectronique, documentAdministrateur) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $requete->execute([$quantite, $dateDebut, $dateFin, $valid, $motif, $commentaire, $signature, $document]);

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

    // Modifier la quantite
    if($valid == 1){
        $requete = $pdo->prepare("UPDATE materiel SET quantité = quantité - ? WHERE idM = ?");
        $requete->execute([$quantite, $materiel_id]);
    }

    header("Location: ../PHP/materiels.php"); // page de succès ??
    exit();
}
