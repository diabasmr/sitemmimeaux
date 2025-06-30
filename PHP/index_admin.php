<?php
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>
<section class="top">
    <h1>Bienvenue <?php echo $_SESSION['user']['role'] ?></h1>
    <p class="fs-2 ms-3 fw-semibold" style="color:#e4587d;"><?php echo $_SESSION['user']['prenom'] ?></p>
</section>
<section class=" reservation">
    <h2>Liste des utilisateurs</h2>
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
        <div class="row p-3 fs-5 fw-semibold" style="color:#e4587d; background-color: #edafbe; border-radius: 10px; border: 1px solid #edafbe;">
            <p class="col-4">Nom d'utilisateur</p>
            <p class="col-4">Date d'inscription</p>
            <p class="col-2">Statut</p>
            <p class="col-2"></p>
        </div>
        <?php
        require_once('../PHPpure/connexion.php');

        $sql = "SELECT u.*, e.promotion, e.td FROM user_ u LEFT JOIN etudiant e ON e.id = u.id;";
        $stmt = $pdo->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo '
<div class="cliquable row p-3 align-items-center gy-3 text-dark text-decoration-none" style="border-radius:10px; border-bottom: 1px solid rgba(228, 88, 125, 0.2); cursor: pointer;">
    <div class="col-4">
        <p>' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</p>
    </div>
    <div class="col-4">
        <p>' . htmlspecialchars($row['date_inscription']) . '</p>
    </div>
    <div class="col-2">
        <p>' . statusUser($row['id'], $pdo) . '</p>
    </div>
    <div class="col-2">
        <button 
            class="modifier"
            onclick="event.stopPropagation(); openModifPopup(
                \'' . $row['id'] . '\',
                \'' . htmlspecialchars($row['nom'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars($row['prenom'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars($row['email'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars($row['telephone'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars($row['Date_de_naissance'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars($row['promotion'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars($row['td'], ENT_QUOTES) . '\', 
                \'' . htmlspecialchars(statusUser($row['id'], $pdo), ENT_QUOTES) . '\',
                \'' . htmlspecialchars($row['valable'], ENT_QUOTES) . '\'
            )">
        </button>
    </div>
</div>';
        }
        ?>
        <button class="add mb-3" id="addUser"><img src="../res/add.svg" alt="plus"></button>
    </section>
    <div id="modifPopup" class="modif">
        <button id="closeModifPopup"><img src="../res/x.svg" alt=""></button>
        <h3 class="fs-3">Modifier l'utilisateur</h3>
        <p>Information <img src="../res/" alt="">
        </p>
        <form action="" method="POST">


            <div class="name">
                <input type="text" name="id" id="id" style="display: none;">
                <div class="nom">
                    <label class="fs-6" for="nom">
                        Nom
                    </label>
                    <input class="fs-6" type="text" name="nom" id="nom" placeholder="Nom" disabled>
                </div>
                <div class="prenom">
                    <label class="fs-6" for="prenom">Prénom</label>
                    <input class="fs-6" type="text" name="prenom" id="prenom" placeholder="Prénom" disabled>
                </div>
            </div>
            <div class="email">
                <label class="fs-6" for="email">Email</label>
                <input class="fs-6" type="email" name="email" id="email" placeholder="Email" disabled>
            </div>
            <div class="tel">
                <label class="fs-6" for="tel">Téléphone</label>
                <input class="fs-6" type="tel" name="tel" id="tel" placeholder="Téléphone" disabled>
            </div>
            <div class="naissance">
                <label class="fs-6" for="tel">Date de naissance</label>
                <input class="fs-6" type="text" name="naissance" id="naissance" placeholder="00-00-00" disabled>
            </div>
            <div>
                <div class="promo">
                    <label class="fs-6" for="promo">
                        Promotion
                    </label>
                    <input class="fs-6" type="text" name="promotion" id="promotion" placeholder="PROMOTION" disabled>
                </div>
                <div class="td">
                    <label class="fs-6" for="td">TD</label>
                    <input class="fs-6" type="text" name="td" id="td" placeholder="TD" disabled>
                </div>
            </div>
            <div class="role">
                <label class="fs-6" for="role">Définir un statut à l'utilisateur</label>
                <select class="fs-6" name="role" id="role">
                    <option value="etudiant">Etudiant</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="administrateur">Administrateur</option>
                    <option value="agent">Agent</option>
                </select>
            </div>
            <div class="buttonsSubmit">
                <button type="submit" id="supprimerUtilisateur" name="supprimerUtilisateur">Supprimer l'utilisateur</button>
                <!-- utilisation de la fonciton changeValable -->

                <!-- <input type="text" name="id2" id="id2" style="display: none;"> -->
                <!-- reload la page aprés validation -->
                <button class="fs-6 p-2" type="submit" id="validation" name="validation">Valider la connexion</button>
                <!-- reload la page aprés modification -->
                <button class="fs-6 p-2" type="submit" id="modifierUtilisateur" name="modifierUtilisateur"
                    onclick="window.location.reload(true);">Modifier
                </button>
            </div>
        </form>
    </div>
    <div class="ajouterUser h-30" id="ajouterUser">
        <button id="closeAjouterPopup"><img src="../res/x.svg" alt=""></button>
        <h3>Ajouter un utilisateur</h3>
        <p>Information</p>
        <form action="../PHPpure/addUser.php" method="POST">
            <div class="name">
                <input type="text" name="id" id="id" style="display: none;">
                <div class="nom">
                    <label for="nom">
                        Nom
                    </label>
                    <input type="text" name="nom" id="nom" placeholder="Nom">
                </div>
                <div class="prenom">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom">
                </div>
            </div>
            <div class="email">
                <label for="email">Attribuer un email</label>
                <input type="email" name="email" id="email" placeholder="Email">
            </div>
            <div class="motDePasse">
                <label for="motDePasse">Attribuer un mot de passe</label>
                <input type="password" name="motDePasse" id="motDePasse" placeholder="Mot de passe">
            </div>

            <div class="role">
                <label for="role">Rôle</label>
                <select name="role" id="role">
                    <option value="etudiant">Etudiant</option>
                    <option value="enseignant">Enseignant</option>
                    <option value="administrateur">Administrateur</option>
                    <option value="agent">Agent</option>
                </select>
            </div>
            <div class="buttonsSubmitContainer">
                <button type="submit" class="buttonsSubmit fs-6 fs-md-auto" name="ajouterUtilisateur">Ajouter l'utilisateur</button>
            </div>
        </form>
    </div>

</section>
<?php
require_once('../PHPpure/connexion.php');

// changer valable en 1 
function changeValable($id, $pdo)
{
    $sql = "UPDATE user_ SET valable = 1 WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}



function statusUser($id, $pdo)
{
    $sql = "SELECT valable FROM user_ WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        if ($result['valable'] == 1) {
            return getUserRole($id, $pdo);
        } else {
            return 'En attente de validation';
        }
    } else {
        return 'Utilisateur introuvable';
    }
}

function supprimerUtilisateur($id, $pdo)
{
    $sql = "DELETE FROM user_ WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
}

if (isset($_POST['id']) && isset($_POST['validation'])) {
    changeValable($_POST['id'], $pdo);

    require '../PHPMailer-master/src/PHPMailer.php';
    require '../PHPMailer-master/src/SMTP.php';
    require '../PHPMailer-master/src/Exception.php';
    $stmt = $pdo->prepare("SELECT * FROM `user_` WHERE id = ?");
    $stmt->execute($_POST['id']);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user3) {
        $email = $user3['email'];
        $Pseudo = $user3['pseudo'];
        $Nom = $user3['nom'];
        $Prenom = $user3['prenom'];
        // Envoi de l'e-mail avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'materiel.iut@gmail.com';
            $mail->Password = 'veta utze kwrk elbf';     // Utilise un mot de passe d’application
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('iut.rezoom@gmail.com', 'ReZoom Support');
            $mail->addAddress($email, "$Nom $Prenom");
            $mail->addReplyTo('iut.rezoom@gmail.com', 'ReZoom Support');

            $mail->Subject = 'Validation de votre compte';
            $mail->Body = "Bonjour $Nom $Prenom,\n\nVotre compte ReZoom, $Pseudo a été validé, vous pouvez maintenant consulter le site et commencer à prévoir vos emprunts et réservations !\n\nCordialement,\nL'équipe ReZoom";

            $mail->send();

            echo <<<HTML
        <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center" style="--bs-border-opacity: .5; z-index:10; width: 500px; border: 1px solid  #e47390;">
          <p class="mb-2 d-block">Le compte a été validé avec succès. </p>
          <div class="text-center mt-3">
          <button onclick="window.location.href='mdp_oublie.php'" class="btn mdp" style="height: 7vh; background-color: #e47390; border-radius: 0.5vw; border: none; font-size: 1.2vw; color: white;">Fermer</button>
          </div>
        </div>
        HTML;
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
        }
    }
}

if (isset($_POST['id']) && isset($_POST['supprimerUtilisateur'])) {
    supprimerUtilisateur($_POST['id'], $pdo);
}

if (isset($_POST['id']) && isset($_POST['modifierUtilisateur'])) {
    $id = $_POST['id'];
    $nouveauRole = $_POST['role'];

    $rolebase = getUserRole($id, $pdo);
    $rolesMap = [
        'Administrateur' => 'administrateur',
        'Enseignant(e)' => 'enseignant',
        'Etudiant(e)' => 'etudiant',
        'Agent(e)' => 'agent'
    ];

    if (isset($rolesMap[$rolebase])) {
        $sql2 = "DELETE FROM $rolesMap[$rolebase] WHERE id = :id";
        $stmt = $pdo->prepare($sql2);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
    }
    $sqlInsert = "INSERT INTO $nouveauRole (id) VALUES (:id)";
    $stmt = $pdo->prepare($sqlInsert);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}
?>