<?php
session_start();
require_once("../PHPpure/connexion.php");

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
    $salle = "Salle " . $_POST['salle'];
    $valid = $_SESSION['user']['role'] == 'Etudiant(e)' ? 0 : 1;
    print_r($salle);
    print_r($horaireD);
    print_r($horaireF);

    // recuperer dans salle le id ou le nom est egal a la salle selectionnée
    $requete = $pdo->prepare("SELECT idS FROM salle WHERE nom = ?");
    $requete->execute([$salle]);
    $salle = $requete->fetch();
    $salle = $salle['idS'];


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
    if (empty($date) || empty($horaireD) || empty($horaireF) || empty($motif) || empty($signature)) {
        die("Tous les champs sont requis.");
    }

    // Insertion de la réservation
    $requete = $pdo->prepare("INSERT INTO reservations (date_debut, date_fin, valide, motif, commentaires, signatureElectronique, documentAdministrateur) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $requete->execute([$dateDebut, $dateFin, $valid, $motif, $commentaire, $signature, $document]);

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

    // Modifier la disponiblilite
    $requete = $pdo->prepare("UPDATE salle SET etat = 'Indisponible' WHERE Nom = ?");
    $requete->execute([$salle]);

    header("Location: ../PHP/salles.php"); // page de succès
    exit();
}
