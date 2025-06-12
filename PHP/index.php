<?php
include("../PHPpure/entete.php");
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
    <title>Accueil</title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main>
        <?php
        if (isset($_SESSION['user'])) {
            if ($_SESSION['user']['role'] == "Etudiant(e)") {
                include("index_etudiant.php");
            } else if ($_SESSION['user']['role'] == "Enseignant(e)") {
                include("index_enseignant.php");
            } else if ($_SESSION['user']['role'] == "Agent(e)") {
                include("index_agent.php");
            } else if ($_SESSION['user']['role'] == "Administrateur") {
                include("index_admin.php");
            }
        }
        ?>
    </main>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/index.js"></script>
</body>

</html>