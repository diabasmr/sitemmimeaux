<?php
session_start();
session_destroy();
header("Location: ../PHP/connexion.html");
exit();
