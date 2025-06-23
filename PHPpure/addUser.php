<?php
require_once '../PHPpure/connexion.php';
// Inclure PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['ajouterUtilisateur'])) {
    // si les champs sont vides mettre un message d'erreur
    if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['motDePasse'])) {
        echo "Veuillez remplir tous les champs";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
        exit();
    }

    $pseudo = $_POST['prenom'] . '.' . $_POST['nom'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $motDePasse = $_POST['motDePasse'];
    $role = $_POST['role'];
    $date_inscription = date('Y-m-d'); // Ajouté
    $valable = 1; // Si tu veux que l'utilisateur soit activé directement


    // si l'utilisateur existe déjà
    $sql = "SELECT * FROM user_ WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'email' => $email
    ]);
    $user = $stmt->fetch();

    $sql2 = "SELECT * FROM user_ WHERE pseudo = :pseudo";
    $stmt2 = $pdo->prepare($sql2);
    $stmt2->execute([
        'pseudo' => $pseudo
    ]);
    $user2 = $stmt2->fetch();

    if ($user || $user2) {
        echo "L'utilisateur existe déjà";
        echo "<script>setTimeout(function() { window.location.href = '../PHP/index.php'; }, 3000);</script>";
    } else {
        // Insertion dans user_
        $sql = "INSERT INTO user_ (pseudo, nom, prenom, email, mot_de_passe, date_inscription, valable)
            VALUES (:pseudo, :nom, :prenom, :email, :motDePasse, :date_inscription, :valable)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'pseudo' => $pseudo,
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'motDePasse' => $motDePasse,
            'date_inscription' => $date_inscription,
            'valable' => $valable
        ]);

        // Récupère l'ID nouvellement inséré
        $lastInsertId = $pdo->lastInsertId();

        // Insertion dans la table de rôle
        $sql3 = "INSERT INTO $role (id) VALUES (:id)";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute([
            'id' => $lastInsertId
        ]);

        require '../PHPMailer-master/src/PHPMailer.php';
        require '../PHPMailer-master/src/SMTP.php';
        require '../PHPMailer-master/src/Exception.php';
        $stmt = $pdo->prepare("SELECT * FROM `user_` WHERE email = ?");
        $stmt->execute([$email]);
        $user3 = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user3) {
            $email = $user3['email'];
            $Pseudo = $user3['pseudo'];
            $Nom = $user3['nom'];
            $Prenom = $user3['prenom'];

            // Envoi de l'e-mail avec PHPMailer
            $mail = new PHPMailer(true);

            try {
                // Configuration SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'iut.rezoom@gmail.com';
                $mail->Password = 'obmv hoac gbrw ftwz';     // Utilise un mot de passe d’application
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;

                $mail->CharSet = 'UTF-8';
                $mail->setFrom('iut.rezoom@gmail.com', 'ReZoom Support');
                $mail->addAddress($email, "$Nom $Prenom");
                $mail->addReplyTo('iut.rezoom@gmail.com', 'ReZoom Support');

                $mail->Subject = 'Validation de votre compte';
                $mail->Body = "Bonjour $Nom $Prenom,\n\nVotre compte ReZoom, $Pseudo a été validé, vous pouvez maintenant consulter le site et commencer à prévoir vos emprunts et réservations !\n\nCordialement,\nL'équipe ReZoom";

                $mail->send();

                echo <<<HTML
            <div class="container-sm-6 bg-white rounded p-5 position-absolute top-50 start-50 translate-middle text-center align-items-center justify-content-center" style="--bs-border-opacity: .5; z-index:10; width: 500px; border: 1px solid  #e47390;">
              <p class="mb-2 d-block">Le compte a été créé avec succès. </p>
              <div class="text-center mt-3">
              <button onclick="window.location.href='mdp_oublie.php'" class="btn mdp" style="height: 7vh; background-color: #e47390; border-radius: 0.5vw; border: none; font-size: 1.2vw; color: white;">Fermer</button>
              </div>
            </div>
            HTML;
            } catch (Exception $e) {
                echo "Erreur lors de l'envoi du mail : {$mail->ErrorInfo}";
            }
        }
        header('Location: ../PHP/index.php');
    }
}
