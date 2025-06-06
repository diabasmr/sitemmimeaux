<?php
include("../PHPpure/entete.php");
if ($_SESSION['user']['role'] != 'Administrateur') {
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


                            echo '<div class="line">';
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
                        <input type="text" id="designation" placeholder="Matériel">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="photo">Photo</label>
                        <img src="https://glistening-sunburst-222dae.netlify.app/materiel/<?= htmlspecialchars($row['photo']); ?>" alt="Photo matériel" style="height:100px; width:100px;">
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="date_achat">Date d'achat</label>
                        <input type="datetime-local" id="date_achat" name="date_achat" placeholder="Date d'achat" disabled>
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
                            <option value="0" selected>Accessoire</option>
                            <option value="1">Vidéo</option>
                            <option value="2">Audio</option>
                            <option value="3">Drone</option>
                            <option value="4">AR/VR</option>
                            <option value="5">Graphisme</option>
                        </select>
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="etat">Etat</label>
                        <select name="etat" id="etat">
                            <option value="0" selected>Très bon état</option>
                            <option value="1">Bon état</option>
                            <option value="2">Mauvais état</option>
                            <option value="3">En panne</option>
                        </select>
                    </div>
                    <div class="modifPopupMateriel_content_body_item">
                        <label for="lien_demo">Lien Démonstration</label>
                        <input type="text" id="lien_demo" name="lien_demo" placeholder="lien_demo">
                    </div>
                    <div class="button-container">
                        <button type="button" name="supprimer" class="supprimer">Supprimer</button>
                        <button type="submit">Modifier</button>
                    </div>
                </div>
            </div>
        </form>
    </main>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/listeDuMateriel.js"></script>
</body>

</html>