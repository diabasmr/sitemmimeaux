<?php
session_start();
require("../PHPpure/connexion.php");
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception; ?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <title>Support et conditions</title>
</head>

<body>
    <?php
    include("header.php");
    if (isset($_SESSION['user'])) {
        include("aside.php");
    }
    ?>

    <main class="mt-5 mt-md-auto">
        <h1 class="mt-5 mt-md-auto text-center text-md-start">Support et Conditions</h1>

        <nav class="py-3 px-4 border rounded-3 shadow-sm w-100 w-md-75 mx-auto" aria-label="breadcrumb">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 gap-md-0">

                <!-- Fil d’Ariane -->
                <ol class="breadcrumb mb-0" style="--bs-breadcrumb-divider: '>'; white-space: nowrap;">
                    <li class="breadcrumb-item">
                        <a href="connexion-compte.php" class="text-decoration-none text-dark">Connexion</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="inscription.php" class="text-decoration-none text-dark">Inscription</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">
                        <a href="index.php" class="text-decoration-none text-dark">ReZoom</a>
                    </li>
                </ol>

                <!-- Boutons -->
                <div class="d-flex flex-wrap justify-content-center justify-content-md-end gap-2">
                    <button type="button" class="p-2 px-3"
                        style="min-width: 160px; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" data-section="1" onclick="showSection('contact')">Nous Contacter</button>
                    <button type="button" class="p-2 px-3"
                        style="min-width: 160px; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" data-section="2" onclick="showSection('mentions')">Mentions légales</button>
                    <button type="button" class="p-2 px-3"
                        style="min-width: 160px; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" data-section="3" onclick="showSection('confidentialite')">Politique de confidentialité</button>
                </div>
            </div>
        </nav>
        <section id="contact" style="display:none; width: 80%; margin: auto;">
            <div class="py-5">
                <h3 class="text-center mb-4">Nous contacter</h1>

                    <form action="../PHPpure/contact.php"
                        method="POST"
                        class="mx-auto p-4 rounded shadow-sm"
                        style="background-color:rgb(255, 255, 255);">

                        <div class="d-flex mb-3 justify-content-around gap-5">
                            <div class="w-100">
                                <label for="nom" class="form-label fw-semibold">Nom</label>
                                <input
                                    class="form-control fs-6 rounded-3"
                                    type="text"
                                    placeholder="Votre nom"
                                    name="nom"
                                    id="nom"
                                    required />
                            </div>

                            <div class="w-100">
                                <label for="prenom" class="form-label fw-semibold">Prénom</label>
                                <input
                                    class="form-control fs-6 rounded-3"
                                    type="text"
                                    placeholder="Votre prénom"
                                    name="prenom"
                                    id="prenom"
                                    required />
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label fw-semibold">Adresse email</label>
                            <input
                                class="form-control fs-6 rounded-3"
                                type="email"
                                placeholder="exemple@email.com"
                                name="email"
                                id="email"
                                required />
                        </div>

                        <div class="mb-3">
                            <label for="sujet" class="form-label fw-semibold">Sujet</label>
                            <select
                                name="sujet"
                                id="sujet"
                                class="form-select fs-6 rounded-3"
                                required>
                                <option value="" disabled selected hidden>Choisissez un sujet</option>
                                <option value="Suggestion">Suggestion</option>
                                <option value="Bug">Bug</option>
                                <option value="Question">Question</option>
                                <option value="Réclamation">Réclamation</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="message" class="form-label fw-semibold">Message</label>
                            <textarea
                                class="form-control fs-6 rounded-3"
                                placeholder="Votre message..."
                                name="message"
                                id="message"
                                rows="5"
                                required></textarea>
                        </div>
                        <p class="text-danger fw-semibold">* Entrer un email valide</p>
                        <p class="text-danger fw-semibold">* Veuillez remplir tous les champs</p>
                        <div class="text-end">
                            <button
                                type="submit"
                                class="btn text-white px-4 py-2 rounded-3"
                                style="background-color: #d72f59;"
                                onmouseover="this.style.backgroundColor='#e47390';"
                                onmouseout="this.style.backgroundColor='#d72f59';">
                                Envoyer
                            </button>
                        </div>
                    </form>
            </div>
        </section>


        <section id="mentions" style="display:none;">blabla</section>
        <section id="confidentialite" style="display:none;">clever blabla</section>
    </main>
    <?php
    if (isset($_POST['submit'])) {
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $sujet = $_POST['sujet'];
        $message = $_POST['message'];

        // Validation basique
        if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($sujet) && !empty($message) && !empty($nom) && !empty($prenom)) {
            //MAIL
            require '../PHPMailer-master/src/PHPMailer.php';
            require '../PHPMailer-master/src/SMTP.php';
            require '../PHPMailer-master/src/Exception.php';

            // Envoi de l'e-mail avec PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuration SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'iut.rezoom@gmail.com';
                $mail->Password = 'obmv hoac gbrw ftwz';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('iut.rezoom@gmail.com', 'ReZoom Support');
                $mail->addAddress('iut.rezoom@gmail.com', 'ReZoom Support');
                $mail->addReplyTo($email, "$nom $prenom");

                $mail->Subject = $sujet;
                $mail->Body = $messsage;

                $mail->send();
            } catch (Exception $e) {
                echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            }
        }
    }
    ?>
    <script>
        function showSection(id) {
            // Masquer toutes les sections
            const allSections = ['contact', 'mentions', 'confidentialite'];
            allSections.forEach(sectionId => {
                const section = document.getElementById(sectionId);
                if (section) section.style.display = 'none';
            });

            // Afficher la section ciblée
            const target = document.getElementById(id);
            if (target) target.style.display = 'block';

            // Optionnel : activer le bouton correspondant
            allSections.forEach(btnId => {
                const btn = document.getElementById(btnId);
                if (btn) btn.classList.remove('active');
            });
            const activeBtn = document.getElementById(id);
            if (activeBtn) activeBtn.classList.add('active');
        }
    </script>

</body>

</html>