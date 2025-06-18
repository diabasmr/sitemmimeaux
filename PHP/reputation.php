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

    <link rel="stylesheet" href="../CSS/header.css" />
    <link rel="stylesheet" href="../CSS/reservation.css" />

    <title>Statistiques</title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main class="mt-5 mt-md-auto">
        <section class="d-flex justify-content-between col-12 mt-5 mt-md-auto rounded bg-white p-3">
            <article class="left">
                <h2>Statistiques</h2>
            </article>
            <div>
            <button class="salles rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="showResas()">Réservations</button>
                <button class="materiel rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="showMateriel()">Matériel</button>
                <button class="salles rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="showSalles()">Salles</button>
            </div>
        </section>
  
        <section id="stats-resa" style="width:100%;">
            <h4>Statistiques temporelles des réservations</h4>
            <canvas id="Reservations"></canvas>

            <div class="row">
                <div class="col-12 col-md-4 my-4">
                    <canvas id="Validations"></canvas>
                </div>
            </div>
            <button id="downloadCSV">Télécharger les statistiques en CSV</button>
        </section>
  
        <section id="stats-materiel" style="display:none; width:100%;">
            <h4>Statistiques quantitative de l'utilisation du matériel</h4>
            <canvas id="Materiel"></canvas>

            <div class="row">
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Temporalite1"></canvas>
                    <b>Matériel 1</b>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Temporalite2"></canvas>
                    <b>Matériel 2</b>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Temporalite3"></canvas>
                    <b>Matériel 3</b>
                </div>
            </div>
            <button id="downloadCSV">Télécharger les statistiques en CSV</button>
        </section>

        <section id="stats-salles" style="display:none; width:100%;">
            <h4>Statistiques quantitative de l'utilisation des salles</h4>
            <canvas id="Salles"></canvas>

            <div class="row">
                <div class="col-12 col-md-6 mb-4">
                    <canvas id="Temporalite4"></canvas>
                    <b>Salle 138</b>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    <canvas id="Temporalite5"></canvas>
                    <b>Salle 212</b>
                </div>
                <button id="downloadCSV">Télécharger les statistiques en CSV</button>
        </section>
</main>
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/stats.js"></script>
</body>

</html>