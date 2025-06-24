<?php
include("../PHPpure/entete.php");
require("../PHPpure/connexion.php");
$error = '';
if (isset($_SESSION['error'])) {
    $error = $_SESSION['error'];
    unset($_SESSION['error']);
}
?>

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
        <?php
        $sql1 = "SELECT * FROM salle";
        $stmt = $pdo->prepare($sql1);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php foreach ($result as $salle): ?>
            <?php $etat = $salle['etat'] ?? 'Indisponible'; ?>
            <div class="imageContainer" style="margin-bottom: 20px;">
                <img
                    class="imageToClick"
                    src="../materiel/<?php echo htmlspecialchars($salle['photo']); ?>"
                    data-src="../materiel/<?php echo htmlspecialchars($salle['photo']); ?>"
                    alt="Salle <?php echo htmlspecialchars($salle['nom']); ?>"
                    style="width: 60%; cursor: pointer;">
                <div style="display: flex; justify-content: space-between; align-items: center; width: 60%; margin-top: 1vh;">
                    <h3 style="margin: 0;">Réservation de la <?php echo htmlspecialchars($salle['nom']); ?></h3>
                    <a href="reservation_salle.php?idS=<?php echo $salle['idS']; ?>">
                        <button
                            style="background-color: rgba(211, 27, 74, 0.61); color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer;"
                            <?php if (strtolower($etat) !== 'disponible') echo "disabled"; ?>>
                            <?php echo (strtolower($etat) === 'disponible') ? "Réserver" : "Indisponible"; ?>
                        </button>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>


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

    <?php if (!empty($error)) : ?>
        <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
                <h5 class="mb-3 fw-semibold text-dark">Confirmation</h5>
                <p class="mb-1"><?= htmlspecialchars($error) ?></p>
                <p class="text-muted mb-4">Un mail de confirmation vous sera envoyé lors de sa validation.</p>
                <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
            </div>
        </div>
    <?php endif; ?>

    <script src="../JS/sideBarre.js"></script>

    <script>
        const vrContainer = document.getElementById('vrContainer');
        const sky = document.getElementById('sky');
        const backButton = document.getElementById('backButton');
        const imageContainers = document.querySelectorAll('.imageContainer');
        const images = document.querySelectorAll('.imageToClick');

        function showVR(imageSrc) {
            imageContainers.forEach(container => container.style.display = 'none');
            sky.setAttribute('src', imageSrc);
            vrContainer.style.display = 'block';
            backButton.style.display = 'block';
            document.body.style.overflow = 'hidden';

            if (vrContainer.requestFullscreen) {
                vrContainer.requestFullscreen();
            } else if (vrContainer.webkitRequestFullscreen) {
                vrContainer.webkitRequestFullscreen();
            } else if (vrContainer.msRequestFullscreen) {
                vrContainer.msRequestFullscreen();
            }
        }

        function exitVR() {
            if (document.exitFullscreen) {
                document.exitFullscreen();
            } else if (document.webkitExitFullscreen) {
                document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
                document.msExitFullscreen();
            }

            vrContainer.style.display = 'none';
            imageContainers.forEach(container => container.style.display = 'block');
            backButton.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        images.forEach(image => {
            image.addEventListener('click', () => {
                const src = image.getAttribute('data-src');
                showVR(src);
            });
        });

        backButton.addEventListener('click', exitVR);
    </script>
</body>

</html>