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
    <!-- width 100%-->
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
            <button class="salles rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="document.getElementById('stats-resa').style.display = 'block'; document.getElementById('stats-materiel').style.display = 'none'; document.getElementById('stats-salles').style.display = 'none';">Réservations</button>
                <button class="materiel rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="document.getElementById('stats-resa').style.display = 'none'; document.getElementById('stats-materiel').style.display = 'block'; document.getElementById('stats-salles').style.display = 'none';">Matériel</button>
                <button class="salles rounded p-2 text-white" style="background-color:#e4587d; border:none;" onclick="document.getElementById('stats-resa').style.display = 'none'; document.getElementById('stats-materiel').style.display = 'none'; document.getElementById('stats-salles').style.display = 'block';">Salles</button>
            </div>
        </section>
  
        <section id="stats-resa">
            <h4>Statistiques temporelles des réservations</h4>
            <canvas id="Reservations"></canvas>

            <div class="row">
                <div class="col-12 col-md-4 mb-4">
                    <canvas id="Validations"></canvas>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <canvas id=""></canvas>
                </div>
                <div class="col-12 col-md-4 mb-4">
                    <canvas id=""></canvas>
                </div>
            </div>
        </section>
  
        <section id="stats-materiel" style="display:none;">
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
        </section>

        <section id="stats-salles" style="display:none;">
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
        </section>
</main>
    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>var ctx = document.getElementById('Reservations').getContext('2d');
var chart = new Chart(ctx, {
    // The type of chart we want to create
    type: 'line',

    // The data for our dataset
    data: {
        labels: ['Septembre', 'Octobre','Novembre', 'Décembre', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin'],
        datasets: [{
            label: 'Total des Réservations',
            backgroundColor: 'rgba(255, 99, 133, 0.65)',
            borderColor: 'rgb(255, 99, 133)',
            data: [22, 22, 60, 57, 34, 22, 20, 8, 27, 70, 60, 8]
        },{
            label: 'Enseignant(e)s',
            backgroundColor: 'rgba(15, 81, 124, 0.49)',
            borderColor: 'rgb(15, 81, 124',
            data: [20, 16, 40, 7, 0, 2, 10 , 3, 20, 30, 10, 3]
        },{
            label: 'Etudiant(e)s',
            backgroundColor: 'rgba(219, 162, 136, 0.49)',
            borderColor: 'rgb(219, 162, 136)',
            data: [2, 6, 20, 50, 34, 20, 10, 5, 7, 40, 50, 5]
        }]
    },

    // Configuration options go here
    options: {}
});

var ctx4 = document.getElementById('Validations').getContext('2d'); 
var myDoughnutChart4 = new Chart(ctx4, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [5, 15, 2, 25],
            backgroundColor: [
                '#9ed28e',
                '#d33859',
                '#eabf70',
                '#8b9fbe'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['Acceptée', 'Refusée', 'En attente', 'Terminée']
    },
    options: { responsive: true }
});

//MATERIEL
var ctx2 = document.getElementById('Materiel').getContext('2d');
var myBarChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Camera','Acessoire', 'Vidéo', 'Audio', 'Drone', 'AR/VR', 'Graphisme'],
        datasets: [{
            label: 'Etudiant(e)s en 1ere année',
            backgroundColor: 'rgba(255, 99, 133, 0.65)',
            borderColor: 'rgb(255, 99, 133)',
            data: [22, 22, 60, 57, 34, 22, 20, 8, 27, 70, 60, 8]
        },{
            label: 'Etudiant(e)s en 2eme année',
            backgroundColor: 'rgba(15, 81, 124, 0.49)',
            borderColor: 'rgb(15, 81, 124',
            data: [20, 16, 40, 7, 0, 2, 10 , 3, 20, 30, 10, 3]
        },{
            label: 'Etudiant(e)s en 3eme année',
            backgroundColor: 'rgba(219, 162, 136, 0.49)',
            borderColor: 'rgb(219, 162, 136)',
            data: [2, 6, 20, 50, 34, 20, 10, 5, 7, 40, 50, 5]
        }]
    },
    options: {}
});

var ctx3 = document.getElementById('Temporalite1').getContext('2d'); 
var myDoughnutChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                '#a38bbe',
                '#dc88bf',
                '#dcab88'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['1 heure', '2-3 heures', 'Plus de 4 heures']
    },
    options: { responsive: true }
});

var ctx3 = document.getElementById('Temporalite2').getContext('2d'); 
var myDoughnutChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                '#a38bbe',
                '#dc88bf',
                '#dcab88'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['1 heure', '2-3 heures', 'Plus de 4 heures']
    },
    options: { responsive: true }
});

var ctx3 = document.getElementById('Temporalite3').getContext('2d'); 
var myDoughnutChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                '#a38bbe',
                '#dc88bf',
                '#dcab88'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['1 heure', '2-3 heures', 'Plus de 4 heures']
    },
    options: { responsive: true }
});

//SALLES
var ctx2 = document.getElementById('Salles').getContext('2d');
var myBarChart = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['Camera','Acessoire', 'Vidéo', 'Audio', 'Drone', 'AR/VR', 'Graphisme'],
        datasets: [{
            label: 'Etudiant(e)s en 1ere année',
            backgroundColor: 'rgba(255, 99, 133, 0.65)',
            borderColor: 'rgb(255, 99, 133)',
            data: [22, 22, 60, 57, 34, 22, 20, 8, 27, 70, 60, 8]
        },{
            label: 'Etudiant(e)s en 2eme année',
            backgroundColor: 'rgba(15, 81, 124, 0.49)',
            borderColor: 'rgb(15, 81, 124',
            data: [20, 16, 40, 7, 0, 2, 10 , 3, 20, 30, 10, 3]
        },{
            label: 'Etudiant(e)s en 3eme année',
            backgroundColor: 'rgba(219, 162, 136, 0.49)',
            borderColor: 'rgb(219, 162, 136)',
            data: [2, 6, 20, 50, 34, 20, 10, 5, 7, 40, 50, 5]
        }]
    },
    options: {}
});

var ctx3 = document.getElementById('Temporalite4').getContext('2d'); 
var myDoughnutChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                '#a38bbe',
                '#dc88bf',
                '#dcab88'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['1 heure', '2-3 heures', 'Plus de 4 heures']
    },
    options: { responsive: true }
});

var ctx3 = document.getElementById('Temporalite5').getContext('2d'); 
var myDoughnutChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                '#a38bbe',
                '#dc88bf',
                '#dcab88'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['1 heure', '2-3 heures', 'Plus de 4 heures']
    },
    options: { responsive: true }
});

var ctx3 = document.getElementById('Temporalite6').getContext('2d'); 
var myDoughnutChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        datasets: [{
            data: [10, 20, 30],
            backgroundColor: [
                '#a38bbe',
                '#dc88bf',
                '#dcab88'
            ],
            borderColor: '#fff',
            borderWidth: 2
        }],
        labels: ['1 heure', '2-3 heures', 'Plus de 4 heures']
    },
    options: { responsive: true }
});
</script>
    
    <script src="../JS/sideBarre.js"></script>
</body>

</html>