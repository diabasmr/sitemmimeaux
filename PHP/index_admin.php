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