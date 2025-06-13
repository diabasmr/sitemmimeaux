<?php

require('../PHPpure/connexion.php');

// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../PHPMailer-master/src/PHPMailer.php';
require '../PHPMailer-master/src/SMTP.php';
require '../PHPMailer-master/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $Email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM `user_` WHERE email = ?");
    $stmt->execute([$Email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $email = $user['email'];
        $Pseudo = $user['pseudo'];
        $Nom = $user['nom'];
        $Prenom = $user['prenom'];
        $mdp_temporaire = bin2hex(random_bytes(4));
        $mdp_hash = password_hash($mdp_temporaire, PASSWORD_DEFAULT);

        $update = $pdo->prepare("UPDATE user_ SET mot_de_passe = ? WHERE email = ?");
        $update->execute([$mdp_hash, $email]);

        // Envoi de l'e-mail avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Configuration SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'materiel.iut@gmail.com'; // Remplace par ton e-mail Gmail
            $mail->Password = 'obmv hoac gbrw ftwz';     // Utilise un mot de passe d’application
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->CharSet = 'UTF-8';
            $mail->setFrom('materiel.iut@gmail.com', 'IUT Support');
            $mail->addAddress($email, "$Nom $Prenom");
            $mail->addReplyTo('materiel.iut@gmail.com', 'IUT Support');

            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "Bonjour $Nom $Prenom,\n\nVoici votre mot de passe temporaire pour le compte $Pseudo : $mdp_temporaire\n\nMerci de le changer dès votre connexion.\n\nCordialement,\nL'équipe IUT Meaux";

            $mail->send();

            echo <<<HTML
            <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center" style="--bs-border-opacity: .5; z-index:10; width: 500px; border: 1px solid  #e47390;">
              <p class="mb-2 d-block">La réinitialisation de votre mot de passe a été réalisée avec succès. </p><p>Consultez vos emails pour le récupérer.</p>
              <div class="text-center mt-3">
              <button onclick="window.location.href='mdp_oublie.php'" class="btn mdp" style="height: 7vh; background-color: #e47390; border-radius: 0.5vw; border: none; font-size: 1.2vw; color: white;">Fermer</button>
              </div>
            </div>
            HTML;

        } catch (Exception $e) {
            echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
        }

    } else {
        echo <<<HTML
        <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center" style="--bs-border-opacity: .5; z-index:10; width: 500px; border: 1px solid  #e47390;">
          <b class="mb-2 d-block">AUCUN COMPTE N'A ÉTÉ TROUVÉ SOUS CETTE ADRESSE EMAIL</b>
          <div class="text-center mt-3">
          <button onclick="window.location.href='mdp_oublie.php'" style="height: 7vh; background-color: #e47390; border-radius: 0.5vw; border: none; font-size: 1.2vw; color: white;">Fermer</button>
          </div>
        </div>
        HTML;
    }
}
?>




<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
      crossorigin="anonymous"
    />

    <link rel="stylesheet" href="../CSS/style.css" />
    <link rel="stylesheet" href="../CSS/connexion.css" />
    <link rel="stylesheet" href="../CSS/header.css" />
    <title>Connexion</title>
  </head>
  <body>
    <main>
      <section>
        <h1 class="fs-4 col-12">Vous avez oublié votre mot de passe ?</h1>
        <p class="col-12">Nous vous enverrons un mot de passe provisoire par email.</p>
        <div class="formContainer">
          <form action="" method="POST">
            <input
              class="fs-6 fs-md-2"
              type="email"
              placeholder="Votre email"
              name="email"
              id="email"
            />
            <button class="fs-6 fs-md-2" type="submit">Envoyer</button>
          </form>
          <p class="fs-6 fs-md-2">
            Pas encore de compte ?
            <a href="inscription.php">Inscrivez-vous</a>
          </p>
          <p class="fs-6 fs-md-2">
            <a href="connexion.html">Se connecter</a>
          </p>
        </div>
      </section>
    </main>
    <script src="../JS/connexion.js"></script>
  </body>
</html>
