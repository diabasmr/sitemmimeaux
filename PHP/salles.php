<?php include("../PHPpure/entete.php"); 
require("../PHPpure/connexion.php");?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/salles.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <title>Réservation VR </title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>

    <main>
        <p>Cliquer sur une image pour entrer en mode VR 360°.</p>

        <!-- Image 1 -->
        <div id="imageContainer1" style="margin-bottom: 20px;">
            <img id="imageToClick1" src="../IMG/image.png" alt="Salle 138" style="width: 60%; cursor: pointer;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 60%; margin-top: 1vh;">
                <h3 style="margin: 0;">Réservation de la salle 138</h3>
                <?php
                    // Connexion déjà faite plus haut normalement
                    $sql1 = "SELECT etat FROM salle WHERE Nom = 'Salle 138'";
                    $stmt = $pdo->prepare($sql1);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $etat = $result['etat'] ?? 'Indisponible'; // Valeur de secours
                ?>
                <a href="reservation_salle.php?salle=138">
                    <button style="background-color: rgba(211, 27, 74, 0.61); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;"
                        <?php if (strtolower($etat) !== 'disponible') echo "disabled"; ?>>
                        <?php echo (strtolower($etat) === 'disponible') ? "Réserver" : "Indisponible"; ?>
                    </button>
                </a>
            </div>
        </div>

        <!-- Image 2 -->
        <div id="imageContainer2">
            <img id="imageToClick2" src="../IMG/image2.jpg" alt="Salle 212" style="width: 60%; cursor: pointer;">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 60%; margin-top: 1vh;">
                <h3 style="margin: 0;">Réservation de la salle 212</h3>

                <?php
                    // Connexion déjà faite plus haut normalement
                    $sql1 = "SELECT etat FROM salle WHERE Nom = 'Salle 212'";
                    $stmt = $pdo->prepare($sql1);
                    $stmt->execute();
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    $etat = $result['etat'] ?? 'Indisponible'; // Valeur de secours
                ?>

                <a href="reservation_salle.php?salle=212">
                    <button 
                        style="background-color: rgba(211, 27, 74, 0.61); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;"
                        <?php if (strtolower($etat) !== 'disponible') echo "disabled"; ?>>
                        <?php echo (strtolower($etat) === 'disponible') ? "Réserver" : "Indisponible"; ?>
                    </button>
                </a>
            </div>
        </div>


        <!-- Scène VR -->
        <div id="vrContainer" style="display: none; width: 100%; height: 100vh; position: fixed; top: 0; left: 0; z-index: 1000;">
            <a-scene id="vrScene" embedded style="width: 100%; height: 100%;" renderer="antialias: true; precision: high">
                <a-sky id="sky" rotation="0 -90 0" material="shader: flat; side: double"></a-sky>
                <a-entity camera look-controls="reverseMouseDrag: true"></a-entity>
            </a-scene>

            <button id="backButton" style="
                position: fixed;
                bottom: 30px;
                right: 30px;
                z-index: 9999;
                padding: 10px 20px;
                background-color: rgba(211, 27, 74, 0.61);
                color: white;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                display: none;">
                Retour
            </button>
        </div>
    </main>

    <script src="../JS/sideBarre.js"></script>

    <script>
        const vrContainer = document.getElementById('vrContainer');
        const sky = document.getElementById('sky');
        const backButton = document.getElementById('backButton');

        function showVR(imageSrc) {
            document.getElementById('imageContainer1').style.display = 'none';
            document.getElementById('imageContainer2').style.display = 'none';
            sky.setAttribute('src', imageSrc);
            vrContainer.style.display = 'block';
            backButton.style.display = 'block';
            document.body.style.overflow = 'hidden';

            // Forcer le plein écran pour une meilleure expérience VR
            if (vrContainer.requestFullscreen) {
                vrContainer.requestFullscreen();
            } else if (vrContainer.webkitRequestFullscreen) {
                vrContainer.webkitRequestFullscreen();
            } else if (vrContainer.msRequestFullscreen) {
                vrContainer.msRequestFullscreen();
            }
        }

        function exitVR() {
            // Sortir du mode plein écran
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }

            vrContainer.style.display = 'none';
            document.getElementById('imageContainer1').style.display = 'block';
            document.getElementById('imageContainer2').style.display = 'block';
            backButton.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        document.getElementById('imageToClick1').addEventListener('click', function() {
            showVR('../IMG/image.png');
        });

        document.getElementById('imageToClick2').addEventListener('click', function() {
            showVR('../IMG/image2.jpg');
        });

        backButton.addEventListener('click', exitVR);
    </script>
</body>

</html>