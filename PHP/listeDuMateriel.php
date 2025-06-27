<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'Administrateur') {
    header('Location: ../PHP/index.php');
    exit();
}
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
                </div>
            </div>
        </div>
        <section class="container-sm bg-white" style="border-radius: 15px;">
            <article class="row p-3 fs-6 fs-md-5 fw-semibold gap-2" style="color:#e4587d; background-color: #edafbe; border-radius: 10px; border: 1px solid #edafbe;">
                <p class="col-2">Matériel</p>
                <p class="col-3">Désignation</p>
                <p class="col-3">Quantité</p>
                <p class="col-2">Statut</p>
                <p class="col-2"></p>
            </article>
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
                        $sql2 = "SELECT quantité FROM materiel WHERE idM = ?";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([$row['idM']]);
                        $quantite_totale = $stmt2->fetch(PDO::FETCH_ASSOC);

                        $sql3 = "SELECT COUNT(r.idR) AS nb_reservations FROM reservations r JOIN concerne c ON r.idR = c.idR WHERE c.idM = ? AND r.valide = 1";
                        $stmt3 = $pdo->prepare($sql3);
                        $stmt3->execute([$row['idM']]);
                        $resas = $stmt3->fetch(PDO::FETCH_ASSOC);

                        // Calcul de la quantité restante
                        $quantite_dispo = $quantite_totale['quantité'] - $resas['nb_reservations'];
                        if ($quantite_dispo >= 1) {
                            $status = "Disponible";
                        } else {
                            $status = "Indisponible";
                        }


                        echo '<div onclick="window.location.href=\'produit.php?id=' . $row['idM'] . '\'" class="cliquable row p-3 align-items-center gy-3 text-dark text-decoration-none" style="border-radius:10px; border-bottom: 1px solid rgba(228, 88, 125, 0.2); cursor: pointer;">';
                        echo '<div class="col-2">';
                        echo '<img src="../materiel/' . htmlspecialchars($row['photo']) . '" alt="Photo matériel" style="height:100%; width:100%;">';
                        echo '</div>';

                        echo '<div class="col-3">';
                        echo '<p>' . htmlspecialchars($row['designation']) . '</p>';
                        echo '</div>';

                        echo '<div class="col-3">';
                        echo '<p><span>Quantité: ' . htmlspecialchars($row['quantité']) . '</span></p>';
                        echo '</div>';

                        echo '<div class="col-2">';
                        echo '<p>' . $status . '</p>';
                        echo '</div>';

                        echo '<div class="col-2">';
                        echo '<button class="modifier" type="button" onclick="event.stopPropagation()" 
        data-id="' . $row['idM'] . '"
        data-designation="' . htmlspecialchars($row['designation']) . '"
        data-photo="' . htmlspecialchars($row['photo']) . '"
        data-dateachat="' . date('Y-m-d', strtotime($row['dateAchat'])) . '"
        data-quantite="' . htmlspecialchars($row['quantité']) . '"
        data-descriptif="' . htmlspecialchars($row['descriptif']) . '"
        data-type="' . htmlspecialchars($row['typeM']) . '"
        data-etat="' . htmlspecialchars($row['etat']) . '"
        data-lien_demo="' . (isset($row['lien_demo']) && !empty($row['lien_demo']) ? htmlspecialchars($row['lien_demo']) : 'lien démonstration') . '">
    </button>';
                        echo '</div>';

                        echo '</div>';
                    }
                } else {
                    echo '<div class="line"><p>Aucun matériel trouvé</p></div>';
                }
            } catch (PDOException $e) {
                echo '<div class="line"><p>Erreur : ' . $e->getMessage() . '</p></div>';
            }

            $pdo = null;
            ?>
            <button class="add mb-3" id="addMateriel"><img src="../res/add.svg" alt="plus"></button>
        </section>
        <form id="upload-form-<?= $index ?>" class="modifPopupMateriel" action="../PHPpure/materielValidation.php" method="POST" enctype="multipart/form-data">
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
                        <!-- UPLOAD IMAGES-->
                        <input type="file" name="photo" accept="image/*">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="date_achat">Date d'achat</label>
                        <input type="date" id="date_achat" name="date_achat" placeholder="Date d'achat">
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
            <form action="../PHPpure/addMateriel.php" method="POST" enctype="multipart/form-data">
                <div class="modifPopupMateriel_content">
                    <div class="modifPopupMateriel_content_header">
                        <h3>Ajouter un matériel</h3>
                        <button class="close_modifPopupMateriel" id="close_modifPopupMateriel">
                            <img src="../res/x.svg" alt="close">
                        </button>
                    </div>
                    <input type="hidden" name="idM" id="idM">
                    <div class="modifPopupMateriel_content_body">
                        <div class="modifPopupMateriel_content_body_item">
                            <label for="designation_add">Désignation du matériel</label>
                            <input type="text" id="designation_add" name="designation" placeholder="Matériel">
                        </div>
                        <div class="modifPopupMateriel_content_body_item">
                            <label for="photo">Photo</label>
                            <input type="file" name="photo" accept="image/*" />
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
    <?php if (!empty($error)) : ?>
        <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
                <h5 class="mb-3 fw-semibold text-dark">Erreur</h5>
                <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
            </div>
        </div>
    <?php endif; ?>
    <script src="../JS/sideBarre.js" defer></script>
    <script src="../JS/listeDuMateriel.js" defer></script>
</body>

</html>