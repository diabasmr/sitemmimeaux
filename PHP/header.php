<!-- $_SESSION['user'] = [
    'id' => $user['id'],
    'pseudo' => $user['pseudo'],
    'nom' => $user['nom'],        // Ajouter le nom de l'utilisateur
    'prenom' => $user['prenom'],  // Ajouter le prénom de l'utilisateur
    'email' => $user['email'],    // Ajouter l'email de l'utilisateur
    'role' => $user['role'],      // Ajouter le rôle de l'utilisateur (admin, utilisateur, etc.)
    'session_token' => bin2hex(random_bytes(32)) // Optionnel : ajouter un token pour plus de sécurité
]; -->

<header class="header">
    <div class="logo">
        <!-- <p>Logo + nom</p> -->
        <img src="../IMG/logo.png" alt="">
    </div>
    <button class="menuButton" onclick="toggleSidebar()">
        <img src="../res/menu.svg" alt="" id="menuimg" />
    </button>
    <!-- bootstrap -->
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- <h1>Tableau de bord</h1> -->
        <!-- detecter la page avec php -->
        <?php
        $filename = pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);
        switch ($filename) {
            case 'index':
                echo '<h1 class="fs-3 fs-md-1">Tableau de bord</h1>';
                break;
            case 'profil':
                echo '<h1 class="fs-3 fs-md-1">Mon profil</h1>';
                break;
            case 'reservation':
                echo '<h1 class="fs-3 fs-md-1">Mes réservations</h1>';
                break;
            case 'salles':
                echo '<h1 class="fs-3 fs-md-1">Salles</h1>';
                break;
            case 'materiels':
                echo '<h1 class="fs-3 fs-md-1">Matériel</h1>';
                break;
            case 'listeDesReservations':
                echo '<h1 class="fs-3 fs-md-1">Liste des réservations</h1>';
                break;
            case 'listeDuMateriel':
                echo '<h1 class="fs-3 fs-md-1">Liste du matériel</h1>';
                break;
            case 'reservation_salle':
                echo '<h1 class="fs-3 fs-md-1">Réservation de salle</h1>';
                break;
            case 'reservation_materiel':
                echo '<h1 class="fs-3 fs-md-1">Réservation de matériel</h1>';
                break;
            default:
                echo '<h1 class="fs-3 fs-md-1">ya pas le nom ou c mal mis</h1>';
        }
        ?>

        <div class="profilXlogout">
            <div class="profil">
                <div class="imgProfilContainer">
                    <img src="
                        <?php
                        if (isset($_SESSION['user']['profil'])) {
                            if ($_SESSION['user']['profil'] != "none") {
                                echo $_SESSION['user']['profil'];
                            } else {
                                echo "../uploads/default.png";
                            }
                        }
                        ?>" onclick="location.href = 'profil.php'" alt="" class="imgProfil" />
                </div>
                <div class="nomRole">
                    <!-- recuperer le nom de l'utilisateur dans la session user-->
                    <p>
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo $_SESSION['user']['nom'] . ' ' . $_SESSION['user']['prenom'];
                        }
                        ?></p>
                    <p><?php echo $_SESSION['user']['role']; ?></p>
                </div>
            </div>
            <!-- detruire la session -->
            <button class="logout" onclick="location.href = '../PHPpure/logout.php'">
                <img src="../res/logout.svg" alt="" />
            </button>
        </div>
    </div>
</header>