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
        <?php if (!empty($error)): ?>
          <div class="container bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center" style="z-index:10; width: 500px; border: 1px solid #e47390;">
            <p class="mb-2 fs-6 fs-md-2"><?= htmlspecialchars($error) ?></p>
            <div class="text-center mt-3">
              <button onclick="this.closest('.container').style.display='none'" class="p-3" style="height: 7vh; background-color: #e47390; border-radius: 0.5vw; border: none; color: white;">Fermer</button>
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