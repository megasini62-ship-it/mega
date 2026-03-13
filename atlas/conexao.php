<?php
$dbuser = "";
$dbpass = "";
$dbname = "";
$dbhost = "localhost";
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die('Não foi possível conectar: ' . mysqli_error($conn));
}
?>