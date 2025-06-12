<?php
include("../PHPpure/entete.php");
if ($_SESSION['user']['role'] != 'Administrateur') {
    header('Location: ../PHP/index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/index.css" />
    <link rel="stylesheet" href="../CSS/tabReservation.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
    <link rel="stylesheet" href="../CSS/modifPopupReservation.css" />
    <title>Liste des réservations</title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main>
        <h1>Réservations</h1>
        <div class="search">
        <p class="fs-6 me-5">Consulter l'historique</p>
        <div class="searchContainer">
            <input type="search" name="search" id="inputSearch" placeholder="Chercher..." />
            <button id="buttonSearch">
                <img src="../res/search.svg" alt="" />
            </button>
        </div>
    </div>
        <section class="table mb-5">
            <article class="header_Table">
                <p>Matériel/Salle</p>
                <p>Utilisateur</p>
                <p>Date</p>
                <p>Statut</p>

            </article>
            <article id="tab" class="body_Table pb-5">
                <!-- <div class="line">
                    <p>Nom de la reservation</p>
                    <p>07/02/2025</p>
                    <p>Non défini</p>
                    <button class="modifier"></button>
                </div>
                <div class="line">
                    <p>Nom de la reservation</p>
                    <p>07/02/2025</p>
                    <p>Non défini</p>
                    <button class="modifier"></button>
                </div>
                <div class="line">
                    <p>Nom de la reservation</p>
                    <p>07/02/2025</p>
                    <p>Non défini</p>
                    <button class="modifier"></button>
                </div>
                <div class="line">
                    <p>Nom de la reservation</p>
                    <p>07/02/2025</p>
                    <p>Non défini</p>
                    <button class="modifier"></button>
                </div> -->
                <!-- pareil mais avec les reservations des materiels ou des salles -->
                <?php
                require_once('../PHPpure/connexion.php');

                // Récupération des réservations
                $sql = "SELECT r.*, 
                        GROUP_CONCAT(DISTINCT CONCAT(m.idM, ':', m.designation, ':', m.refernceM) SEPARATOR '||') as materiels, 
                        GROUP_CONCAT(DISTINCT CONCAT(s.idS, ':', s.nom, ':', s.type) SEPARATOR '||') as salles,
                        GROUP_CONCAT(DISTINCT CONCAT(u.id, ':', u.nom, ':', u.prenom, ':', COALESCE(u.avatar, 'default')) SEPARATOR '||') as users
                        FROM reservations r
                        LEFT JOIN concerne c ON r.idR = c.idR
                        LEFT JOIN materiel m ON c.idM = m.idM
                        LEFT JOIN concerne_salle cs ON r.idR = cs.idR
                        LEFT JOIN salle s ON cs.idS = s.idS
                        LEFT JOIN reservation_users ru ON r.idR = ru.idR
                        LEFT JOIN user_ u ON ru.id = u.id
                        GROUP BY r.idR
                        ORDER BY r.date_debut DESC";

                try {
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            $now = new DateTime();
                            $end = new DateTime($row['date_fin']);
                            if ($row['valide'] == 0 && $end < $now) {
                                $status = "Annulé";
                                $sql = "UPDATE reservations SET valide = 2 WHERE idR = :idR";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':idR', $row['idR'], PDO::PARAM_INT);
                                $stmt->execute();
                            } elseif ($end < $now) {
                                $status = "Terminé";
                                $sql = "UPDATE reservations SET valide = 3 WHERE idR = :idR";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindParam(':idR', $row['idR'], PDO::PARAM_INT);
                                $stmt->execute();
                            } elseif ($row['valide'] == 1) {
                                $status = "Validée";
                            } else if ($row['valide'] == 2) {
                                $status = "Refusée";
                            } else {
                                $status = "En attente";
                            }

                            // Traitement des matériels
                            $materiels = [];
                            if ($row['materiels']) {
                                $materielsArray = explode('||', $row['materiels']);
                                foreach ($materielsArray as $materielStr) {
                                    list($id, $designation, $reference) = explode(':', $materielStr);
                                    $materiels[] = [
                                        'id' => $id,
                                        'designation' => $designation,
                                        'reference' => $reference
                                    ];
                                }
                            }

                            // Traitement des salles
                            $salles = [];
                            if ($row['salles']) {
                                $sallesArray = explode('||', $row['salles']);
                                foreach ($sallesArray as $salleStr) {
                                    list($id, $nom, $type) = explode(':', $salleStr);
                                    $salles[] = [
                                        'id' => $id,
                                        'nom' => $nom,
                                        'type' => $type
                                    ];
                                }
                            }

                            // Traitement des utilisateurs
                            $users = [];
                            if ($row['users']) {
                                $usersArray = explode('||', $row['users']);
                                foreach ($usersArray as $userStr) {
                                    list($id, $nom, $prenom, $avatar) = explode(':', $userStr);
                                    $users[] = [
                                        'id' => $id,
                                        'nom' => $nom,
                                        'prenom' => $prenom,
                                        'avatar' => $avatar === 'default' ? "../uploads/default.png" : $avatar
                                    ];
                                }
                            }

                            echo '<div class="line mb-3">';
                            if (!empty($salles)) {
                                $salle = $salles[0];
                                echo '<p>' . htmlspecialchars($salle['nom']) . '</p>';
                            } else {
                                $mater = $materiels[0];
                                echo '<p>' . htmlspecialchars($mater['designation']) . '</p>';
                            }
                            if (!empty($users)) {
                                $firstUser = $users[0];
                                echo '<p>' . htmlspecialchars($firstUser['nom']) . ' ' . htmlspecialchars($firstUser['prenom']) . '</p>';
                            }
                            echo '<p class="text-center">' . date('d/m/Y H:i', strtotime($row['date_debut'])) . ' - ' .
                                date('d/m/Y H:i', strtotime($row['date_fin'])) . '</p>';
                                if($status == "En attente"){
                                    echo '<p class="p-2 text-center" style="color: #f9a308; border: 0.15vw solid #f9a308; border-radius:15px;">' . $status . '</p>';
                                }
                                elseif($status == "Validée"){
                                    echo '<p class="text-center p-2" style="color: #356c25; border: 0.15vw solid #356c25; border-radius:15px;">' . $status . '</p>';
                                }
                                elseif($status == "Refusé"){
                                    echo '<p class="text-center p-2" style="color: #f9080c; border: 0.15vw solid #f9080c; border-radius:15px;">' . $status . '</p>';
                                }
                                elseif($status == "Terminé"){
                                    echo '<p class="text-center p-2" style="color: #707070; border: 0.15vw solid #4b4b4b; border-radius:15px;">' . $status . '</p>';
                                }
                                else{echo '<p class="text-center  p-2" style="color: #f9080c; border: 0.15vw solid #f9080c; border-radius:15px;">' . $status . '</p>';}
                            echo '<button class="modifier" data-id="' . $row['idR'] . '" 
                                    data-motif="' . htmlspecialchars($row['motif']) . '"
                                    data-date-debut="' . date('Y-m-d\TH:i', strtotime($row['date_debut'])) . '"
                                    data-date-fin="' . date('Y-m-d\TH:i', strtotime($row['date_fin'])) . '"
                                    data-status="' . $row['valide'] . '"
                                    data-materiels=\'' . json_encode($materiels) . '\'
                                    data-salles=\'' . json_encode($salles) . '\'
                                    data-users=\'' . json_encode($users) . '\'></button>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="line"><p>Aucune réservation trouvée</p></div>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="line"><p>Erreur : ' . $e->getMessage() . '</p></div>';
                }

                $pdo = null;
                ?>
            </article>
        </section>
        <form class="modifPopupReservation" action="../PHPpure/reservationValidation.php" method="POST">
            <div class="modifPopupReservation_content">
                <div class="modifPopupReservation_content_header">
                    <h3>Modifier la réservation</h3>
                    <button class="close_modifPopupReservation">
                        <img src="../res/x.svg" alt="close">
                    </button>
                </div>
                <input type="hidden" name="idR" id="idR">
                <div class="modifPopupReservation_content_body">
                    <div class="modifPopupReservation_content_body_item">
                        <label for="motif">Motif de la réservation</label>
                        <input type="text" id="motif" placeholder="Motif" disabled>
                    </div>
                    <div class="modifPopupReservation_content_body_item">
                        <label for="date_debut">Date de début</label>
                        <input type="datetime-local" id="date_debut" placeholder="Date de début" disabled>
                    </div>
                    <div class="modifPopupReservation_content_body_item">
                        <label for="date_fin">Date de fin</label>
                        <input type="datetime-local" id="date_fin" placeholder="Date de fin" disabled>
                    </div>
                    <div class="modifPopupReservation_content_body_item">
                        <label for="status">Statut</label>
                        <select name="status" id="status">
                            <option value="0" selected>En attente</option>
                            <option value="1">Validée</option>
                            <option value="2">Refusée</option>
                            <option value="3">Terminé</option>
                        </select>
                    </div>
                    <div class="modifPopupReservation_content_body_item">
                        <label for="materiels">Matériels</label>
                        <input type="text" id="materiels" placeholder="Materiels" disabled>
                    </div>
                    <!-- ou -->
                    <div class="modifPopupReservation_content_body_item">
                        <label for="salles">Salles</label>
                        <input type="text" id="sallesinput" placeholder="Salles" disabled>
                    </div>
                    <div class="avatar-container">
                        <label for="avatar">Qui réserve :</label>
                        <div class="avatar-container_img">
                            <img src="../IMG/jinx.png" alt="">
                        </div>
                    </div>
                    <div class="button-container">
                        <button type="submit" class="supprimer" name="supprimer">Supprimer</button>
                        <button type="submit" name="modifier">Modifier</button>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/listeDesReservations.js"></script>
    <script>
        function supprimerReservation() {
            if (confirm("Voulez-vous vraiment supprimer cette réservation ?")) {
                window.location.href = "../PHPpure/supprimer_reservation.php?idR=" + idR;
            }
        }
    </script>
</body>

</html>