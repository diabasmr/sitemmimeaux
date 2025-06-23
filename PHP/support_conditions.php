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
        <section id="contact" style="width: 80%; margin: auto;">
            <div class="py-5">
                <h2 class="text-center fw-bold" style="color: #d72f59;">Nous contacter</h2>

                <form action=" #"
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
                    <p style="display:none" class="text-danger fw-semibold">* Veuillez remplir tous les champs</p>
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


        <section id="mentions" style="display:none; width: 80%; margin: auto;">
            <div class="py-5">
                <h2 class="text-center fw-bold" style="color: #d72f59;">Mentions légales</h2>

                <div class="bg-white p-4 p-md-5 rounded shadow-sm fs-6">
                    <h2 class="h5 fw-semibold mb-3">Éditeur du site</h2>
                    <p>
                        Site : <strong>ReZoom</strong><br>
                        Responsable de la publication : Samoura Diaba<br>
                        Contact : iut.rezoom@gmail.com
                    </p>

                    <h2 class="h5 fw-semibold mt-4 mb-3">Hébergement</h2>
                    <p>
                        Hébergeur : O2Switch<br>
                        Adresse : 222 Boulevard Gustave Flaubert, 63000 Clermont-Ferrand, France<br>
                        Téléphone : 04 44 44 60 40
                    </p>

                    <h2 class="h5 fw-semibold mt-4 mb-3">Propriété intellectuelle</h2>
                    <p>
                        Tous les contenus présents sur le site <strong>ReZoom</strong> (textes, images, logos, etc.) sont protégés par les lois en vigueur sur la propriété intellectuelle.
                        Toute reproduction ou représentation totale ou partielle est interdite sans autorisation préalable.
                    </p>

                    <h2 class="h5 fw-semibold mt-4 mb-3">Données personnelles</h2>
                    <p>
                        Conformément au RGPD, vous disposez d’un droit d’accès, de rectification et de suppression des données vous concernant.
                        Pour toute demande, contactez-nous à l’adresse : <strong>iut.rezoom@gmail.com</strong>.
                    </p>

                    <!--<h2 class="h5 fw-semibold mt-4 mb-3">Cookies</h2>
                    <p>
                        Ce site peut utiliser des cookies à des fins de statistiques ou de fonctionnement.
                        Vous pouvez configurer votre navigateur pour refuser leur utilisation.
                    </p>
-->

                    <h2 class="h5 fw-semibold mt-4 mb-3">Crédits</h2>
                    <p>
                        Design & développement :
                    <ul>
                        <li><strong>Diaba Samoura</strong></li>
                        <li><strong>Charly Janvier</strong></li>
                        <li><strong>Soumiyya Gbadagni</strong></li>
                        <li><strong>Laura Lebreton</strong></li>
                    </ul><br>
                    Images / illustrations : Diaba Samoura, Charly Janvier, Gbadagni, Laura Lebreton.<br>
                    </p>
                </div>
            </div>
        </section>

        <section id="confidentialite" style="display:none; width: 80%; margin: auto;">
            <div class="py-5">
                <h2 class="text-center fw-bold" style="color: #d72f59;">Politique de confidentialité</h2>

                <div class="bg-white p-4 p-md-5 rounded shadow-sm fs-6">
                    <p class="mb-3">
                        Cette politique de confidentialité a pour but de vous informer de manière transparente sur la manière dont nous collectons, utilisons et protégeons vos données personnelles.
                    </p>

                    <h2 class="fs-5 mt-4">1. Données collectées</h2>
                    <p>
                        Lorsque vous remplissez notre formulaire de contact, nous collectons les données suivantes :
                    </p>
                    <ul>
                        <li>Votre adresse e-mail</li>
                        <li>Le sujet de votre message</li>
                        <li>Le contenu de votre message</li>
                    </ul>
                    <p>Ces informations sont strictement utilisées pour répondre à votre demande.</p>

                    <h2 class="fs-5 mt-4">2. Stockage des données</h2>
                    <p>
                        Vos données sont stockées de manière sécurisée sur notre serveur hébergé chez :
                        <br>
                        <strong>O2Switch – 222 Boulevard Gustave Flaubert, 63000 Clermont-Ferrand</strong>.
                    </p>

                    <h2 class="fs-5 mt-4">3. Cookies</h2>
                    <p>
                        Ce site n’utilise pas de cookies à des fins publicitaires ou analytiques. Seuls des cookies strictement nécessaires au bon fonctionnement du site peuvent être utilisés (ex : cookie de session).
                    </p>

                    <h2 class="fs-5 mt-4">4. Vos droits</h2>
                    <p>
                        Conformément au RGPD, vous disposez des droits suivants :
                    </p>
                    <ul>
                        <li>Droit d'accès à vos données personnelles</li>
                        <li>Droit de rectification</li>
                        <li>Droit à l'effacement ("droit à l’oubli")</li>
                        <li>Droit d’opposition</li>
                        <li>Droit à la portabilité</li>
                    </ul>
                    <p>
                        Pour exercer vos droits, vous pouvez nous contacter via le formulaire de contact.
                    </p>

                    <h2 class="fs-5 mt-4">5. Responsable du traitement</h2>
                    <p>
                        Le responsable du traitement des données est l’équipe du site ReZoom. Vous pouvez nous écrire via la page “Nous contacter”.
                    </p>

                    <h2 class="fs-5 mt-4">6. Sécurité</h2>
                    <p>
                        Nous mettons tout en œuvre pour assurer la sécurité de vos données, en limitant les accès et en sécurisant notre serveur. Les mots de passe sont stockés sous forme chiffrée.
                    </p>

                    <h2 class="fs-5 mt-4">7. Données des formulaires d’inscription et de connexion</h2>
                    <p>
                        Lors de votre inscription sur notre site, nous collectons :
                    </p>
                    <ul>
                        <li>Nom et prénom</li>
                        <li>Pseudonyme</li>
                        <li>Date de naissance</li>
                        <li>Numéro de téléphone</li>
                        <li>Adresse e-mail</li>
                        <li>Mot de passe (crypté)</li>
                        <li>Date d’inscription</li>
                    </ul>
                    <p>
                        Ces informations sont nécessaires pour créer et gérer votre compte utilisateur. En vous connectant, nous collectons également votre adresse e-mail et votre mot de passe afin de vérifier vos identifiants. Un cookie de session temporaire est utilisé pour maintenir votre connexion.
                    </p>

                    <h2 class="fs-5 mt-4">8. Données liées aux réservations</h2>
                    <p>
                        Lorsque vous effectuez une réservation de salle ou de matériel, nous collectons :
                    </p>
                    <ul>
                        <li>La date et l’heure de la réservation</li>
                        <li>Le type de ressource réservée (salle, matériel, etc.)</li>
                        <li>Votre identifiant utilisateur</li>
                        <li>La signature électronique ou le nom associé à la réservation</li>
                    </ul>
                    <p>
                        Ces données nous permettent de garantir le suivi, la traçabilité et la bonne gestion des demandes de réservation.
                    </p>

                    <p class="mt-5 text-muted small text-end">
                        Dernière mise à jour : juin 2025
                    </p>
                </div>
            </div>
        </section>

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
        email = document.getElementById('email');
        nom = document.getElementById('nom');
        prenom = document.getElementById('prenom');
        msg = document.querySelectorAll('textarea');
        select = document.querySelector('select');
        submit = document.querySelector('button[type="submit"]');

        submit.addEventListener('click', function(event) {
            alerte = document.querySelector('p.text-danger');
            // Vérification des champs avant l'envoi
            if (email.value.trim() === '' || nom.value.trim() === '' || prenom.value.trim() === '' || select.value === '' || Array.from(msg).some(textarea => textarea.value.trim() === '')) {
                alerte.style.display = 'block'; // Affiche le message d'erreur
                event.preventDefault(); // Empêche l'envoi du formulaire
            }
        });

        email.addEventListener('input', function() {
            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (emailPattern.test(email.value)) {
                email.classList.remove('is-invalid');
                email.classList.add('is-valid');
            } else {
                email.classList.remove('is-valid');
                email.classList.add('is-invalid');
            }
        });


        nom.addEventListener('input', function() {
            if (nom.value.trim() !== '') {
                nom.classList.remove('is-invalid');
                nom.classList.add('is-valid');
            } else {
                nom.classList.remove('is-valid');
                nom.classList.add('is-invalid');
            }
        });

        prenom.addEventListener('input', function() {
            if (prenom.value.trim() !== '') {
                prenom.classList.remove('is-invalid');
                prenom.classList.add('is-valid');
            } else {
                prenom.classList.remove('is-valid');
                prenom.classList.add('is-invalid');
            }
        });

        msg.forEach(function(textarea) {
            textarea.addEventListener('input', function() {
                if (textarea.value.trim() !== '') {
                    textarea.classList.remove('is-invalid');
                    textarea.classList.add('is-valid');
                } else {
                    textarea.classList.remove('is-valid');
                    textarea.classList.add('is-invalid');
                }
            });
        });

        select.addEventListener('change', function() {
            if (select.value !== '') {
                select.classList.remove('is-invalid');
                select.classList.add('is-valid');
            } else {
                select.classList.remove('is-valid');
                select.classList.add('is-invalid');
            }
        });

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