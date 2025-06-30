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
      $mail->Username = 'iut.rezoom@gmail.com'; // Remplace par ton e-mail Gmail
      $mail->Password = 'veta utze kwrk elbf';     // Utilise un mot de passe d’application
      $mail->SMTPSecure = 'tls';
      $mail->Port = 587;

      $mail->CharSet = 'UTF-8';
      $mail->setFrom('iut.rezoom@gmail.com', 'ReZoom Support');
      $mail->addAddress($email, "$Nom $Prenom");
      $mail->addReplyTo('iut.rezoom@gmail.com', 'ReZoom Support');

      $mail->Subject = 'Réinitialisation de votre mot de passe';
      $mail->Body = "Bonjour $Nom $Prenom,\n\nVoici votre mot de passe temporaire pour le compte $Pseudo : $mdp_temporaire\n\nMerci de le changer dès votre connexion.\n\nCordialement,\nL'équipe ReZoom";

      $mail->send();

      echo <<<HTML
          <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
              <h5 class="mb-3 fw-semibold text-dark">Confirmation</h5>
              <p class="text-muted">La réinitialisation de votre mot de passe a été réalisée avec succès. </p>
              <p class="text-muted mb-4">Consultez vos emails pour le récupérer. </p>
              <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
            </div>
          </div>
        HTML;
    } catch (Exception $e) {
      echo <<<HTML
          <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
              <h5 class="mb-3 fw-semibold text-dark">Erreur</h5>
              <p class="text-muted mb-4">"Erreur lors de l'envoi du mail : {$mail->ErrorInfo}"</p>
              <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
            </div>
          </div>
        HTML;
    }
  } else {
    echo <<<HTML
          <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
              <h5 class="mb-3 fw-semibold text-dark">Pas si vite</h5>
              <p class="text-muted mb-4">Veuillez entrer un adresse email valide et existante chez Re Zoom</p>
              <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
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
    crossorigin="anonymous" />

  <link rel="stylesheet" href="../CSS/style.css" />
  <link rel="stylesheet" href="../CSS/connexion.css" />
  <link rel="stylesheet" href="../CSS/header.css" />
  <title>Connexion</title>
  <link rel="icon" type="image/png" href="../IMG/logo.png">
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
            id="email" />
          <button class="fs-6 fs-md-2" type="submit">Envoyer</button>
        </form>
        <p class="fs-6 fs-md-2">
          Pas encore de compte ?
          <a href="inscription.php">Inscrivez-vous</a>
        </p>
        <p class="fs-6 fs-md-2">
          <a href="connexion-compte.php">Se connecter</a>
        </p>
      </div>
      <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-2 mt-3">
        <a class="fs-6 liens" href="support_conditions.php">Support et Conditions</a>
      </div>
    </section>
  </main>
  <script src="../JS/connexion.js"></script>
</body>

</html>