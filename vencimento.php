<?php
include("atlas/conexao.php");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}

$senha = $_GET['senha'];

if ($senha == "123gdsfgbhgdyegryr56y4w5t7Cv3rwrfcrwa3bgs9ume09v58dasdasdadfsdfgm3nut09083r4y289Y45") {
}else{
    echo "Senha Inválida!";
    exit;
}
    
//hora sao paulo
date_default_timezone_set('America/Sao_Paulo');

$token = $_GET['token'];
$dominio = $_GET['dominio'];
if (strpos($token, "' OR") !== false) {
    echo "Token Invalido!";
    exit();
  }
  if (strpos($dominio, "' OR") !== false) {
    echo "Token Invalido!";
    exit();
  }

//consulta o token e o dominio
$sql = "SELECT * FROM tokens WHERE token ='$token' AND dominio ='$dominio'";
$result = mysqli_query($conn, $sql);
$proximovencimento = date('d/m/Y', strtotime('+1 days'));
$datahoje = date('d/m/Y', strtotime('-1 days'));
//se o token e o dominio estiverem corretos
if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $vencimento = $row['vencimento'];
    echo $vencimento;
    exit;
} else {
    // Se não estiverem corretos, exibe uma mensagem de erro
    echo "Token Inválido!";
}

?>