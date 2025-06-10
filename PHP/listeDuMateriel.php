<?php
include("../PHPpure/entete.php");
if ($_SESSION['user']['role'] != 'Administrateur') {
    header('Location: ../PHP/index.php');
    exit();
}

/*if (isset($_POST['upload'])) {
    // Récupérer le nom du matériel
    $materiel = $_POST['materiel'] ?? '';

    // Récupérer d'abord les anciens noms dans la BDD (exemple avec PDO)
    $sql = "SELECT photo FROM materiel WHERE Nom = ? LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$materiel]);
    $oldImage = $stmt->fetch(PDO::FETCH_ASSOC);

    // Initialiser les noms à garder par défaut
    $newImages = $oldImage;

    // Dossier upload
    $targetDir = "../materiel/";

        $inputName = "image";

        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            // Nouveau fichier uploadé remplace
            $originalName = $_FILES[$inputName];
            $extension = pathinfo($originalName, PATHINFO_EXTENSION);
            $newName = preg_replace('/[^a-zA-Z0-9_-]/', '', $materiel) . "_image" . '_' . uniqid() . "." . $extension;
            $targetFile = $targetDir . $newName;
            move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile);

            $newImage = $newName; // On remplace dans la variable
        }

    // Préparation de la requête UPDATE avec placeholders
    $sqlUpdate = "UPDATE materiel SET photo = :img WHERE Nom = :nom";
    $stmtUpdate = $pdo->prepare($sqlUpdate);

    // Liaison des paramètres un par un avec bindParam
    $stmtUpdate->bindParam(':img', $newImage, PDO::PARAM_STR);
    $stmtUpdate->bindParam(':nom', $materiel, PDO::PARAM_STR);

    // Exécution de la requête
    $stmtUpdate->execute();
}*/
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
    <link rel="stylesheet" href="../CSS/header.css" />
    <link rel="stylesheet" href="../CSS/modifPopupMateriel.css" />
    <title>Liste du matériel</title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main>
        <h1 class="mt-3">Matériel</h1>
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
                <p>Matériel</p>
                <p>Nom du matériel</p>
                <p>Statut</p>

            </article>
            <article class="body_Table pb-5">
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

                // Récupération du matériel
                $sql = "SELECT * FROM materiel";

                try {
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    if (count($result) > 0) {
                        foreach ($result as $row) {
                            // si le matériel est disponible ou non
                            if ($row['quantité'] >= 1) {
                                $status = "Disponible";
                            } else {
                                $status = "Indisponible";
                            }


                            echo '<div class="line my-5">';
                            echo '<img src="https://glistening-sunburst-222dae.netlify.app/materiel/' . htmlspecialchars($row['photo']) . '" alt="Photo matériel" style="height:100px; width:100px;">';
                            echo '<p class="text-center">' . htmlspecialchars($row['designation']) . '</p>';
                            echo '<p>' . $status . '</p>';
                            echo '<button class="modifier" data-id="' . $row['idM'] . '" 
                                    data-designation="' . htmlspecialchars($row['designation']) . '"
                                    data-photo="' . htmlspecialchars($row['photo']) . '"
                                    data-dateachat="' . date('Y-m-d', strtotime($row['dateAchat'])) . '"
                                    data-quantite="' . htmlspecialchars($row['quantité']) . '"
                                    data-descriptif="' . htmlspecialchars($row['descriptif']) . '"
                                    data-type="' . htmlspecialchars($row['typeM']) . '"
                                    data-etat="' . htmlspecialchars($row['etat']) . '"
                                    data-lien_demo="' . htmlspecialchars($row['lien_demo']) . '"></button>';
                            echo '</div>';
                        }
                    } else {
                        echo '<div class="line"><p>Aucun matériel trouvée</p></div>';
                    }
                } catch (PDOException $e) {
                    echo '<div class="line"><p>Erreur : ' . $e->getMessage() . '</p></div>';
                }

                $pdo = null;
                ?>
            </article>
            <button class="add" onclick="addmateriel()" id="addMateriel"><img src="../res/add.svg" alt="plus"></button>
        </section>
        <form class="modifPopupMateriel" action="../PHPpure/materielValidation.php" method="POST">
            <div class="modifPopupMateriel_content">
                <div class="modifPopupMateriel_content_header">
                    <h3>Modifier le matériel</h3>
                    <button class="close_modifPopupMateriel">
                        <img src="../res/x.svg" alt="close">
                    </button>
                </div>
                <input type="hidden" name="idM" id="idM">
                <div class="modifPopupMateriel_content_body">
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="designation">Désignation du matériel</label>
                        <input type="text" id="designation" name="designation" placeholder="Matériel">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="photo">Photo</label>
                        <img src="https://glistening-sunburst-222dae.netlify.app/materiel/<?= htmlspecialchars($row['photo']); ?>" alt="Photo matériel" style="height:100px; width:100px;">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="date_achat">Date d'achat</label>
                        <input type="datetime-local" id="date_achat" name="date_achat" placeholder="Date d'achat">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="quantite">Quantité</label>
                        <input type="number" id="quantite" name="quantite" min="0" value="1">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="descriptif">Descriptif</label>
                        <input type="text" id="descriptif" name="descriptif" placeholder="Descriptif">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                            <option value="Accessoire" selected>Accessoire</option>
                            <option value="Vidéo">Vidéo</option>
                            <option value="Audio">Audio</option>
                            <option value="Drone">Drone</option>
                            <option value="AR/VR">AR/VR</option>
                            <option value="Graphisme">Graphisme</option>
                        </select>
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="etat">Etat</label>
                        <select name="etat" id="etat">
                            <option value="Très bon état" selected>Très bon état</option>
                            <option value="Bon état">Bon état</option>
                            <option value="Mauvais état">Mauvais état</option>
                            <option value="En panne">En panne</option>
                        </select>
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="lien_demo">Lien Démonstration</label>
                        <input type="text" id="lien_demo" name="lien_demo" placeholder="lien_demo">
                    </div>
                    <div class="button-container">
                        <button type="button" name="supprimer" class="supprimer">Supprimer</button>
                        <button type="submit" name="submit">Modifier</button>
                    </div>
                </div>
            </div>
            </form>

            <!-- AJOUT MATERIEL -->
        <div class="modifPopupMateriel h-30" id="ajouterMateriel">
        <form action="../PHPpure/addMateriel.php" method="POST">
            <div class="modifPopupMateriel_content">
                <div class="modifPopupMateriel_content_header">
                    <h3>Ajouter un matériel</h3>
                    <button class="close_modifPopupMateriel" onclick="closePopup()">
                        <img src="../res/x.svg" alt="close">
                    </button>
                </div>
                <input type="hidden" name="idM" id="idM">
                <div class="modifPopupMateriel_content_body">
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="designation">Désignation du matériel</label>
                        <input type="text" id="designation" name="designation" placeholder="Matériel">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="photo">Photo</label>
                        <img src="https://glistening-sunburst-222dae.netlify.app/materiel/<?= htmlspecialchars($row['photo']); ?>" alt="Photo matériel" style="height:100px; width:100px;">
                    </div>
                    <!-- FORM UPLOAD IMAGES 
                    <form id="upload-form-<?= $index ?>" class="d-none mt-2" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="materiel" value="<?= htmlspecialchars($user['Nom']) ?>">
                        <input type="file" name="image1" accept="image/*" required>
                        <button type="submit" name="upload" class="btn btn-success btn-sm mt-2">Uploader</button>
                    </form>
                -->

                    <div class="modifPopupMateriel_content_body_item">
                        <label for="date_achat">Date d'achat</label>
                        <input type="datetime-local" id="date_achat" name="date_achat" placeholder="Date d'achat">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="quantite">Quantité</label>
                        <input type="number" id="quantite" name="quantite" min="0" value="1">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="descriptif">Descriptif</label>
                        <input type="text" id="descriptif" name="descriptif" placeholder="Descriptif">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="type">Type</label>
                        <select name="type" id="type">
                            <option value="Accessoire" selected>Accessoire</option>
                            <option value="Vidéo">Vidéo</option>
                            <option value="Audio">Audio</option>
                            <option value="Drone">Drone</option>
                            <option value="AR/VR">AR/VR</option>
                            <option value="Graphisme">Graphisme</option>
                        </select>
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="etat">Etat</label>
                        <select name="etat" id="etat">
                            <option value="Très bon état" selected>Très bon état</option>
                            <option value="Bon état">Bon état</option>
                            <option value="Mauvais état">Mauvais état</option>
                            <option value="En panne">En panne</option>
                        </select>
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="lien_demo">Lien Démonstration</label>
                        <input type="text" id="lien_demo" name="lien_demo" placeholder="lien_demo">
                    </div>
                    <div class="button-container mt-3">
                        <button type="submit" name="ajouterMateriel">Ajouter</button>
                    </div>
                </div>
        </form>
    </div>

    </main>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/listeDuMateriel.js"></script>
</body>

</html>