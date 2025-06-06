<?php
session_start();
require_once("connexion.php");

$idM = $_GET['idM'];
$idU = $_SESSION['user']['id'];

$sql = "INSERT INTO favori_materiel (id, idM ) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$idU, $idM]);

header("Location: ../PHP/materiels.php");
