<section class="top">
    <p>Bienvenue dans votre espace personnel</p>
    <p><?php echo $_SESSION['user']['prenom'] ?></p>
    <div class="cards">
        <div class="card"></div>
        <div class="card"></div>
    </div>
</section>
<section class="reservation">
    <h2>Liste de réservation</h2>
    <div class="search">
        <p>Consulter l'historique</p>
        <div class="searchContainer">
            <input type="search" name="search" id="inputSearch" placeholder="Chercher..." />
            <button id="buttonSearch">
                <img src="../res/search.svg" alt="" />
            </button>
        </div>
    </div>
    <section class="table">
        <!-- utilisateion de bootstrap -->
        <article class="header_Table">
            <p>Type de réservation</p>
            <p>Date de réservation</p>
            <p>Créneau de réservation</p>
            <p>Statut</p>
        </article>
        <article class="body_Table">
            <!-- <div class="line">
                        <p>Réservation de caméra</p>
                        <p>04/04/2025</p>
                        <p>12h45 - 13h45</p>

                        <button class="attente"></button>
                    </div>
                    <div class="line">
                        <p>Réservation de caméra</p>
                        <p>04/04/2025</p>
                        <p>12h45 - 13h45</p>
                        <button class="accepté"></button>
                    </div>
                    <div class="line">
                        <p>Réservation salle</p>
                        <p>04/04/2025</p>
                        <p>12h45 - 13h45</p>
                        <button class="réfusé"></button>
                    </div>
                    <div class="line">
                        <p>Réservation de caméra</p>
                        <p>04/04/2025</p>
                        <p>12h45 - 13h45</p>
                        <button class="terminé"></button>
                    </div> -->
            <?php
            // Assurez-vous que l'utilisateur est connecté et que son ID est disponible dans la session
            if (isset($_SESSION['user']['id'])) {
                $userId = $_SESSION['user']['id']; // Récupérer l'ID de l'utilisateur connecté
            }
            require_once "../PHPpure/connexion.php";

            // Requête SQL pour récupérer les réservations de l'utilisateur
            $sql = "
                        SELECT 
                            r.idR,
                            r.date_debut,
                            r.date_fin,
                            r.valide,
                            m.designation AS materiel
                        FROM reservations r
                        JOIN concerne c ON r.idR = c.idR
                        JOIN materiel m ON c.idM = m.idM
                        JOIN reservation_users ru ON r.idR = ru.idR
                        WHERE ru.id = :user_id
                        ORDER BY r.date_debut DESC
                    ";

            // Prépare la requête
            $stmt = $pdo->prepare($sql);

            // Lie l'ID de l'utilisateur à la requête SQL
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);

            // Exécute la requête
            $stmt->execute();

            // Affichage des résultats
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $date = date("d/m/Y", strtotime($row['date_debut']));
                $startHour = date("H\hi", strtotime($row['date_debut']));
                $endHour = date("H\hi", strtotime($row['date_fin']));

                // Détermine le statut pour le bouton
                $now = new DateTime();
                $end = new DateTime($row['date_fin']);
                if ($row['valide'] == 0) {
                    $status = "attente";
                    if ($end < $now) {
                        $status = "annulé";
                        $sql = "UPDATE reservations SET valide = 2 WHERE idR = :idR";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':idR', $row['idR'], PDO::PARAM_INT);
                        $stmt->execute();
                    }
                } elseif ($end < $now) {
                    $status = "terminé";
                    $sql = "UPDATE reservations SET valide = 3 WHERE idR = :idR";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':idR', $row['idR'], PDO::PARAM_INT);
                    $stmt->execute();
                    $sql2 = "UPDATE materiel SET quantité = quantité + :quantite WHERE idR = :idR";
                    $stmt = $pdo->prepare($sql2);
                    $stmt->bindParam(':quantite', $row['quantite'], PDO::PARAM_INT);
                    $stmt->bindParam(':idM', $row['idM'], PDO::PARAM_INT);
                    $stmt->execute();
                } else if ($row['valide'] == 1) {
                    $status = "accepté";
                } else if ($row['valide'] == 2) {
                    $status = "refusé";
                }

                // Affichage des informations de réservation
                echo "
                            <div class='line'>
                                <p>Réservation de {$row['materiel']}</p>
                                <p>$date</p>
                                <p>$startHour - $endHour</p>
                                <div class='statusButton'>
                                    <button class='$status'></button>
                                    <button class='telecharger' onclick='window.open(\"../PHPpure/genererpdf.php?idR={$row['idR']}\", \"_blank\")'>Télécharger</button>
                                </div>
                            </div>
                        ";
            }

            // Pareil pour les réservations de salle
            $sql = "
                        SELECT 
                            r.date_debut,
                            r.date_fin,
                            r.valide,
                            s.nom AS salle,
                            r.idR AS idR

                        FROM reservations r
                        JOIN concerne_salle cs ON r.idR = cs.idR
                        JOIN salle s ON cs.idS = s.idS
                        JOIN reservation_users ru ON r.idR = ru.idR
                        WHERE ru.id = :user_id
                        ORDER BY r.date_debut DESC
            ";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $stmt->execute();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $date = date("d/m/Y", strtotime($row['date_debut']));
                $startHour = date("H\hi", strtotime($row['date_debut']));
                $endHour = date("H\hi", strtotime($row['date_fin']));

                if ($row['valide'] == 0) {
                    $status = "attente";
                } elseif ($row['valide'] == 1) {
                    $status = "accepté";
                } elseif ($row['valide'] == 2) {
                    $status = "réfusé";
                }
                echo "
                            <div class='line'>  
                                <p>Réservation de {$row['salle']}</p>
                                <p>$date</p>
                                <p>$startHour - $endHour</p>
                                <div class='statusButton'>
                                    <button class='$status'></button>
                                    <button class='telecharger' onclick='window.open(\"../PHPpure/genererpdf.php?idR={$row['idR']}\", \"_blank\")'>Télécharger</button>
                                </div>
                            </div>
                        ";
            }

            // ajouter bouton pour telecharger le pdf


            ?>
        </article>
    </section>
</section>