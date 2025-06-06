<?php
// en tete de connexion a la base de donnees

$host = "localhost";
$user = "root";
$pass = "";
$dbname = "sae201";

// $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

// $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}



function getUserRole($idUser, $pdo)
{
    // vérifie si l'id present dans table administrateur
    $query = $pdo->prepare('SELECT id FROM administrateur WHERE id = ?');
    $query->execute([$idUser]);
    if ($query->fetch()) {
        return 'Administrateur';
    }

    // vérifie si l'id present dans table enseignant
    $query = $pdo->prepare('SELECT id FROM enseignant WHERE id = ?');
    $query->execute([$idUser]);
    if ($query->fetch()) {
        return 'Enseignant(e)';
    }

    // vérifie si l'id present dans table étudiant
    $query = $pdo->prepare('SELECT id FROM etudiant WHERE id = ?');
    $query->execute([$idUser]);
    if ($query->fetch()) {
        return 'Etudiant(e)';
    }

    // vérifie si l'id present dans table agent
    $query = $pdo->prepare('SELECT id FROM agent WHERE id = ?');
    $query->execute([$idUser]);
    if ($query->fetch()) {
        return 'Agent(e)';
    }

    return 'Non Defini'; // aucun rôle trouvé
}