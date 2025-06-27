<?php
require('fpdf186/fpdf.php');
require_once('connexion.php'); // Connexion à la base de données

$idReservation = 1;

$sql = "
SELECT r.idR, r.date_debut, r.date_fin, r.motif, r.commentaires,
       u.nom, u.prenom, u.email,
       GROUP_CONCAT(m.designation SEPARATOR ', ') AS materiels
FROM reservations r
JOIN reservation_users ru ON r.idR = ru.idR
JOIN etudiant e ON ru.id = e.id
JOIN user_ u ON e.id = u.id
JOIN concerne c ON c.idR = r.idR
JOIN materiel m ON m.idM = c.idM
WHERE r.idR = :id
GROUP BY r.idR
";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $idReservation]);
$res = $stmt->fetch();

if (!$res) {
    die("Réservation introuvable");
}

// Création PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Accusé de réception de réservation', 0, 1, 'C');

$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, "Nom : {$res['prenom']} {$res['nom']}", 0, 1);
$pdf->Cell(0, 10, "Email : {$res['email']}", 0, 1);
$pdf->Ln(5);
$pdf->Cell(0, 10, "Réservation n°{$res['idR']}", 0, 1);

$pdf->Ln(5);
$pdf->MultiCell(0, 10, "Matériel : {$res['materiels']}");
$pdf->Cell(0, 10, "Date de début : {$res['date_debut']}", 0, 1);
$pdf->Cell(0, 10, "Date de fin : {$res['date_fin']}", 0, 1);
$pdf->MultiCell(0, 10, "Motif : {$res['motif']}");
$pdf->SetTextColor(255, 0, 0);
$pdf->MultiCell(0, 10, "Commentaires : {$res['commentaires']}");

$pdf->SetTextColor(0, 0, 0);
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 10, "Ce document vous sert de preuve de réservation. Merci d’imprimer ce reçu si besoin.");

$pdf->Output("I", "accuse_reservation_{$res['idR']}.pdf");
