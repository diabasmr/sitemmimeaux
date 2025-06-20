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
    <section class="d-flex flex-column flex-md-row justify-content-between align-items-baseline col-12 mt-5 mt-md-auto rounded bg-white p-3 gap-3">
        <article class="left">
            <h2>Statistiques</h2>
        </article>

        <article class="annee d-flex align-items-center gap-5">
            <button id="left" class="fs-md-3 fs-6">←</button>
            <h4 id="headerDate" class="m-0">2025</h4>
            <button id="right" class="fs-md-3 fs-6">→</button>
        </article>

        <div class="d-flex gap-2 flex-wrap">
            <button class="salles rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="showResas()">Réservations</button>
            <button class="materiel rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="showMateriel()">Matériel</button>
            <button class="salles rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="showSalles()">Salles</button>
        </div>
    </section>
  
        <section id="stats-resa" style="width:100%;">
            <canvas id="Reservations"></canvas>
            <button class="salles rounded p-2 text-white mt-2" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Reservations')">Télécharger en CSV</button>

            <div class="row">
                <div class="col-12 col-md-4 my-4">
                    <canvas id="Validations"></canvas>
                    <button class="salles rounded p-2 text-white mt-2" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Validations')">Télécharger en CSV</button>
                </div>
            </div>
        </section>
  
        <section id="stats-materiel" style="display:none; width:100%;">
            <canvas id="Materiel"></canvas>
            <button class="salles rounded p-2 text-white mt-2" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Materiel');">Télécharger en CSV</button>

            <div class="row mt-5">
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Temporalite1"></canvas>
                    <button class="salles rounded p-2 mt-2 text-white" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Temporalite1');">Télécharger en CSV</button>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Temporalite2"></canvas>
                    <button class="salles rounded p-2 mt-2 text-white" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Temporalite2');">Télécharger en CSV</button>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Temporalite3"></canvas>
                    <button class="salles rounded p-2 mt-2 text-white" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Temporalite3');">Télécharger en CSV</button>
                </div>
            </div>
        </section>

        <section id="stats-salles" style="display:none; width:100%;">
            <canvas id="Salles"></canvas>
            <button class="salles rounded p-2 mt-2 text-white" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Salles');">Télécharger en CSV</button>

            <div class="row mt-5">
                <div class="col-12 col-md-6 mb-4">
                    <canvas id="Temporalite4"></canvas>
                    <button class="salles rounded p-2 mt-2 text-white" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Temporalite4');">Télécharger en CSV</button>
                </div>
                <div class="col-12 col-md-6 mb-4">
                    <canvas id="Temporalite5"></canvas>
                    <button class="salles rounded p-2 mt-2 text-white" style="background-color:#e4587d; border:none;" onclick="exportChartToCSV('Temporalite5');">Télécharger en CSV</button>
                </div>
        </section>
</main>
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/stats.js"></script>
</body>

</html>