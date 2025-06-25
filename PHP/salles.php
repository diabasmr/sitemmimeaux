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

    <main class="mt-5 mt-md-auto">
        <p class="mt-5 mt-md-auto">Cliquer sur une image pour entrer en mode 360°.</p>
        <?php if ($_SESSION['user']['role'] == 'Administrateur'): ?>
            <nav class="d-flex justify-content-center mb-3 gap-3">
                <button
                    class="btn btn-sm"
                    onclick="document.getElementById('ajout').classList.remove('d-none');"
                    style=" background-color: #ffd9ec; color: #b30059; border: 1px solid #ff99cc; border-radius: 50px; box-shadow: 0 0 10px rgba(255, 153, 204, 0.4); font-weight: bold;">
                    Ajouter une salle
                </button>
            </nav>
            <div id="ajout" class="d-none position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
                <!-- Carte de présentation -->
                <div class="col-md-4 p-4 bg-white position-relative shadow rounded-4">

                    <form class="salle-form" id="salleAjout" method="POST" action="../PHPpure/ajoutSalle.php"
                        enctype="multipart/form-data">
                        <h2 class="text-center mb-4"
                            style="color: #cc0a74; text-shadow: 0 0 3px #ffcce6;">
                            Détails de la salle
                        </h2>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #99004d;">Nom de la salle</label>
                            <input type="text" name="salle" class="form-control"
                                placeholder="Entrez le nom de la salle"
                                style="color: #4d0033; background-color: transparent;">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #99004d;">Nombre de PC</label>
                            <input type="number" name="nb_pc" class="form-control"
                                placeholder="Entrez le nombre de PC"
                                style="color: #4d0033; background-color: transparent;">
                        </div>

                        <div class="mb-3">
                            <label class="form-label" style="font-weight: 600; color: #99004d;">Nombre de places</label>
                            <input type="number" name="nb_places" class="form-control"
                                placeholder="Entrez le nombre de places"
                                style="color: #4d0033; background-color: transparent;">
                        </div>

                        <div class="mb-2">
                            <label class="form-label" style="font-weight: 600; color: #99004d;">Description</label>
                            <textarea name="description" class="form-control" rows="3"
                                style="color: #4d0033; background-color: transparent;">Entrer une description</textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" style="font-weight: 600; color: #99004d;">Type d'utilisation</label>
                            <input type="text" name="etat" class="form-control"
                                placeholder="Entrez le type d'utilisation"
                                style="color: #4d0033; background-color: transparent;">
                        </div>
                        <div class="mb-5">
                            <label class="form-label" style="font-weight: 600; color: #99004d;">Ajouter une photo</label>
                            <input
                                type="file"
                                name="photo"
                                accept="image/*"
                                class="form-control"
                                style="color: #4d0033; font-weight: 500;">
                        </div>
                        <?php if ($_SESSION['user']['role'] == 'Administrateur'): ?>
                            <nav class="position-absolute bottom-0 end-0 m-3 z-3">
                                <button
                                    class="btn btn-sm"
                                    type="submit"
                                    name="validajout"
                                    onclick="document.getElementById('ajout').classList.add('d-none');"
                                    style="background-color: #ffd9ec; color: #b30059; border: 1px solid #ff99cc; border-radius: 50px; box-shadow: 0 0 10px rgba(255, 153, 204, 0.4); font-weight: bold;">
                                    Valider
                                </button>
                            </nav>
                        <?php endif; ?>
                    </form>
                </div>
            </div>
        <?php endif; ?>


        <?php
        $sql1 = "SELECT * FROM salle";
        $stmt = $pdo->prepare($sql1);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php foreach ($result as $salle): ?>
            <?php $etat = $salle['etat'] ?? 'Indisponible'; ?>

            <div class="mb-5 border-0">
                <div class="row g-0 position-relative">

                    <!-- Image -->
                    <div class="imageContainer col-md-6 p-3">
                        <img
                            class="img-fluid rounded imageToClick"
                            src="../materiel/<?php echo htmlspecialchars($salle['photo']); ?>"
                            data-src="../materiel/<?php echo htmlspecialchars($salle['photo']); ?>"
                            alt="Salle <?php echo htmlspecialchars($salle['nom']); ?>"
                            style="cursor: pointer;">

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <h5 class="mb-0 fw-semibold">Réservation de la <?php echo htmlspecialchars($salle['nom']); ?></h5>
                            <a href="reservation_salle.php?idS=<?php echo $salle['idS']; ?>">
                                <button
                                    class="btn btn-sm <?php echo (strtolower($etat) === 'disponible') ? 'btn-danger' : 'btn-secondary'; ?>"
                                    <?php if (strtolower($etat) !== 'disponible') echo "disabled"; ?>>
                                    <?php echo (strtolower($etat) === 'disponible') ? "Réserver" : "Indisponible"; ?>
                                </button>
                            </a>
                        </div>
                    </div>

                    <!-- Infos salle -->
                    <div class="col-md-6 p-4">

                        <!-- Carte de présentation -->
                        <div class="infosSalle col-md-6 p-4 position-relative shadow rounded-4">
                            <nav class="position-absolute top-0 end-0 m-3 z-3">
                                <button
                                    class="btn btn-sm mb-2"
                                    onclick="modifiersalle(this)"
                                    style="background-color: #ffd9ec; color: #b30059; border: 1px solid #ff99cc; border-radius: 50px; box-shadow: 0 0 10px rgba(255, 153, 204, 0.4); font-weight: bold;">
                                    Modifier
                                </button>
                            </nav>
                            <form class="mt-4 salle-form" id="salleDetails" method="POST" action="../PHPpure/modifierSalle.php"
                                enctype="multipart/form-data">
                                <h2 class="text-center mb-4"
                                    style="font-family: 'Segoe UI', sans-serif; font-weight: 600; color: #cc0a74; text-shadow: 0 0 3px #ffcce6;">
                                    Détails de la salle
                                </h2>

                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #99004d;">Nombre de PC</label>
                                    <input type="number" name="nb_pc" class="form-control-plaintext"
                                        value="<?= htmlspecialchars($salle['nb_pc'] ?? '') ?>" disabled
                                        style="color: #4d0033; background-color: transparent;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #99004d;">Nombre de places</label>
                                    <input type="number" name="nb_places" class="form-control-plaintext"
                                        value="<?= htmlspecialchars($salle['capacite'] ?? '') ?>" disabled
                                        style="color: #4d0033; background-color: transparent;">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label" style="font-weight: 600; color: #99004d;">Description</label>
                                    <textarea name="description" class="form-control-plaintext" rows="3" disabled
                                        style="color: #4d0033; background-color: transparent;"><?= htmlspecialchars($salle['description'] ?? '') ?></textarea>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label" style="font-weight: 600; color: #99004d;">Type d'utilisation</label>
                                    <input type="text" name="typeUse" class="form-control-plaintext"
                                        value="<?= htmlspecialchars($salle['type'] ?? '') ?>" disabled
                                        style="color: #4d0033; background-color: transparent;">
                                </div>
                                <input type="hidden" name="idS" value="<?= htmlspecialchars($salle['idS']) ?>">
                                <?php if ($_SESSION['user']['role'] == 'Administrateur'): ?>
                                    <nav id="valid" class="d-none position-absolute bottom-0 end-0 m-3 z-3">
                                        <button
                                            class="btn btn-sm"
                                            type="submit"
                                            name="validmodifier"
                                            style="background-color: #ffd9ec; color: #b30059; border: 1px solid #ff99cc; border-radius: 50px; box-shadow: 0 0 10px rgba(255, 153, 204, 0.4); font-weight: bold;">
                                            Valider
                                        </button>
                                    </nav>
                                <?php endif; ?>
                            </form>
                        </div>

                    </div>
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

        function modifiersalle(button) {
            const formContainer = button.closest('.infosSalle');
            if (!formContainer) return;

            formContainer.querySelectorAll('input[disabled], textarea[disabled], select[disabled]').forEach(field => {
                field.removeAttribute('disabled');
                field.classList.remove('form-control-plaintext');
                field.classList.add('form-control');
            });
            formContainer.querySelector('#valid').classList.remove('d-none');
            button.style.display = 'none';
        }
    </script>
</body>

</html>