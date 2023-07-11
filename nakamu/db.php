<?php

$host = "localhost";
$user = "root";
$pass = "";
$base = "nakamu";

$co = mysqli_connect($host, $user, $pass, $base);
if (mysqli_connect_errno()) {
    echo "Erreur de connexion à la base de données: " . mysqli_connect_error();
    exit;
}

?>