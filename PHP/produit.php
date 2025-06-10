<?php
include("../PHPpure/entete.php");
require('../PHPpure/connexion.php');
if ($_SESSION['user']['role'] != 'Administrateur') {
    header('Location: ../PHP/index.php');
    exit();
}

// Vérifie si l'ID du produit est bien passé dans l'URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête pour récupérer les détails du produit
    $sql = "SELECT * FROM materiel WHERE idM = :nom";

    // Prépare la requête
    $stmt = $pdo->prepare($sql);
    if ($stmt) {
        $stmt->execute([':nom' => $id]);

        // Récupère les résultats
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Si le produit n'existe pas
        if (!$row) {
            echo "Produit introuvable.";
            exit;
        }
    } else {
        echo "Erreur dans la préparation de la requête.";
        exit;
    }
} else {
    echo "Aucun produit sélectionné.";
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <title>Produit</title>
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/index.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    // recuperer les favoris
    $sql = "SELECT * FROM favori_materiel WHERE id = ?";
    $result = $pdo->prepare($sql);
    $result->execute([$_SESSION['user']['id']]);
    ?>
    <main>
        <div>
            <div class="row ms-3 ms-md-auto mt-3">
                <h2><?= htmlspecialchars($row['designation']) ?></h2>
            </div>
            <div class="row">
                <img src="https://glistening-sunburst-222dae.netlify.app/materiel/<?= htmlspecialchars($row['photo']) ?>" alt="Photo matériel" class="col-sm-4 rounded ">
                <div class="col-sm-7 ms-1 me-1">
                    <div class="row justify-content-center">
                        <h3 id="nomproduit"><?= htmlspecialchars($row['designation']) ?></h3>
                        <p><?= htmlspecialchars($row['descriptif']) ?></p>
                    </div>

                    <div class="row justify-content-center mt-5">
                        <div class="col-sm-5 col-4 justify-content-center align-items-center">
                            <div class="row">
                                <input type="number" class="form-control text-center" value="1" min="1" id="quantite">
                            </div>
                            <div class="row mt-2">
                                <?php
                                echo "<button class='btn btn-danger text-white text-center p-3' onclick='reserverMateriel(" . $row['idM'] . ")'";

                                if ($row['quantité'] == 0) {
                                    echo " disabled";
                                }

                                echo ">Réserver</button>";
                                ?>
                            </div>
                        </div>
                        <div class="col-4 ms-3">
                            <?php
                            echo "<div class='row'>";
                            echo "<p id='other' class='mt-2 text-center fs-auto fw-bold w-100'>";
                            echo $row['quantité'] . " " . "disponibles";
                            echo "</p>";
                            echo "</div>";
                            echo "<div class='row mt-2'>";
                            echo "<button class='btn bg-transparent border-0'>";
                            $sql1 = "SELECT * FROM favori_materiel WHERE idM = ? AND id = ?";
                            // avec idM = $row['idM'] et id = $_SESSION['user']['id']
                            $result1 = $pdo->prepare($sql1);
                            $result1->execute([$row['idM'], $_SESSION['user']['id']]);
                            if ($result1->fetch()) {
                                echo "<img src='../res/heartPlein.svg' alt='favory' onclick='retirerFavoris(" . $row['idM'] . ")'>";
                            } else {
                                echo "<img src='../res/heartVide.svg' alt='favory' onclick='ajouterFavoris(" . $row['idM'] . ")'>";
                            }
                            echo "<span class='ms-2 text-center fs-auto fw-bold w-100'>Favoris</span>";
                            echo "</button>";
                            echo "</div>";
                            ?>
                        </div>
                    </div>

                    <div class="row gap-2 justify-content-center">
                        <div class="col-5 p-5 bg-light  mt-3">
                            PDF Consignes
                        </div>
                        <div class="col-5 p-5 bg-light mt-3">
                            Tuto utilisation
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid my-5">
            <div class="row ms-5">
                <div class="col-sm-4 fs-3 mt-5">
                    Commentaires
                </div>
            </div>
            <?php

            // Préparation de la requête avec UNION
            

            // Récupération des résultats
            
            ?>

            <!--boucle foreach-->
                <div id="com1" class="row ms-0 m-5">
                    <div class="col-sm-10 mt-2 ms-5 border border-secondary rounded p-3">
                        <div class="row ms-5">
                            <div class="col-sm-1"><img src="../img/jinx.png" class='rounded' style="height: 50px; width:auto;" alt="boite mes emprunts"></div>
                            <div class="col-sm-4"> Clarou</div>
                        </div>
                        <div class="row ms-5">
                            <div class="col-sm-2">5 ☆</div>
                            <div class="col-sm-2">10/06/2025</div>
                        </div>
                        <div class="row ms-2 mt-2">
                            <div class="col-sm-10"> Super produit !!</div>
                        </div>
                    </div>
                </div>
            <!--fin boucle foreach-->

            <form action="#" method="post" class="my-5 d-flex align-items-center ms-md-5">
                <textarea class="form-control me-3 p-2 rounded" id="exampleFormControlTextarea1" rows="1"
                    name="commentaire" placeholder="Ecrire un commentaire" style="resize: none; width: 70%;"></textarea>
                <input class="p-2 rounded" type="number" name="reaction" value="5" min="1" max="5" style="width: 60px; margin-right: 5px;"> <span class="fs-3">☆</span>
                <input type="submit" name="submit" class="btn btn-danger ms-2" value="ENVOYER">
            </form>
        </div>
    </main>

    <?php
    if (isset($_POST["submit"])) {
        $commentaire = htmlspecialchars($_POST['commentaire']) ?? '';
        $reaction = htmlspecialchars($_POST['reaction']) ?? '';

        if (!empty($commentaire) && !empty($reaction)) {
            if ($_SESSION['utilisateur']['Td']) {
                // Préparer la requête pour les élèves
                $sql = "INSERT INTO commentaires_eleve(Pseudo, date_comment, commentaire, reaction, materiel) 
                    VALUES (:pseudo, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), :commentaire, :reaction, :materiel)";
                $stmt = $pdo->prepare($sql);

                // Vérifier si la préparation a échoué
                if ($stmt === false) {
                    die("Erreur de préparation de la requête: " . $pdo->errorInfo());
                }

                // Lier les paramètres à la requête
                $stmt->bindParam(':pseudo', $_SESSION['utilisateur']['Pseudo'], PDO::PARAM_STR);
                $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                $stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
                $stmt->bindParam(':materiel', $_GET['id'], PDO::PARAM_STR);

                // Exécuter la requête
                $stmt->execute();
            } else {
                // Préparer la requête pour les professeurs
                $sql = "INSERT INTO commentaires_prof(Pseudo, date_comment, commentaire, reaction) 
                    VALUES (:pseudo, NOW(), :commentaire, :reaction)";
                $stmt = $pdo->prepare($sql);

                // Vérifier si la préparation a échoué
                if ($stmt === false) {
                    die("Erreur de préparation de la requête: " . $conn->errorInfo());
                }

                // Lier les paramètres à la requête
                $stmt->bindParam(':pseudo', $_SESSION['utilisateur']['Pseudo'], PDO::PARAM_STR);
                $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
                $stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);

                // Exécuter la requête
                $stmt->execute();
            }
        }
    }
    ?>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/index.js"></script>
    <script>
        function reserverMateriel(idM) {
            window.location.href = "reservation_materiel.php?idM=" + idM;
        }

        function ajouterFavoris(idM) {
            window.location.href = "../PHPpure/ajouter_favoris.php?idM=" + idM;
        }

        function retirerFavoris(idM) {
            window.location.href = "../PHPpure/retirer_favoris.php?idM=" + idM;
        }
    </script>
</body>

</html>