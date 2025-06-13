<?php 
$dt = time();
$il_y_a_16_ans = date("Y-m-d", $dt- 31536000*16);
$il_y_a_100_ans = date("Y-m-d", $dt - 31536000*100);

 ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link rel="stylesheet" href="../CSS/style.css">
    <link rel="stylesheet" href="../CSS/inscription.css">
    <!-- <link rel="stylesheet" href="../CSS/header.css"> -->
    <title>Inscription</title>
</head>

<body>
    <main>
        <section>
            <div class="formContainer">
                <h1 id="formTitle" class="fs-3 fs-md-1">Créez votre compte</h1>
                <form id="inscriptionForm" action="../PHPpure/inscriptionUser.php" method="post">
                    <!-- Step 1 -->
                    <div class="step" id="step1">
                        <label for="nom" class="fs-6 fs-md-2">Nom *</label>
                        <input type="text" class="fs-6 fs-md-2" placeholder="Nom" name="nom" id="nom" required />
                        <label for="prenom" class="fs-6 fs-md-2">Prénom *</label>
                        <input type="text" class="fs-6 fs-md-2" placeholder="Prénom" name="prenom" id="prenom" required />
                        <label for="pseudo" class="fs-6 fs-md-2">Pseudo <span class="retenir">(à retenir)</span></label>
                        <input type="text" class="fs-6 fs-md-2" placeholder="prénom.nom" name="pseudo" id="pseudo" required readonly /> <br>
                        <br><button type="button" class="fs-6 fs-md-2" onclick="nextStep('step1', 'step2', ['nom', 'prenom'])">Continuer</button>
                    </div>
                    <!-- Step 2 -->
                    <div class="step" id="step2" style="display: none;">
                        <label for="date_naissance" class="fs-6 fs-md-2">Date de naissance *</label>
                        <div style="position:relative; width:100%;">
                        <input class="fs-6 fs-md-2" type="date" min="<?=$il_y_a_100_ans?>" max="<?=$il_y_a_16_ans?>" name="date_naissance" id="date_naissance" style="padding-right:2.5em;" required />
                            <span class="calendar">
                                <img src="../res/calendar.svg" alt="">
                            </span>
                        </div>
                        <label for="adresse" class="fs-6 fs-md-2">Adresse postale</label>
                        <input type="text" class="fs-6 fs-md-2" placeholder="Ex : 1 rue de la paix, 75000 Paris" name="adresse" id="adresse" />
                        <label for="email" class="fs-6 fs-md-2">Email *</label>
                        <input class="fs-6 fs-md-2" type="email" placeholder="mail@exemple.com" name="email" id="email" required /><br>
                        <br><button class="fs-6 fs-md-2" type="button" onclick="nextStep('step2', 'step3', ['date_naissance', 'email'])">Continuer</button>
                        <button class="fs-6 fs-md-2" type="button" onclick="prevStep('step2', 'step1')">Retour</button>
                    </div>
                    <!-- Step 3 -->
                    <div class="step" id="step3" style="display: none;">
                        <label class="fs-6 fs-md-2" for="mdp">Mot de passe *</label>
  
                        <div class="box">
                            <input class="fs-6 fs-md-2" type="password" minlength="8"
                            pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$" placeholder="Ex : Mot2passe!" name="mdp" id="mdp" required />
                            <span class="voir toggle-password" onclick="voirmdp()">
                                <img class="voirimg" src="../res/eye-slash-regular.svg" alt="">
                            </span>
                        </div>
                        <label class="fs-6 fs-md-2" for="confirme_mdp">Confirmez un mot de passe *</label>
  
                        <div class="box">
                            <input class="fs-6 fs-md-2" type="password" placeholder="" name="confirme_mdp" id="confirme_mdp" required />
                            <span class="voir toggle-password" onclick="voirmdpconfirm()">
                                <img class="voirimgconfirm" src="../res/eye-slash-regular.svg" alt="">
                            </span>
                        </div>
                        <br><button type="button" class="fs-6 fs-md-2" onclick="nextStep('step3', 'submit', ['mdp', 'confirme_mdp'])">Valider</button>
                        <button type="button" class="fs-6 fs-md-2" onclick="prevStep('step3', 'step2')">Retour</button>
                    </div>
                </form>
                <!--AFFICHAGE DES ERREURS REPEREES-->
                <?php if (!empty($error)): ?>
                    <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center" style="--bs-border-opacity: .5; z-index:10; width: 500px; border: 1px solid  #e47390;">
                        <p class="mb-2 d-block"><?= htmlspecialchars($error) ?></p>
                        <div class="text-center mt-3">
                            <button class="fs-6 fs-md-2" onclick="this.closest('.container-sm-6').style.display='none'" style="height: 7vh; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;">Fermer</button>
                        </div>
                    </div>
                <?php endif; ?>

                <p class="fs-6 fs-md-2">
					Déjà inscrit(e)?
					<a href="connexion.html">Connectez-vous</a>
					</p>
                <div class="progress">
    <div class="progress-bar" id="progressBar" role="progressbar"
        style="width: 25%; background-color:#E47390;"></div>
</div>
        </section>
    </main>
    <script src="../JS/inscription.js"></script>
</body>
</html>