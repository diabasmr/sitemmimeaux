<?php
session_start();
session_destroy();
header("Location: ../PHP/connexion-compte.php");
exit();
