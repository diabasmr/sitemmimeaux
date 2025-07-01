<?php
include("../PHPpure/entete.php");
require('../PHPpure/connexion.php');

// Vérifie si l'ID du produit est bien passé dans l'URL
if (isset($_GET['id'])) {
  $id = $_GET['id'];

  // Requête pour récupérer les détails du produit
  $sql = "SELECT * FROM materiel WHERE idM = :nom";

  // Prépare la requête
  $stmt = $pdo->prepare($sql);
  if ($stmt) {
    $stmt->execute([':nom' => $id]);

    // Récupère les résultats
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le produit n'existe pas
    if (!$row) {
      echo "Produit introuvable.";
      exit;
    }
  } else {
    echo "Erreur dans la préparation de la requête.";
    exit;
  }
} else {
  echo "Aucun produit sélectionné.";
  exit;
}
?>


<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  <title>Produit</title>
  <link rel="stylesheet" href="../CSS/style.css" />
  <link rel="stylesheet" href="../CSS/index.css" />
  <link rel="stylesheet" href="../CSS/header.css" />
  <link rel="stylesheet" href="../CSS/reservation_salle.css" />
  <link rel="icon" type="image/png" href="../IMG/logo.png">
</head>

<body>
  <?php
  include("header.php");
  include("aside.php");
  // recuperer les favoris
  $sql = "SELECT * FROM favori_materiel WHERE id = ?";
  $result = $pdo->prepare($sql);
  $result->execute([$_SESSION['user']['id']]);
  ?>
  <main>
    <div class="mb-4">
      <div>
        <h2><?= htmlspecialchars($row['designation']) ?></h2>
        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="mt-2 mt-md-0">
          <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="materiels.php">Matériels</a></li>
            <li class="breadcrumb-item active" aria-current="page">Produit</li>
          </ol>
        </nav>
      </div>
    </div>

    <div class="row g-4">
      <div class="col-md-4">
        <img src="../materiel/<?= htmlspecialchars($row['photo']) ?>" alt="Photo matériel" class="img-fluid rounded">
      </div>
      <div class="col-md-8 d-flex flex-column justify-content-between">
        <div>
          <h3 id="nomproduit"><?= htmlspecialchars($row['designation']) ?></h3>
          <p><?= htmlspecialchars($row['descriptif']) ?></p>
        </div>

        <div class="d-flex flex-wrap align-items-center gap-3 mt-4">
          <button
            class="btn btn-danger px-4 py-2"
            onclick="reserverMateriel(<?= $row['idM'] ?>)"
            <?= $row['quantité'] == 0 ? 'disabled' : '' ?>>
            Réserver
          </button>

          <div class="fs-5 fw-semibold text-center">
            <?= $row['quantité'] ?> disponibles
          </div>

          <button class="btn d-flex align-items-center gap-2 p-0 border-0 bg-transparent">
            <?php
            $sql1 = "SELECT * FROM favori_materiel WHERE idM = ? AND id = ?";
            $result1 = $pdo->prepare($sql1);
            $result1->execute([$row['idM'], $_SESSION['user']['id']]);
            if ($result1->fetch()) {
              echo "<img src='../res/heartPlein.svg' alt='favori' style='cursor:pointer;' onclick='retirerFavoris({$row['idM']})'>";
            } else {
              echo "<img src='../res/heartVide.svg' alt='favori' style='cursor:pointer;' onclick='ajouterFavoris({$row['idM']})'>";
            }
            ?>
            <span class="fs-5 fw-bold">Favoris</span>
          </button>
        </div>

        <div class="row mt-5 g-3">
          <div class="col-6 bg-light p-4 rounded text-center d-flex flex-column justify-content-center align-items-center">
            <h5 class="mb-4">Règlement général d'utilisation</h5>
            <button class="btn btn-danger px-4 py-2" onclick="regle.style.display='block';">Le consulter</button>
            <div id="regle" class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-md-65 start-50 translate-middle text-center align-items-center justify-content-center">
              <h5 class="mb-4">Règlement général d'utilisation</h5>
              <p class="mb-3">En réservant du matériel ou une salle dans le cadre du BUT MMI, je reconnais avoir pris connaissance du présent règlement :</p>
              <ul class="text-start mb-4" style="display: inline-block; text-align: left;">
                <li>Je suis responsable du matériel ou de la salle durant toute la durée de la réservation.</li>
                <li>En cas de perte, vol, ou détérioration, je m'engage à en assumer les conséquences, y compris financières.</li>
                <li>Je m'engage à utiliser les équipements de manière respectueuse, en conformité avec leur usage prévu.</li>
                <li>Je comprends que toute utilisation non autorisée pourra entraîner des sanctions pédagogiques ou disciplinaires.</li>
              </ul>
              <div class="text-center">
                <label>
                  <input type="button" name="acceptation" class="fs-6 fs-md-5 btn btn-danger px-4 py-2" value="Fermer" onclick="const container = this.closest('.container-sm-6'); container.style.display='none';">
                </label>
              </div>
            </div>
          </div>
          <div class="col-6">
            <div class="ratio ratio-16x9 rounded overflow-hidden">
              <iframe
                src="https://www.youtube.com/embed/<?= htmlspecialchars($row['lien_demo']) ?>"
                title="Démonstration vidéo"
                frameborder="0"
                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                allowfullscreen></iframe>
            </div>
          </div>

        </div>
      </div>
    </div>

    <section class="my-5">
      <h4 class="mb-4">Commentaires</h4>

      <?php
      $materielcomment = $_GET['id'];

      // Préparation de la requête avec UNION
      $sql = "SELECT u.pseudo, u.avatar, c.date_comment, c.commentaire, c.reaction 
          FROM commentaires c 
          JOIN user_ u ON c.id = u.id 
          WHERE c.idM = :materiel";

      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':materiel', $materielcomment, PDO::PARAM_STR);
      $stmt->execute();

      // Récupération des résultats
      $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
      ?>

      <?php foreach ($users as $user): ?>
        <div class="mb-4">
          <div class="card">
            <div class="card-body d-flex">
              <img <img src="<?= htmlspecialchars($user['avatar']) ?>" alt="avatar user" class="rounded me-3" style="width:10%; height:10%;">
              <div>
                <div class="d-flex justify-content-between gap-2 align-items-center mb-2">
                  <strong><?= htmlspecialchars($user['pseudo']) ?></strong>
                  <small class="text-muted"><?= htmlspecialchars($user['date_comment']) ?></small>
                </div>
                <div class="mb-2"> <?= htmlspecialchars($user['reaction']) ?> ☆</div>
                <p><?= htmlspecialchars($user['commentaire']) ?></p>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach ?>
      <form action="#" method="post" class="d-flex gap-3 align-items-center">
        <textarea
          class="form-control"
          id="exampleFormControlTextarea1"
          rows="1"
          name="commentaire"
          placeholder="Écrire un commentaire"
          style="resize:none; max-width: 70%;"></textarea>
        <input
          type="number"
          name="reaction"
          value="5"
          min="1"
          max="5"
          class="form-control"
          style="max-width: 80px;">
        <span class="fs-3">☆</span>
        <input type="submit" name="submit" class="btn btn-danger" value="ENVOYER">
      </form>
    </section>
  </main>


  <?php
  if (isset($_POST["submit"])) {
    $commentaire = htmlspecialchars($_POST['commentaire']) ?? '';
    $reaction = htmlspecialchars($_POST['reaction']) ?? '';

    if (!empty($commentaire) && !empty($reaction)) {
      $sql = "INSERT INTO commentaires(id, date_comment, commentaire, reaction, idM) 
                    VALUES (:id, DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), :commentaire, :reaction, :idM)";
      $stmt = $pdo->prepare($sql);

      // Vérifier si la préparation a échoué
      if ($stmt === false) {
        die("Erreur de préparation de la requête: " . $pdo->errorInfo());
      }

      // Lier les paramètres à la requête
      $stmt->bindParam(':id', $_SESSION['user']['id'], PDO::PARAM_STR);
      $stmt->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
      $stmt->bindParam(':reaction', $reaction, PDO::PARAM_STR);
      $stmt->bindParam(':idM', $_GET['id'], PDO::PARAM_STR);

      // Exécuter la requête
      $stmt->execute();
    }
  }
  ?>
  <script src="../JS/sideBarre.js"></script>
  <script src="../JS/index.js"></script>
  <script src="../JS/reservation_salle.js"></script>
  <script>
    function reserverMateriel(idM) {
      window.location.href = "reservation_materiel.php?idM=" + idM;
    }

    function ajouterFavoris(idM) {
      window.location.href = "../PHPpure/ajouter_favoris.php?idM=" + idM;
    }

    function retirerFavoris(idM) {
      window.location.href = "../PHPpure/retirer_favoris.php?idM=" + idM;
    }
  </script>
</body>

</html>