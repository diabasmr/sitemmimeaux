<section class="top">
    <p>Bienvenue dans votre espace personnel</p>
    <p><?php echo $_SESSION['user']['prenom'] ?></p>
    <div class="cards">
        <div class="card p-2">
            <h5>Prochaine réservation</h5>
            <p style="color:pink;">
                <?php
                require "../PHPpure/connexion.php";

                // Préparation de la requête
                $sql = "
                SELECT r.idR, r.date_debut
                FROM reservations r
                WHERE r.date_debut > :today AND valide = 1
                ORDER BY r.date_debut ASC
                LIMIT 1
            ";

                $stmt = $pdo->prepare($sql);

                // Passage des paramètres
                $today = date('Y-m-d');

                $stmt->bindParam(':today', $today);

                // Exécution et traitement
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($row) {
                    echo $row['date_debut'];
                } else {
                    echo "Aucune réservation à venir.";
                }
                ?>
            </p>
        </div>
    </div>
</section>
<section class="reservation">
    <h2>Liste des réservations</h2>
    <div class="search">
        <p>Consulter l'historique</p>
        <div class="searchContainer">
            <input type="search" name="search" id="inputSearch" placeholder="Chercher..." />
            <button id="buttonSearch">
                <img src="../res/search.svg" alt="" />
            </button>
        </div>
    </div>
    <section class="container-sm bg-white" style="border-radius: 15px;">
        <!-- utilisateion de bootstrap -->
        <div class="row p-3 fs-5 fw-semibold" style="color:#e4587d; background-color: #edafbe; border-radius: 10px; border: 1px solid #edafbe;">
            <p class="col-3">Type de réservation</p>
            <p class="col-3">Date de réservation</p>
            <p class="col-3">Créneau de réservation</p>
        </div>
        <?php
        if (isset($_SESSION['user']['id'])) {
            $userId = $_SESSION['user']['id']; // Récupérer l'ID de l'utilisateur connecté
        }
        require_once "../PHPpure/connexion.php";

        $sql = "SELECT 
                            r.idR,
                            r.date_debut,
                            r.date_fin,
                            r.valide,
                            m.designation AS materiel
                        FROM reservations r
                        JOIN concerne c ON r.idR = c.idR
                        JOIN materiel m ON c.idM = m.idM
                        WHERE valide = 1
                        ORDER BY r.date_debut DESC
                    ";

        // Prépare la requête
        $stmt = $pdo->prepare($sql);

        // Exécute la requête
        $stmt->execute();

        // Affichage des résultats
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = date("d/m/Y", strtotime($row['date_debut']));
            $startHour = date("H\hi", strtotime($row['date_debut']));
            $endHour = date("H\hi", strtotime($row['date_fin']));

            // Affichage des informations de réservation
            echo "
                            <div class='row p-3 align-items-center gy-3' style='border-radius:10px; border-bottom: 1px solid rgba(228, 88, 125, 0.2);'>
                                <div class='col-3'>
                                <p>Réservation de {$row['materiel']}</p>
                                </div>
                                <div class='col-3'>
                                <p>$date</p>
                                </div>
                                <div class='col-3'>
                                <p>$startHour - $endHour</p>
                                </div>
                                <div class='col-3'>
                                    <button class='telecharger' onclick='window.open(\"../PHPpure/genererpdf.php?idR={$row['idR']}\", \"_blank\")'>Télécharger</button>
                                </div>
                            </div>
                        ";
        }

        // Pareil pour les réservations de salle
        $sql = "SELECT 
                            r.date_debut,
                            r.date_fin,
                            r.valide,
                            s.nom AS salle,
                            r.idR AS idR

                        FROM reservations r
                        JOIN concerne_salle cs ON r.idR = cs.idR
                        JOIN salle s ON cs.idS = s.idS
                        WHERE valide = 1
                        ORDER BY r.date_debut DESC
            ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $date = date("d/m/Y", strtotime($row['date_debut']));
            $startHour = date("H\hi", strtotime($row['date_debut']));
            $endHour = date("H\hi", strtotime($row['date_fin']));
            echo "
                            <div class='line'>  
                                <p>Réservation de {$row['salle']}</p>
                                <p>$date</p>
                                <p>$startHour - $endHour</p>
                                <div class='statusButton'>
                                    <button class='telecharger' onclick='window.open(\"../PHPpure/genererpdf.php?idR={$row['idR']}\", \"_blank\")'>Télécharger</button>
                                </div>
                            </div>
                        ";
        }

        // ajouter bouton pour telecharger le pdf


        ?>
    </section>
</section>