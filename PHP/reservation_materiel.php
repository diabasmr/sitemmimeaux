<?php include("../PHPpure/entete.php"); ?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Réservation Matériel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- bootstrap  -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/header.css">
    <link rel="stylesheet" href="../CSS/reservation_salle.css">
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>

    <main class="reservation-container mt-5 mb-5 mt-md-auto">
        <form action="../PHPpure/reservation_materiel.php" method="post" class="ms-4 ms-md-auto my-5 mt-md-auto">
            <h1>Procédure de réservation</h1>


            <section class="reservation-content">
                <!-- Colonne de gauche : matériel -->
                <div class="equipment">
                    <!-- <img src="../IMG/canon.jpg" alt="" id="materiel-image">
                    <h2 id="materiel-title">Caméra</h2> -->
                    <?php
                    require_once("../PHPpure/connexion.php");
                    $idM = $_GET['idM'];
                    $sql = "SELECT * FROM materiel WHERE idM = ?";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([$idM]);
                    $materiel = $stmt->fetch();
                    ?>
                    <img src="../materiel/<?php echo $materiel['photo']; ?>" alt="" id="materiel-image">
                    <h2 id="materiel-title"><?php echo $materiel['designation']; ?></h2>
                    <input type="hidden" name="materiel" value="<?php echo $materiel['idM']; ?>">

                    <label for="horaire">Choisir un créneau horaire:</label>
                    <div class="my-4 d-flex justify-content-around">
                        <div class="d-flex justify-content-between align-items-baseline">
                        <label for="horaireD" class="form-label me-2"> De: </label>
                        <select name="horaireD" class="form-select" id="horaireD" required>
                            <option name="horaireD" value="08:00" selected>08:00</option>
                            <option name="horaireD" value="09:00">09:00</option>
                            <option name="horaireD" value="10:00">10:00</option>
                            <option name="horaireD" value="11:00">11:00</option>
                            <option name="horaireD" value="12:00">12:00</option>
                            <option name="horaireD" value="13:00">13:00</option>
                            <option name="horaireD" value="14:00">14:00</option>
                            <option name="horaireD" value="15:00">15:00</option>
                            <option name="horaireD" value="16:00">16:00</option>
                            <option name="horaireD" value="17:00">17:00</option>
                        </select>
                        </div>
                        <div class="d-flex justify-content-between align-items-baseline">
                        <label for="horaireF" class="form-label me-2"> À: </label>
                        <select name="horaireF" class="form-select" id="horaireF" required>
                            <option name="horaireF" value="09:00" selected>09:00</option>
                            <option name="horaireF" value="10:00">10:00</option>
                            <option name="horaireF" value="11:00">11:00</option>
                            <option name="horaireF" value="12:00">12:00</option>
                            <option name="horaireF" value="13:00">13:00</option>
                            <option name="horaireF" value="14:00">14:00</option>
                            <option name="horaireF" value="15:00">15:00</option>
                            <option name="horaireF" value="16:00">16:00</option>
                            <option name="horaireF" value="17:00">17:00</option>
                            <option name="horaireF" value="18:00">18:00</option>
                        </select>
                        </div>
                    </div>


                    <label for="motif">Motif de la réservation</label>
                    <textarea id="motif" name="motif" placeholder="Bonjour ,...."></textarea>
                </div>

                <!-- Colonne de droite : calendrier et infos utilisateur -->
                <div class="reservation-details">
                    <div class="calendar">
                        <header>
                            <button onclick="changeMonth(-1)" type="button">❮</button>
                            <span id="month-year"></span>
                            <button onclick="changeMonth(1)" type="button">❯</button>
                        </header>
                        <table>
                            <thead>
                                <tr>
                                    <th>Lu</th>
                                    <th>Ma</th>
                                    <th>Me</th>
                                    <th>Je</th>
                                    <th>Ve</th>
                                    <th>Sa</th>
                                    <th>Di</th>
                                </tr>
                            </thead>
                            <tbody id="days"></tbody>
                        </table>
                        <input type="hidden" id="selected-date" name="selected-date">
                    </div>

                    <div class="who">
                        <h3>Qui réserve ?</h3>
                        <div class="avatars">
                            <div id="avatar-container">
                                <?php
                                require_once("../PHPpure/connexion.php");
                                $id_utilisateur = $_SESSION["user"]["id"];
                                if ($_SESSION["user"]["role"] == "Etudiant(e)") {
                                    $requete = $pdo->prepare("SELECT * FROM user_ WHERE id = ?");
                                    $requete->execute([$id_utilisateur]);
                                    $utilisateur = $requete->fetch();
                                    $avatar = $utilisateur["avatar"];
                                    echo "<img src='$avatar' class='avatar' data-user-id='$id_utilisateur'>";
                                }
                                ?>
                            </div>
                            <input type="hidden" name="user_ids[]" id="user_ids">
                            <button class="add-avatar" id="add-avatar" type="button">+</button>
                        </div>
                        <section class="who-list-user" id="who-list-user">
                            <button type="button" class="close-user-list" id="close-user-list">
                                <img src="../res/x.svg" alt="">
                            </button>
                            <h3>Chercher un étudiant</h3>
                            <div class="search-container">
                                <input class="fs-3 fs-md-1" type="text" name="search" id="search" placeholder="Rechercher un étudiant">
                                <button type="button" class="search-button" id="search-button">
                                    <img src="../res/search.svg" alt="">
                                </button>
                            </div>
                            <article
                                class="d-flex justify-content-start align-items-center flex-column w-100 who-list-user-container"
                                id="overflowY">
                                <?php
                                require_once("../PHPpure/connexion.php");
                                if (isset($_SESSION['user'])) {
                                    $idConnecte = $_SESSION['user']['id'];
                                    $sql = "
                                            SELECT u.id, u.nom, u.prenom, u.avatar, e.promotion, e.td
                                            FROM user_ u
                                            INNER JOIN etudiant e ON u.id= e.id
                                            WHERE u.id != :idConnecte
                                        ";
                                    $stmt = $pdo->prepare($sql);
                                    $stmt->bindParam(':idConnecte', $idConnecte, PDO::PARAM_INT);
                                    $stmt->execute();
                                    $etudiants = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($etudiants as $etudiant) {
                                ?>
                                        <div class="who-list-user-item mb-3 col-12 d-flex justify-content-between align-items-center gap-2 w-100"
                                            id="<?= $etudiant['id'] ?>">
                                            <div class="d-flex justify-content-between align-items-center w-100">
                                                <div class="d-flex justify-content-between align-items-center gap-2">
                                                    <img src="<?= htmlspecialchars($etudiant['avatar'] ?? '../uploads/default.png') ?>"
                                                        alt="" class="avatarAjouterEtudiant " id="<?= $etudiant['id'] ?>">
                                                    <div
                                                        class="etudiantInfo d-flex justify-content-end align-items-start flex-column">
                                                        <p class="fs-3 fs-md-1"><?= htmlspecialchars($etudiant['prenom']) . ' ' . htmlspecialchars($etudiant['nom']) ?>
                                                        </p>
                                                        <p class="fs-3 fs-md-1"><?= htmlspecialchars($etudiant['promotion']) ?></p>
                                                    </div>
                                                </div>
                                                <p class="fs-3 fs-md-1"><?= htmlspecialchars($etudiant['td']) ?></p>
                                            </div>
                                            <button type="button" class="ajouterUserButton fs-3 fs-md-1">ajouter</button>
                                        </div>
                                <?php
                                    }
                                } else {
                                    echo "Utilisateur non connecté.";
                                }
                                ?>
                            </article>
                        </section>
                    </div>

                    <div class="signature-section">
                        <h3>Je signe</h3>
                        <canvas id="signature-canvas"></canvas>
                        <button class="clear-signature" onclick="clearCanvas()" type="button">Effacer</button>
                        <input type="hidden" name="signature" id="signature-data">

                        <label>
                            <input type="checkbox" name="acceptation">
                            En cas de perte, de détérioration ou d'utilisation non autorisée, je m'engage à en assumer les conséquences.
                        </label>
                    </div>

                    <button class="submit-button" type="submit" name="submit">Soumettre</button>
                </div>
            </section>
        </form>
    </main>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/reservation_salle.js"></script>
</body>

</html>