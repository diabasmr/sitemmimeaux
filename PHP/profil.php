<?php include("../PHPpure/entete.php"); ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/profil.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
    <!-- bootstrap -->
    <title>Profil</title>
</head>

<body>
    <?php
    include("header.php");
    include("aside.php");
    ?>
    <main class="mt-5 ms-3 mt-md-auto">
        <section class="my-5 mt-md-auto profil">
            <h3 class="fs-3 fs-md-auto">Vous êtes <?php echo $_SESSION['user']['role']; ?></h3>
            <div class="profilimg">
                <div class="imgProfilContainer" id="imgProfilContainerMain" onclick="displayUploadForm()">
                    <img class="imgProfil" src="
                        <?php
                        if (isset($_SESSION['user']['profil'])) {
                            if ($_SESSION['user']['profil'] != "") {
                                echo $_SESSION['user']['profil'];
                            } else {
                                echo "../uploads/default.png";
                            }
                        }
                        ?>" alt="">
                    <img class="edit" src="../res/Edit_Pencil_02.svg" alt="">
                </div>
                <form id="uploadForm" class="uploadForm" action="../PHPpure/upload_profile_pic.php" method="post"
                    enctype="multipart/form-data">
                    <div class="upload-box" id="dropZone">
                        <img src="../res/+.svg" alt="" class="upload-icon">
                        <input type="file" name="avatar" id="fileInput" accept="image/*" style="display: none;">
                        <div id="preview"></div>
                    </div>
                    <button type="submit" class="upload-button">Uploader</button>
                </form>
            </div>
            <!-- <p>Ajouter une photo de profil </p> -->
            <hr>
            <div class="details">
                <p class="fs-4 fs-md-1">Détails <img src="../res/edition.svg" alt=""></p>
                <form class="form nomPrenom" action="../PHPpure/profilModification.php" method="post">
                    <div class="nomPrenomInput">
                        <div>
                            <label class="fs-6 fs-md-2" for="nom">Nom</label>
                            <input class="fs-6 fs-md-2" type="text" id="nom" name="nom" value="<?php echo $_SESSION['user']['nom'] ?>">
                        </div>
                        <div>
                            <label for="prenom" class="fs-6 fs-md-2">Prénom</label>
                            <input class="fs-6 fs-md-2" type="text" id="prenom" name="prenom" value="<?php echo $_SESSION['user']['prenom'] ?>">
                        </div>
                    </div>
                    <button class="fs-6 fs-md-2" type="submit" name="modifier_nomPrenom">Modifier</button>

                </form>
                <form class="form email" action="../PHPpure/profilModification.php" method="post">
                    <label class="fs-6 fs-md-2" for="email">Email</label>
                    <input class="fs-6 fs-md-2" type="email" id="email" name="email" value="<?php echo $_SESSION['user']['email'] ?>">
                    <button class="fs-6 fs-md-2" type="submit" name="modifier_email">Modifier</button>
                </form>

                <form class="form tel" action="../PHPpure/profilModification.php" method="post">
                    <label class="fs-6 fs-md-2" for="tel">Téléphone</label>
                    <input class="fs-6 fs-md-2" type="tel" id="tel" name="tel" value="+33  <?php echo $_SESSION['user']['telephone'] ?>">
                    <button class="fs-6 fs-md-2" type="submit" name="modifier_tel">Modifier</button>
                </form>
            </div>
            <form action="../PHPpure/profilModification.php" method="post" class="form password">
                <p class="fs-4 fs-md-1">Mot de passe</p>
                <p class="fs-6 fs-md-2">Modifier mon mot de passe</p>
                <div>
                    <label for="password" class="fs-6 fs-md-2">Mot de passe actuel</label>
                    <input type="password" class="fs-6 fs-md-2" id="password" name="password" required>
                </div>
                <div>
                    <label for="newPassword" class="fs-6 fs-md-2">Nouveau mot de passe</label>
                    <input type="password" class="fs-6 fs-md-2" id="newPassword" name="newPassword" required>
                </div>
                <button type="submit" class="fs-6 fs-md-2" name="modifier_password">Modifier</button>
            </form>
            <form action="../PHPpure/profilModification.php" method="post" class="form other">
                <h1>Autres</h1>
                <div>
                    <label for="adresse" class="fs-6 fs-md-1">Adresse postale</label>
                    <input type="text" class="fs-6 fs-md-1" id="adresse" name="adresse" value="<?php echo $_SESSION['user']['adresse'] ?>">
                </div>
                <?php
                if ($_SESSION['user']['role'] == "Etudiant(e)") {
                ?>
                    <div>
                        <label for="numeroEtudiant ">Numéro étudiant</label>
                        <input type="text" id="numeroEtudiant" name="numeroEtudiant" value="<?php echo $_SESSION['user']['numeroEtudiant'] ?>">
                    </div>
                <?php
                }
                ?>
                <button type="submit" class="fs-6 fs-md-2" name="modifier_other">Modifier</button>
            </form>
        </section>
    </main>
    <script src="../JS/sideBarre.js"></script>
    <script src="../JS/profilchange.js"></script>
</body>

</html>