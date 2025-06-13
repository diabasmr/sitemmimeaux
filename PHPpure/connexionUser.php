<?php
session_start();
// ajout de l'entete de connexion à la base de données
require_once('connexion.php');

// vérifier si une requête de type POST est reçue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // récupérer les données du formulaire de connexion
    $pseudo = trim($_POST['pseudo']);
    $mdp = trim($_POST['mdp']);

    // vérifier que les champs ne sont pas vides
    if (empty($pseudo) || empty($mdp)) {
        $error = "Veuillez remplir tous les champs";
        exit;
        
    }

    // préparation de la requête pour récupérer l'utilisateur par pseudo
    $stmt = $pdo->prepare("SELECT * FROM user_ WHERE pseudo = :pseudo");
    $stmt->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // comparer le mot de passe (ici comparaison directe, mais à sécuriser avec password_verify si besoin)

        if (password_verify($mdp, $user['mot_de_passe'])) {
            if ($user['valable'] == 0) {
                $error = "Votre compte n'est pas encore activé par un administrateur ou un enseignant veuillez patienter ou contacter un administrateur.";
                exit;
            }

            // recuperer l'id
            $id = $user['id'];

            // recuperer le rôle
            $role = getUserRole($id, $pdo);

            if ($role == "Etudiant(e)") {
                $sql2 = 'SELECT numeroEtudiant FROM etudiant WHERE id = :id';
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute([
                    ':id' => $id
                ]);
                $numeroEtudiant = $stmt2->fetch(PDO::FETCH_ASSOC)['numeroEtudiant'];
                if ($numeroEtudiant == '' || $numeroEtudiant == null || $numeroEtudiant == 0 || $numeroEtudiant == '0' || $numeroEtudiant == 'Non renseigné') {
                    $numeroEtudiant = 'Non renseigné';
                }
            }

            // stocker les infos dans la session
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

            if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on') {
                $_SESSION['user']['rememberMe'] = true;
            } else {
                $_SESSION['user']['rememberMe'] = false;
            }

            // redirection
            header('Location: ../PHP/index.php');
            exit();
        } else {
            $error = "Mot de passe incorrect.";
                exit;
            
        }
    } else {
        $error = "Aucun utilisateur trouvé avec ce pseudo.";
        exit;
    }
}
