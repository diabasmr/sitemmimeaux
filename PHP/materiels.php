<?php
include("../PHPpure/entete.php");
?>

<!DOCTYPE html>
<html lang="fr-FR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/materiels.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
    <title>Matériel</title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main>
        <?php
        require_once("../PHPpure/connexion.php");
        // recuperer les favoris
        $sql = "SELECT * FROM favori_materiel WHERE id = ?";
        $result = $pdo->prepare($sql);
        $result->execute([$_SESSION['user']['id']]);
        // si de favoris
        if ($result->fetch()) {

        ?>
            <section class="mt-5 mt-md-auto container-fluid d-flex flex-column gap-5">
                <div>
                    <h3 class="mt-5 mt-md-auto fs-1 fw-bold">Favoris</h3>
                </div>
                <!-- <div class="row justify-content-start align-items-center">
                <div class="col-4 d-flex justify-content-center align-items-center flex-column position-relative w-25">

                    <div
                        class="position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4">
                        <button class="btn bg-transparent border-0">
                            <img src="../res/heartVide.svg" alt="favory">
                        </button>

                    </div>
                    <img src="../IMG/co.png" alt="co" class="w-100 rounded-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center fs-auto fw-bold w-100">Micro - HyperX HX-MICQC-BK QuadCast</p>
                        <button class="btn btn-danger text-white w-50 p-3 ">Réserver</button>
                    </div>
                </div>
            </div> -->

                <div class="row justify-content-start align-items-center">
                    <?php
                    require_once("../PHPpure/connexion.php");
                    // recuperer favori et materiel
                    $sql = "SELECT m.*, f.* FROM favori_materiel f LEFT JOIN materiel m ON f.idM = m.idM WHERE f.id = ?";
                    $result = $pdo->prepare($sql);
                    $result->execute([$_SESSION['user']['id']]);
                    while ($row = $result->fetch()) {

                        echo "<div class='col-6 col-md-4 d-flex justify-content-center align-items-center flex-column position-relative'>";
                        echo "<div class='position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4'>";
                        echo "<button class='btn bg-transparent border-0'>";
                        echo "<img src='../res/heartPlein.svg' alt='favory' onclick='retirerFavoris(" . $row['idM'] . ")'>";
                        echo "</button>";
                        echo "</div>";
                        echo "<div class='position-absolute top-0 start-0 d-flex justify-content-between align-items-center gap-3 p-4'>";
                        echo "<p id='other' class='text-white rounded p-2 border-0' style='background-color:#e4587d;'>";
                        echo $row['quantité'] . " " . "disponibles";
                        echo "</p>";
                        echo "<p id='phone' class='text-white rounded p-2 border-0' style='background-color:#e4587d;'>";
                        echo $row['quantité'];
                        echo "</p>";
                        echo "</div>";
                        echo "<img src='../materiel/" . $row['photo'] . "' alt='materiel' class='w-100 rounded-5 materiel-image'>";
                        echo "<div class='d-flex justify-content-center align-items-center flex-column w-100'>";
                        echo "<a class='text-center fs-auto fw-bold w-100' style='text-decoration:none; color:black;' href='produit.php?id=". $row['idM']. "'>" . $row['designation'] . "</a>";
                        echo "<button class='btn btn-danger text-white text-center w-80 w-md-50 p-3' onclick='reserverMateriel(" . $row['idM'] . ")'";

                        if ($row['quantité'] == 0) {
                            echo " disabled";
                        }

                        echo ">Réserver</button>";
                        echo "</div>";
                        echo "</div>";
                    }
                    ?>
                </div>



            </section>
        <?php
        }
        ?>

        <section class="mt-5 mt-md-auto container-fluid d-flex flex-column gap-5 mb-5">
            <div class="mt-5 mt-md-auto d-flex align-items-center justify-content-md-between">
                <h3 class="fs-1 fw-bold w-50 w-md-auto">Tous</h3>
                <div class="d-flex justify-content-between align-items-center w-md-100 gap-3">
                    <input type="search" placeholder="Rechercher" class="form-control w-100 w-md-50 p-3 search-input">
                    <button class="search-btn btn p-3"><img src="../res/search.svg" alt="search"></button>
                </div>
            </div>
            <!-- flex wrap qui change de 3 en 2 en 1 en dessous de 768px -->
            <div class="row justify-content-start align-items-baseline g-5 ">
                <!-- position relative -->
                <!-- <div class="col-4 d-flex justify-content-center align-items-center flex-column position-relative w-25">
                   
                    <div
                        class="position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4">
                        <button class="btn bg-transparent border-0">
                            <img src="../res/heartVide.svg" alt="favory">
                        </button>

                    </div>
                    <img src="../IMG/co.png" alt="co" class="w-100 rounded-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center fs-auto fw-bold w-100">Micro - HyperX HX-MICQC-BK QuadCast</p>
                        <button class="btn btn-danger text-white w-50 p-3 ">Réserver</button>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center flex-column position-relative w-25">
                   
                    <div
                        class="position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4">
                        <button class="btn bg-transparent border-0">
                            <img src="../res/heartVide.svg" alt="favory">
                        </button>

                    </div>
                    <img src="../IMG/co.png" alt="co" class="w-100 rounded-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center fs-auto fw-bold w-100">Micro - HyperX HX-MICQC-BK QuadCast</p>
                        <button class="btn btn-danger text-white w-50 p-3 ">Réserver</button>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center flex-column position-relative w-25">
                   
                    <div
                        class="position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4">
                        <button class="btn bg-transparent border-0">
                            <img src="../res/heartVide.svg" alt="favory">
                        </button>

                    </div>
                    <img src="../IMG/co.png" alt="co" class="w-100 rounded-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center fs-auto fw-bold w-100">Micro - HyperX HX-MICQC-BK QuadCast</p>
                        <button class="btn btn-danger text-white w-50 p-3 ">Réserver</button>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center flex-column position-relative w-25">
                   
                    <div
                        class="position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4">
                        <button class="btn bg-transparent border-0">
                            <img src="../res/heartVide.svg" alt="favory">
                        </button>

                    </div>
                    <img src="../IMG/co.png" alt="co" class="w-100 rounded-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center fs-auto fw-bold w-100">Micro - HyperX HX-MICQC-BK QuadCast</p>
                        <button class="btn btn-danger text-white w-50 p-3 ">Réserver</button>
                    </div>
                </div>
                <div class="col-4 d-flex justify-content-center align-items-center flex-column position-relative w-25">
                   
                    <div
                        class="position-absolute top-0 end-0 d-flex justify-content-between align-items-center gap-3 p-4">
                        <button class="btn bg-transparent border-0">
                            <img src="../res/heartVide.svg" alt="favory">
                        </button>

                    </div>
                    <img src="../IMG/co.png" alt="co" class="w-100 rounded-5">
                    <div class="d-flex justify-content-center align-items-center flex-column">
                        <p class="text-center fs-auto fw-bold w-100">Micro - HyperX HX-MICQC-BK QuadCast</p>
                        <button class="btn btn-danger text-white w-50 p-3 ">Réserver</button>
                    </div>
                </div> -->
                <?php
                require_once("../PHPpure/connexion.php");
                // recuperer les img dans ../materiel/[photop]
                $sql = "SELECT * FROM materiel";
                $result = $pdo->query($sql);
                while ($row = $result->fetch()) {
                    echo "<div class='col-6 col-md-4 d-flex justify-content-center align-items-center flex-column position-relative'>";
                    echo "<div class='position-absolute top-0 end-0 d-flex justify-content-between align-items-center p-4'>";
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
                    echo "</button>";
                    echo "</div>";
                    echo "<div class='position-absolute top-0 start-0 ms-3 d-flex justify-content-between align-items-center gap-3 p-4'>";
                        echo "<p id='other' class='text-white rounded p-2 border-0' style='background-color:#e4587d;'>";
                        echo $row['quantité'] . " " . "disponibles";
                        echo "</p>";
                        echo "<p id='phone' class='text-white rounded p-2 border-0' style='background-color:#e4587d;'>";
                        echo $row['quantité'];
                        echo "</p>";
                        echo "</div>";
                    // img meme taille que la div
                    echo "<img src='../materiel/" . $row['photo'] . "' alt='materiel' class='rounded-5 materiel-image'>";
                    echo "<div class='d-flex justify-content-center align-items-center flex-column w-100'>";
                    echo "<a class='text-center fs-auto fw-bold w-100' style='text-decoration:none; color:black;' href='produit.php?id=". $row['idM']. "'>" . $row['designation'] . "</a>";
                    echo "<button class='btn btn-danger text-white text-center w-80 w-md-50 p-3' onclick='reserverMateriel(" . $row['idM'] . ")'";

                    if ($row['quantité'] == 0) {
                        echo " disabled";
                    }

                    echo ">Réserver</button>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </section>
    </main>
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