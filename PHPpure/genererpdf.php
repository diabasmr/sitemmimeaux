<?php
require('./fpdf/fpdf.php');
require_once('./connexion.php');

class PDF extends FPDF
{
    function Header()
    {
        $this->Image('../IMG/logo.png', 5, 5, 30);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(80);
        $this->Cell(30, 30, 'Confirmation de reservation', 0, 0, 'C');
        $this->Ln(20);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

if (isset($_GET['idR'])) {
    $idR = $_GET['idR'];

    $sql = "SELECT r.*, 
            GROUP_CONCAT(DISTINCT CONCAT(m.idM, ':', m.designation, ':', m.refernceM) SEPARATOR '||') as materiels, 
            GROUP_CONCAT(DISTINCT CONCAT(s.idS, ':', s.nom, ':', s.type) SEPARATOR '||') as salles,
            GROUP_CONCAT(DISTINCT CONCAT(u.id, ':', u.nom, ':', u.prenom) SEPARATOR '||') as users
            FROM reservations r
            LEFT JOIN concerne c ON r.idR = c.idR
            LEFT JOIN materiel m ON c.idM = m.idM
            LEFT JOIN concerne_salle cs ON r.idR = cs.idR
            LEFT JOIN salle s ON cs.idS = s.idS
            LEFT JOIN reservation_users ru ON r.idR = ru.idR
            LEFT JOIN user_ u ON ru.id = u.id
            WHERE r.idR = ?
            GROUP BY r.idR";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$idR]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reservation) {
            $pdf = new PDF();
            $pdf->AliasNbPages();
            $pdf->AddPage();
            $pdf->SetFont('Arial', '', 12);

            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Details de la reservation', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            $pdf->Cell(40, 10, 'Motif :', 0);
            $pdf->Cell(0, 10, $reservation['motif'], 0, 1);

            $pdf->Cell(40, 10, 'Date de debut :', 0);
            $pdf->Cell(0, 10, date('d/m/Y H:i', strtotime($reservation['date_debut'])), 0, 1);
            $pdf->Cell(40, 10, 'Date de fin :', 0);
            $pdf->Cell(0, 10, date('d/m/Y H:i', strtotime($reservation['date_fin'])), 0, 1);

            $status = $reservation['valide'] == 1 ? "Valide" : ($reservation['valide'] == 2 ? "Refuse" : "En attente de validation");
            $pdf->Cell(40, 10, 'Statut :', 0);
            $pdf->Cell(0, 10, $status, 0, 1);

            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Materiel reserve :', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            if ($reservation['materiels']) {
                $materiels = explode('||', $reservation['materiels']);
                foreach ($materiels as $materiel) {
                    list($id, $designation, $reference) = explode(':', $materiel);
                    $pdf->Cell(0, 10, "- $designation ($reference)", 0, 1);
                }
            } else {
                $pdf->Cell(0, 10, "Aucun materiel reserve", 0, 1);
            }

            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Salle reservee :', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            if ($reservation['salles']) {
                $salles = explode('||', $reservation['salles']);
                foreach ($salles as $salle) {
                    list($id, $nom, $type) = explode(':', $salle);
                    $pdf->Cell(0, 10, "- $nom ($type)", 0, 1);
                }
            } else {
                $pdf->Cell(0, 10, "Aucune salle reservee", 0, 1);
            }

            $pdf->Ln(5);
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Reservateurs:', 0, 1);
            $pdf->SetFont('Arial', '', 12);

            if ($reservation['users']) {
                $users = explode('||', $reservation['users']);
                foreach ($users as $user) {
                    list($id, $nom, $prenom) = explode(':', $user);
                    $pdf->Cell(0, 10, "- $prenom $nom", 0, 1);
                }
            }

            if ($reservation['commentaires']) {
                $pdf->Ln(5);
                $pdf->SetFont('Arial', 'B', 12);
                $pdf->Cell(0, 10, 'Commentaires :', 0, 1);
                $pdf->SetFont('Arial', '', 12);
                $pdf->MultiCell(0, 10, $reservation['commentaires']);
            }

            // Génération du PDF
            $pdf->Output('D', 'reservation_' . $idR . '.pdf');
        } else {
            echo "Reservation non trouvee";
        }
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "ID de reservation non specifie";
}