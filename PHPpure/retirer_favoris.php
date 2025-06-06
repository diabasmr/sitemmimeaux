<?php
session_start();
require_once("connexion.php");

$idM = $_GET['idM'];
$idU = $_SESSION['user']['id'];
$sql = "DELETE FROM favori_materiel WHERE idM = ? AND id = ?";
$result = $pdo->prepare($sql);
$result->execute([$idM, $idU]);

header("Location: ../PHP/materiels.php");
