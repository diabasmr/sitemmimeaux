<?php
session_start();
require_once('connexion.php');

// Check session
if (!isset($_SESSION['user']['id'])) {
    echo json_encode([]);
    exit();
}
    $sql = "SELECT 
    mois,
    SUM(enseignants) AS enseignants,
    SUM(etudiants) AS etudiants,
    SUM(enseignants + etudiants) AS total
    FROM (
    -- enseignants
    SELECT 
        MONTH(r.date_debut) AS mois,
        COUNT(DISTINCT r.idR) AS enseignants,
        0 AS etudiants
    FROM reservations r
    JOIN concerne c ON r.idR = c.idR
    JOIN reservation_users ru ON r.idR = ru.idR
    JOIN enseignant en ON ru.id = en.id
    WHERE YEAR(r.date_debut) = 2025
    GROUP BY mois

    UNION ALL

    -- étudiants
    SELECT 
        MONTH(r.date_debut) AS mois,
        0 AS enseignants,
        COUNT(DISTINCT r.idR) AS etudiants
    FROM reservations r
    JOIN concerne c ON r.idR = c.idR
    JOIN reservation_users ru ON r.idR = ru.idR
    JOIN etudiant et ON ru.id = et.id
    WHERE YEAR(r.date_debut) = 2025
    GROUP BY mois
    ) AS sous_total
    GROUP BY mois
    ORDER BY mois;
    ";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialise les tableaux avec 10 mois de Septembre (9) à Juin (6)
$mois_map = [9, 10, 11, 12, 1, 2, 3, 4, 5, 6];
$enseignants = $etudiants = $total = array_fill(0, 10, 0); //3 tableaux de 10 zéeos

// Remplir les données extraites
foreach ($result as $row) {
    $index = array_search((int)$row['mois'], $mois_map);
    if ($index !== false) {
        $enseignants[$index] = (int)$row['enseignants'];
        $etudiants[$index] = (int)$row['etudiants'];
        $total[$index] = (int)$row['total'];
    }
}

//Récupère les statuts
$stmt2 = $pdo->prepare("SELECT (SELECT COUNT(idR) FROM reservations WHERE valide = 1) AS acceptee,
(SELECT COUNT(idR) FROM reservations WHERE valide = 2) AS refusee,
(SELECT COUNT(idR) FROM reservations WHERE valide = 0) AS attente,
(SELECT COUNT(idR) FROM reservations WHERE valide = 3) AS terminee;");
$stmt2->execute();
$validation = $stmt2->fetch(PDO::FETCH_ASSOC);

//Récupère les statuts
$stmt3 = $pdo->prepare("SELECT 
  typemateriel,
  SUM(firstyear) AS firstyear,
  SUM(secondyear) AS secondyear,
  SUM(thirdyear) AS thirdyear
FROM (
  -- première années
  SELECT 
      m.typeM AS typemateriel,
      COUNT(DISTINCT r.idR) AS firstyear,
      0 AS secondyear,
      0 AS thirdyear
  FROM reservations r
  JOIN concerne c ON r.idR = c.idR
  JOIN reservation_users ru ON r.idR = ru.idR
  JOIN etudiant en ON ru.id = en.id
  JOIN materiel m ON c.idM = m.idM
  WHERE promotion = 'MMI - 1'
  GROUP BY typemateriel

  UNION ALL

  -- deuxième années
  SELECT 
      m.typeM AS typemateriel,
      0 AS firstyear,
      COUNT(DISTINCT r.idR) AS secondyear,
      0 AS thirdyear
  FROM reservations r
  JOIN concerne c ON r.idR = c.idR
  JOIN reservation_users ru ON r.idR = ru.idR
  JOIN etudiant en ON ru.id = en.id
  JOIN materiel m ON c.idM = m.idM
  WHERE promotion = 'MMI - 2'
  GROUP BY typemateriel

  UNION ALL

  -- troisième années
  SELECT 
      m.typeM AS typemateriel,
      0 AS firstyear,
      0 AS secondyear,
      COUNT(DISTINCT r.idR) AS thirdyear
  FROM reservations r
  JOIN concerne c ON r.idR = c.idR
  JOIN reservation_users ru ON r.idR = ru.idR
  JOIN etudiant en ON ru.id = en.id
  JOIN materiel m ON c.idM = m.idM
  WHERE promotion = 'MMI - 3'
  GROUP BY typemateriel
) AS sub
GROUP BY typemateriel
ORDER BY typemateriel;
");

$stmt3->execute();
$usagepromo = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Initialise les tableaux avec 10 mois de Septembre (9) à Juin (6)
$type_map = ["Accessoire",
          "Vidéo",
          "Audio",
          "Drone",
          "AR/VR",
          "Graphisme"];
$firstyear = $secondyear = $thirdyear = array_fill(0, count($type_map), 0); //3 tableaux de 7 zéros

// Remplir les données extraites
foreach ($usagepromo as $row2) {
    $index = array_search($row2['typemateriel'], $type_map);
    if ($index !== false) {
        $firstyear[$index] = (int)$row2['firstyear'];
        $secondyear[$index] = (int)$row2['secondyear'];
        $thirdyear[$index] = (int)$row2['thirdyear'];
    }
}


$sql = "SELECT 
    salle,
    SUM(enseignantsS) AS enseignantsS,
    SUM(firstyearS) AS firstyearS,
    SUM(secondyearS) AS secondyearS,
    SUM(thirdyearS) AS thirdyearS
    FROM (
    -- enseignants
    SELECT 
        s.nom AS salle,
        COUNT(DISTINCT r.idR) AS enseignantsS,
        0 AS firstyearS,
        0 AS secondyearS,
        0 AS thirdyearS
    FROM reservations r
    JOIN concerne_salle cs ON r.idR = cs.idR
    JOIN reservation_users ru ON r.idR = ru.idR
    JOIN salle s ON cs.idS= s.idS
    JOIN enseignant en ON ru.id = en.id
    GROUP BY salle

    UNION ALL

    -- étudiants
    -- première années
  SELECT 
      s.nom AS salle,
      COUNT(DISTINCT r.idR) AS firstyearS,
      0 AS enseignantsS,
      0 AS secondyearS,
      0 AS thirdyearS
  FROM reservations r
  JOIN concerne_salle cs ON r.idR = cs.idR
  JOIN reservation_users ru ON r.idR = ru.idR
  JOIN etudiant en ON ru.id = en.id
  JOIN salle s ON cs.idS= s.idS
  WHERE promotion = 'MMI - 1'
  GROUP BY salle

  UNION ALL

  -- deuxième années
  SELECT 
      s.nom AS salle,
      0 AS firstyearS,
      COUNT(DISTINCT r.idR) AS secondyearS,
      0 AS thirdyearS,
      0 AS enseignantsS
  FROM reservations r
  JOIN concerne_salle cs ON r.idR = cs.idR
  JOIN reservation_users ru ON r.idR = ru.idR
  JOIN etudiant en ON ru.id = en.id
  JOIN salle s ON cs.idS= s.idS
  WHERE promotion = 'MMI - 2'
  GROUP BY salle

  UNION ALL

  -- troisième années
  SELECT 
      s.nom AS salle,
      0 AS firstyearS,
      0 AS secondyearS,
      0 AS enseignantsS, 
      COUNT(DISTINCT r.idR) AS thirdyearS
  FROM reservations r
  JOIN concerne_salle cs ON r.idR = cs.idR
  JOIN reservation_users ru ON r.idR = ru.idR
  JOIN etudiant en ON ru.id = en.id
  JOIN salle s ON cs.idS= s.idS
  WHERE promotion = 'MMI - 3'
  GROUP BY salle
) AS sub
GROUP BY salle
ORDER BY salle;
    ";

$stmt4 = $pdo->prepare($sql);
$stmt4->execute();
$salle = $stmt4->fetchAll(PDO::FETCH_ASSOC);

// Initialise les tableaux avec 10 mois de Septembre (9) à Juin (6)
$salle_map = ["Salle 138", "Salle 212"];
$enseignantsS = $firstyearS = $secondyearS = $thirdyearS = array_fill(0, count($salle_map), 0);

// Remplir les données extraites
foreach ($salle as $row3) {
    $index = array_search($row3['salle'], $salle_map);
    if ($index !== false) {
        $enseignantsS[$index] = (int)$row3['enseignantsS'];
        $firstyearS[$index] = (int)$row3['firstyearS'];
        $secondyearS[$index] = (int)$row3['secondyearS'];
        $thirdyearS[$index] = (int)$row3['thirdyearS'];
    }
}

//Récupère les matériaux les plus utilisés
$stmt5 = $pdo->prepare("SELECT m.idM, m.designation, COUNT(c.idM) AS nb_concernes FROM materiel m JOIN concerne c ON c.idM = m.idM GROUP BY m.idM, m.designation ORDER BY nb_concernes DESC LIMIT 3;");
$stmt5->execute();
$plusutilise = $stmt5->fetchAll(PDO::FETCH_ASSOC);
//Récupère les périodes d'utilisation
$idM_list = array_column($plusutilise, 'idM');

$placeholders = implode(',', array_fill(0, count($idM_list), '?'));

$stmt6 = $pdo->prepare("
    SELECT c.idM, ROUND(TIME_TO_SEC(TIMEDIFF(r.date_fin, r.date_debut)) / 3600, 2) AS temps_moyen
    FROM reservations r
    JOIN concerne c ON c.idR = r.idR
    WHERE c.idM IN ($placeholders)
");

$stmt6->execute($idM_list);
$tempsmoyens = $stmt6->fetchAll(PDO::FETCH_ASSOC);

print_r($tempsmoyens);
echo json_encode([
    "enseignants" => $enseignants,
    "etudiants" => $etudiants,
    "total" => $total,
    "validation" => $validation,
    "firstyear" => $firstyear,
    "secondyear" => $secondyear,
    "thirdyear" => $thirdyear,
    "firstyearS" => $firstyearS,
    "secondyearS" => $secondyearS,
    "thirdyearS" => $thirdyearS,
    "enseignantsS" => $enseignantsS,
    "materiel1" => $plusutilise[0]['designation'],
    "utilisation1" =>$tempsmoyens[0]['temps_moyen'],
    "materiel2" => $plusutilise[1]['designation'],
    "utilisation2" =>$tempsmoyens[1]['temps_moyen'],
    "materiel3" => $plusutilise[2]['designation'],
    "utilisation3" =>$tempsmoyens[2]['temps_moyen']
]);
    ?>