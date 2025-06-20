<?php //include("../PHPpure/entete.php"); 
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
    <link rel="stylesheet" href="../CSS/header.css" />
    <script src="https://aframe.io/releases/1.7.0/aframe.min.js"></script>
    <title>Réservation VR </title>
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
                    style="min-width: 160px; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;"data-section="2" onclick="showSection('mentions')">Mentions légales</button>
                <button type="button" class="p-2 px-3"
                    style="min-width: 160px; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;" data-section="3" onclick="showSection('confidentialite')">Politique de confidentialité</button>
            </div>
        </div>
    </nav>
    <section id="contact" style="display:none;">hi</section>
    <section id="mentions" style="display:none;">blabla</section>
    <section id="confidentialite" style="display:none;">clever blabla</section>
</main>
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