<?php
session_start();
$error = '';
if (isset($_SESSION['error'])) {
  $error = $_SESSION['error'];
  unset($_SESSION['error']);
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
</head>

<body>
  <main>
    <section>
      <h1 class="fs-4 col-12">Veuillez vous connecter</h1>
      <div class="formContainer">
        <form action="../PHPpure/connexionUser.php" method="POST">
          <input
            class="fs-6 fs-md-2"
            type="text"
            placeholder="Pseudo"
            name="pseudo"
            id="pseudo" />
          <div class="passwordContainer">
            <input
              class="fs-6 fs-md-2"
              type="password"
              placeholder="Mot de passe"
              name="mdp"
              id="mdp" />
            <button
              type="button"
              class="showPassword me-2 me-md-auto"
              id="showPassword">
              <img src="../res/eye-closed.svg" alt="eye" />
            </button>
          </div>

          <div class="remember d-md-flex d-block my-2 my-md-auto text-center">
            <div class="souvenir">
              <input type="checkbox" id="rememberMe" name="rememberMe" />
              <label class="fs-6 fs-md-2 ms-5 ms-md-auto" for="rememberMe">Se souvenir de moi</label>
            </div>
            <a class="fs-6 fs-md-2" href="mdp_oublie.php">Mot de passe oublié ?</a>
          </div>
          <button class="fs-6 fs-md-2" type="submit">Connexion</button>
        </form>
        <!--AFFICHAGE DES ERREURS REPEREES-->
        <?php if (!empty($error)) : ?>
          <div id="confirmationPopup" class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(0, 0, 0, 0.3); backdrop-filter: blur(4px); z-index: 1050;">
            <div class="bg-white rounded-4 shadow p-4 text-center border" style="border-color: #e47390; max-width: 420px; width: 90%;">
              <h5 class="mb-3 fw-semibold text-dark">Pas si vite</h5>
              <p class="text-muted mb-4"><?= htmlspecialchars($error) ?></p>
              <button type="button" class="btn w-50 text-white" style="background-color: #e47390;" onclick="document.getElementById('confirmationPopup').remove()">Fermer</button>
            </div>
          </div>
        <?php endif; ?>

        <p class="fs-6 fs-md-2">
          Pas encore de compte ?
          <a href="inscription.php">Inscrivez-vous</a>
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