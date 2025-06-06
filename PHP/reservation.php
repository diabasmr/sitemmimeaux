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

    <title>Réservation</title>
</head>

<body>
    <!-- width 100%-->
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main class="mt-5 mt-md-auto">
        <section class="mt-5 mt-md-auto calendarHeader">
            <article class="left">
                <p id="headerDate">Avril 2025</p>
                <div class="buttoncontainer">
                    <button class="left" id="left">
                        <img src="../res/left.svg" alt="" /></button>
                    <button class="right" id="right">
                        <img src="../res/right.svg" alt="" />
                    </button>
                </div>
            </article>
            <button class="reserve" onclick="window.location.href='materiels.php'">Réserver</button>
        </section>
        <div class="calendarContainer">
            <div class="calendarheader">
                <button onclick="prev()" id="prevM">
                    <img src="../res/chevronleftcalen.svg" alt="" />
                </button>
                <p id="dates"></p>
                <button onclick="next()" id="nextM">
                    <img src="../res/chevronrightcalen.svg" alt="" />
                </button>
            </div>
            <div class="calendar" id="calendar"></div>
        </div>

    </main>

    <!-- JS -->
    <script src="
        https://cdn.jsdelivr.net/npm/fullcalendar@6.1.17/index.global.min.js
        "></script>
    <script></script>
    <script src="../JS/calendar.js"></script>
    <script src="../JS/sideBarre.js"></script>
</body>

</html>