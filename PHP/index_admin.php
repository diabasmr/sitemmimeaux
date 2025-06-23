<?php
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
?>
<section class="top">
    <p>Bienvenue <?php echo $_SESSION['user']['role'] ?></p>
    <p><?php echo $_SESSION['user']['prenom'] ?></p>
</section>
<section class="reservation">
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
    <section class="table mb-5">
        <article class="header_Table">
            <p>Nom d'utilisateur</p>
            <p>Date d'inscription</p>
            <p>Statut</p>

        </article>
        <article class="body_Table">
            <!-- <div class="line">
                <p>Nom d'utilisateur inscrit</p>
                <p>07/02/2025</p>
                <p>Non défini</p>
                <button class="modifier"></button>
            </div>
            <div class="line">
                <p>Nom d'utilisateur inscrit</p>
                <p>07/02/2025</p>
                <p>Non défini</p>
                <button class="modifier"></button>
            </div>
            <div class="line">
                <p>Nom d'utilisateur inscrit</p>
                <p>07/02/2025</p>
                <p>Non défini</p>
                <button class="modifier"></button>
            </div>
            <div class="line">
                <p>Nom d'utilisateur inscrit</p>
                <p>07/02/2025</p>
                <p>Non défini</p>
                <button class="modifier"></button>
            </div> -->
            <?php
            require_once('../PHPpure/connexion.php');

            $sql = "SELECT * FROM user_";
            $stmt = $pdo->query($sql);

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '
                <div class="line" >
                    <p>' . htmlspecialchars($row['nom']) . ' ' . htmlspecialchars($row['prenom']) . '</p>
                    <p>' . htmlspecialchars($row['date_inscription']) . '</p>
                    <p>' . statusUser($row['id'], $pdo) . '</p>
                    <button 
                        class="modifier" 
                        onclick="openModifPopup(
                            \'' . $row['id'] . '\',
                            \'' . $row['nom'] . '\', 
                            \'' . $row['prenom'] . '\', 
                            \'' . $row['email'] . '\', 
                            \'' . $row['telephone'] . '\', 
                            \'' . statusUser($row['id'], $pdo) . '\',
                            \'' . $row['valable'] . '\'
                        )">
                    </button>
                </div>';
            }
            ?>
        </article>
        <button class="add" id="addUser"><img src="../res/add.svg" alt="plus"></button>
    </section>
    <div id="modifPopup" class="modif">
        <button id="closeModifPopup"><img src="../res/x.svg" alt=""></button>
        <h3>Modifier l'utilisateur</h3>
        <p>Information <img src="../res/" alt="">
        </p>
        <form action="" method="POST">


            <div class="name">
                <input type="text" name="id" id="id" style="display: none;">
                <div class="nom">
                    <label for="nom">
                        Nom
                    </label>
                    <input type="text" name="nom" id="nom" placeholder="Nom" disabled>
                </div>
                <div class="prenom">
                    <label for="prenom">Prénom</label>
                    <input type="text" name="prenom" id="prenom" placeholder="Prénom" disabled>
                </div>
            </div>
            <div class="email">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" disabled>
            </div>
            <div class="tel">
                <label for="tel">Téléphone</label>
                <input type="tel" name="tel" id="tel" placeholder="Téléphone" disabled>
            </div>
            <div class="role">
                <label for="role">Définir un statut à l'utilisateur</label>
                <select name="role" id="role">
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
                <button type="submit" id="validation" name="validation">Valider la connexion</button>
                <!-- reload la page aprés modification -->
                <button type="submit" id="modifierUtilisateur" name="modifierUtilisateur"
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
            $mail->Password = 'obmv hoac gbrw ftwz';     // Utilise un mot de passe d’application
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