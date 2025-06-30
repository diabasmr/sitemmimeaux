<?php
session_start();
require_once('connexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    if (empty($pseudo) || empty($mdp)) {
        $_SESSION['error'] = "Veuillez remplir tous les champs";
        header('Location: ../PHP/connexion-compte.php');
        exit();
    } else {
        $stmt = $pdo->prepare("SELECT * FROM user_ WHERE pseudo = :pseudo OR email = :pseudo");
        $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if (password_verify($mdp, $user['mot_de_passe'])) {
                if ($user['valable'] == 0) {
                    $_SESSION['error'] = "Votre compte n'est pas encore activé.";
                    header('Location: ../PHP/connexion-compte.php');
                    exit();
                } else {
                    $id = $user['id'];
                    $role = getUserRole($id, $pdo);

                    $numeroEtudiant = 'Non renseigné';
                    if ($role == "Etudiant(e)") {
                        $sql2 = 'SELECT numeroEtudiant FROM etudiant WHERE id = :id';
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute([':id' => $id]);
                        $numeroEtudiantFetched = $stmt2->fetch(PDO::FETCH_ASSOC)['numeroEtudiant'];
                        if (!empty($numeroEtudiantFetched) && $numeroEtudiantFetched != '0') {
                            $numeroEtudiant = $numeroEtudiantFetched;
                        }
                    }

                    $_SESSION['user'] = [
                        'id' => $user['id'],
                        'pseudo' => $user['pseudo'],
                        'nom' => $user['nom'],
                        'prenom' => $user['prenom'],
                        'email' => $user['email'],
                        'telephone' => $user['telephone'],
                        'adresse' => $user['adresse'],
                        'numeroEtudiant' => $numeroEtudiant,
                        'role' => $role,
                        'profil' => $user['avatar'],
                        'session_token' => bin2hex(random_bytes(32))
                    ];

                    $_SESSION['user']['rememberMe'] = isset($_POST['rememberMe']) && $_POST['rememberMe'] === 'on';

                    header('Location: ../PHP/index.php');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Mot de passe incorrect.";
                header('Location: ../PHP/connexion-compte.php');
                exit();
            }
        } else {
            $_SESSION['error'] = "Aucun utilisateur trouvé avec ce pseudo.";
            header('Location: ../PHP/connexion-compte.php');
            exit();
        }
    }
}
