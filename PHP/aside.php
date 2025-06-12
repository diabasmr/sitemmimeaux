<?php
$current_page = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
?>

<!-- bootstrap -->
<aside class="d-flex flex-column justify-content-between">
    <article class="menu">
    <div class="logoside">
        <!-- <p>Logo + nom</p> -->
        <img src="../IMG/logo.png" alt="">
    </div>
        <p>Menu</p>
        <nav>
            <ul class="">
                <li>
                    <a href="index.php" id="index" <?php echo ($current_page === 'index') ? 'class="active"' : ''; ?>>
                        <img src="../res/tableaudebord.svg" alt="" />
                        Tableau de bord
                    </a>
                </li>
                <li>
                    <a href="reservation.php" id="reservation"
                        <?php echo ($current_page === 'reservation') ? 'class="active"' : ''; ?>>
                        <img src="../res/reservation.svg" alt="" />
                        <?php
                        if (isset($_SESSION['user']['role'])) {
                            if ($_SESSION['user']['role'] == 'Etudiant(e)') {
                                echo 'Mes réservations';
                            } else {
                                echo 'Réservations';
                            }
                        }
                        ?>
                    </a>
                </li>
                <?php
                if ($_SESSION['user']['role'] != 'Agent(e)') {
                ?>
                <li>
                    <a href="materiels.php" id="materiel"
                        <?php echo ($current_page === 'materiels') ? 'class="active"' : ''; ?>>
                        <img src="../res/materiel.svg" alt="" />
                        Matériel
                    </a>
                </li>
                <li>
                    <a href="salles.php" id="salles"
                        <?php echo ($current_page === 'salles' || $current_page === 'reservation_salle') ? 'class="active"' : ''; ?>>
                        <img src="../res/salles.svg" alt="" />
                        Salles
                    </a>
                </li>
                <?php
                }
                ?>
                <?php
                if ($_SESSION['user']['role'] == 'Administrateur') {
                ?>
                    <li>
                        <a href="listeDesReservations.php" id="accepter_reservation"
                            <?php echo ($current_page === 'listeDesReservations') ? 'class="active"' : ''; ?>>
                            <img src="../res/liste_rsrv.svg" alt="" />
                            Liste des réservations
                        </a>
                    </li>
                    <li>
                        <a href="listeDuMateriel.php" id="accepter_materiel"
                            <?php echo ($current_page === 'listeDuMateriel') ? 'class="active"' : ''; ?>>
                            <img src="../res/liste_rsrv.svg" alt="" />
                            Liste du matériel
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </article>
    <article class="autre menu">
        <p>Autre</p>
        <nav>
            <ul>
                <li>
                    <a href="profil.php" id="profil"
                        <?php echo ($current_page === 'profil') ? 'class="active"' : ''; ?>>
                        <img src="../res/profil.svg" alt="" />
                        Mon profil
                    </a>
                </li>
                <?php
                if ($_SESSION['user']['role'] != 'Agent(e)') {
                ?>
                <li>
                    <a href="https://intranet-edu.univ-eiffel.fr/ent" id="ent">
                        <img src="../res/univ.svg" alt="" />
                        Accéder à l'ENT 
                    </a>
                </li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </article>
</aside>