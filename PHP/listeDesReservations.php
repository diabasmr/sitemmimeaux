<?php
include("../PHPpure/entete.php");
if ($_SESSION['user']['role'] != 'Administrateur') {
    header('Location: ../PHP/index.php');
    exit();
}
require('../PHPpure/connexion.php');
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
        <div class="container search p-3">
            <div class="row align-items-center gy-3">
                <div class="col-12 col-md-auto d-flex align-items-center gap-3 flex-wrap">
                    <p class="fs-6 mb-0 text-nowrap">Consulter l'historique</p>
                    <div class="searchContainer me-5">
                        <input type="search" name="search" id="inputSearch" placeholder="Chercher..." />
                        <button id="buttonSearch">
                            <img src="../res/search.svg" alt="" />
                        </button>
                    </div>
                </div>

                <div class="col-12 d-flex align-items-center gap-3 flex-wrap">
                    <button class="btn text-dark px-3 py-2" style="background-color: pink; border: 1px solid #e4587d; border-radius: 10px;" onclick="window.location.href='reputation.php'">
                        Voir les statistiques
                    </button>

                    <div class="d-flex align-items-center p-2 rounded shadow-sm" style="color: #e4587d; background-color: #fff0f5;">
                        <div class="rounded-circle me-2 flex-shrink-0" style="width: 16px; height: 16px; background-color: #e4587d; border: 1px solid #e4587d;"></div>
                        <span class="fw-semibold"><?php
                                                    $sql1 = "SELECT SUM(notif) AS notifs FROM notifications";
                                                    $stmt = $pdo->prepare($sql1);
                                                    $stmt->execute();
                                                    $notifs = $stmt->fetch(PDO::FETCH_ASSOC);

                                                    $nbNotifs = isset($notifs['notifs']) && $notifs['notifs'] !== null ? (int)$notifs['notifs'] : 0;
                                                    echo htmlspecialchars($nbNotifs) ?> Notifications</span>
                    </div>
                </div>
            </div>
        </div>

        <section class="table mb-5">
            <article class="header_Table">
                <p>Réservation</p>
                <p>Utilisateur</p>
                <p>Demande</p>
                <p>Date de réservation</p>
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
                // Récupération des réservations
                $sql = "SELECT r.*, 
                        GROUP_CONCAT(DISTINCT CONCAT(m.idM, ':', m.designation, ':', m.refernceM, ':', m.photo) SEPARATOR '||') as materiels, 
                        GROUP_CONCAT(DISTINCT CONCAT(s.idS, ':', s.nom, ':', s.type, ':', s.photo) SEPARATOR '||') as salles,
                        GROUP_CONCAT(DISTINCT CONCAT(u.id, ':', u.nom, ':', u.prenom, ':', COALESCE(u.avatar, 'default')) SEPARATOR '||') as users
                        FROM reservations r
                        LEFT JOIN concerne c ON r.idR = c.idR
                        LEFT JOIN materiel m ON c.idM = m.idM
                        LEFT JOIN concerne_salle cs ON r.idR = cs.idR
                        LEFT JOIN salle s ON cs.idS = s.idS
                        LEFT JOIN reservation_users ru ON r.idR = ru.idR
                        LEFT JOIN user_ u ON ru.id = u.id
                        GROUP BY r.idR
                        ORDER BY r.date_demande DESC";

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
                                $status = "Expirée";
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
                                    list($id, $designation, $reference, $photo) = explode(':', $materielStr);
                                    $materiels[] = [
                                        'id' => $id,
                                        'designation' => $designation,
                                        'reference' => $reference,
                                        'photo' => $photo
                                    ];
                                }
                            }

                            // Traitement des salles
                            $salles = [];
                            if ($row['salles']) {
                                $sallesArray = explode('||', $row['salles']);
                                foreach ($sallesArray as $salleStr) {
                                    list($id, $nom, $type, $photo) = explode(':', $salleStr);
                                    $salles[] = [
                                        'id' => $id,
                                        'nom' => $nom,
                                        'type' => $type,
                                        'photo' => $photo
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
                            $sql2 = "SELECT notif FROM notifications WHERE idR = ?";
                            $stmt = $pdo->prepare($sql2);
                            $stmt->execute([$row['idR']]);
                            $notifications = $stmt->fetch(PDO::FETCH_ASSOC);
                            echo '<div class="line mb-5">';
                            if (!empty($salles)) {
                                $salle = $salles[0];
                                if (is_array($notifications) && isset($notifications['notif']) && (int)$notifications['notif'] === 1) { //pour eviter les erreurs dans le cas ou il y a pas de notifs
                                    echo '<div class="d-flex align-items-center mb-2">
                                    <img title="' . htmlspecialchars($salle['nom']) . '" 
                                         src="../materiel/' . htmlspecialchars($salle['photo']) . '" 
                                         alt="Salle" 
                                         class="rounded-circle me-2 flex-shrink-0" 
                                         style="width: 50%; height: auto; object-fit: cover; border: 4px solid #e4587d;">
                                  </div>';
                                } else {
                                    echo '<img title=">' . htmlspecialchars($salle['nom']) . '" src="../materiel/' . htmlspecialchars($salle['photo']) . '" alt="Salle" class="rounded-circle me-2 flex-shrink-0" style="width: 50%; height: auto; object-fit: cover;">';
                                }
                            } else {
                                $mater = $materiels[0];
                                if (is_array($notifications) && isset($notifications['notif']) && (int)$notifications['notif'] === 1) { //pour eviter les erreurs dans le cas ou il y a pas de notifs
                                    echo '<div class="d-flex align-items-center mb-2">
                                    <img title="' . htmlspecialchars($mater['designation']) . '" 
                                         src="../materiel/' . htmlspecialchars($mater['photo']) . '" 
                                         alt="Salle" 
                                         class="rounded-circle me-2 flex-shrink-0" 
                                         style="width: 50%; height: auto; object-fit: cover; border: 4px solid #e4587d;">
                                  </div>';
                                } else {
                                    echo '<img title=">' . htmlspecialchars($mater['designation']) . '" src="../materiel/' . htmlspecialchars($mater['photo']) . '" alt="Salle" class="rounded-circle me-2" style="width: 50%; height: auto;">';
                                }
                            }
                            if (!empty($users)) {
                                $firstUser = $users[0];
                                echo '<p>' . htmlspecialchars($firstUser['nom']) . ' ' . htmlspecialchars($firstUser['prenom']) . '</p>';
                            }
                            echo '<p class="text-center">' . date('d/m/Y H:i', strtotime($row['date_demande'])) . '</p>';
                            echo '<p class="text-center">' . date('d/m/Y H:i', strtotime($row['date_debut'])) . ' - ' .
                                date('d/m/Y H:i', strtotime($row['date_fin'])) . '</p>';
                            if ($status == "En attente") {
                                echo '<p class="p-2 text-center" style="height:45%; color: #f9a308; border: 0.15vw solid #f9a308; border-radius:15px;">' . $status . '</p>';
                            } elseif ($status == "Validée") {
                                echo '<p class="text-center p-2" style="height:45%; color: #356c25; border: 0.15vw solid #356c25; border-radius:15px;">' . $status . '</p>';
                            } elseif ($status == "Refusé") {
                                echo '<p class="text-center p-2" style="height:45%; color: #f9080c; border: 0.15vw solid #f9080c; border-radius:15px;">' . $status . '</p>';
                            } elseif ($status == "Expirée") {
                                echo '<p class="text-center p-2" style="height:45%; color: #707070; border: 0.15vw solid #4b4b4b; border-radius:15px;">' . $status . '</p>';
                            } else {
                                echo '<p class="text-center  p-2" style="height:45%; color: #f9080c; border: 0.15vw solid #f9080c; border-radius:15px;">' . $status . '</p>';
                            }
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
                        </select>
                    </div>
                    <div class="modifPopupReservation_content_body_item">
                        <label for="com">Ajouter un commentaire</label>
                        <input type="text" name="com" id="com" placeholder="Commentaire">
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
                        <button type="button" onclick="document.getElementById('SuppressionPopup').classList.remove('d-none');document.getElementById('SuppressionPopup').classList.add('d-flex');">Supprimer</button>
                        <button type="submit" name="modifier">Modifier</button>
                    </div>
                </div>
            </div>
        </form>

        <div id="SuppressionPopup" class="position-fixed top-0 start-0 w-100 h-100 d-none align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <form action="../PHPpure/reservationValidation.php" method="POST">
                <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
                    <h5 class="mb-3 fw-semibold text-dark">Supprimer la réservation</h5>
                    <p class="text-muted mb-4">Êtes-vous sûre de vouloir supprimer cette réservation&nbsp;?</p>
                    <button type="button" class="btn w-40 text-white" style="background-color: #e47390;" onclick="document.getElementById('SuppressionPopup').classList.add('d-none');">Annuler</button>
                    <button type="submit" name="supprimer" class="w-40 btn text-white"
                        style="background-color: #dc3545;">
                        Supprimer
                    </button>
                </div>
                <input type="hidden" name="idR" value="<?= $row['idR'] ?>" id="idRToDelete">
            </form>
        </div>

    </main>
    <?php if (!empty($error)) : ?>
        <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
                <h5 class="mb-3 fw-semibold text-dark">Erreur</h5>
                <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
            </div>
        </div>
    <?php endif; ?>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/listeDesReservations.js"></script>
</body>

</html>