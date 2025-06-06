<?php
session_start();
require_once('connexion.php');

// Check session
if (!isset($_SESSION['user']['id'])) {
    echo json_encode([]);
    exit();
}

$userId = $_SESSION['user']['id'];
$role = $_SESSION['user']['role'] ?? 'Etudiant(e)';

$reservationIds = [];

if ($role !== 'Administrateur') {
    // Récupère les idR où l'utilisateur est dedans
    $stmt = $pdo->prepare("SELECT idR FROM reservation_users WHERE id = :userId");
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $reservationIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    // Si aucune résa = on retourne vide
    if (empty($reservationIds)) {
        echo json_encode([]);
        exit();
    }

    $placeholders = implode(',', array_fill(0, count($reservationIds), '?'));
    $sql = "
        SELECT 
            r.idR,
            r.date_debut AS start,
            r.date_fin AS end,
            m.designation AS title,
            u.avatar,
            'materiel' as type
        FROM reservations r
        JOIN concerne c ON r.idR = c.idR
        JOIN materiel m ON c.idM = m.idM
        JOIN reservation_users ru ON r.idR = ru.idR
        JOIN user_ u ON ru.id = u.id
        WHERE r.valide = 1 AND r.idR IN ($placeholders)
        UNION ALL
        SELECT 
            r.idR,
            r.date_debut AS start,
            r.date_fin AS end,
            s.nom AS title,
            u.avatar,
            'salle' as type
        FROM reservations r
        JOIN concerne_salle cs ON r.idR = cs.idR
        JOIN salle s ON cs.idS = s.idS
        JOIN reservation_users ru ON r.idR = ru.idR
        JOIN user_ u ON ru.id = u.id
        WHERE r.valide = 1 AND r.idR IN ($placeholders)
        ORDER BY start
    "; // IMPORTANT : il faut passer deux fois les mêmes params (pour chaque IN)
    $params = array_merge($reservationIds, $reservationIds);

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
} else {
    // admin : récupère tout
    $sql = "
        SELECT 
            r.idR,
            r.date_debut AS start,
            r.date_fin AS end,
            m.designation AS title,
            u.avatar,
            'materiel' as type
        FROM reservations r
        JOIN concerne c ON r.idR = c.idR
        JOIN materiel m ON c.idM = m.idM
        JOIN reservation_users ru ON r.idR = ru.idR
        JOIN user_ u ON ru.id = u.id
        WHERE r.valide = 1
        UNION ALL
        SELECT 
            r.idR,
            r.date_debut AS start,
            r.date_fin AS end,
            s.nom AS title,
            u.avatar,
            'salle' as type
        FROM reservations r
        JOIN concerne_salle cs ON r.idR = cs.idR
        JOIN salle s ON cs.idS = s.idS
        JOIN reservation_users ru ON r.idR = ru.idR
        JOIN user_ u ON ru.id = u.id
        WHERE r.valide = 1
        ORDER BY start
    ";
    $stmt = $pdo->query($sql);
}

// Regroupe les avatars par réservation
$events = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $key = $row['start'] . $row['title'];

    if (!isset($events[$key])) {
        $events[$key] = [
            'idR' => $row['idR'],
            'title' => $row['title'],
            'start' => $row['start'],
            'end' => $row['end'],
            'type' => $row['type'],
            'avatars' => []
        ];
    }

    $events[$key]['avatars'][] = $row['avatar'] ?: "../uploads/default.png";
}

echo json_encode(array_values($events));
