<?php
include("atlas/conexao.php");
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    
}

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

//se o token e o dominio estiverem corretos
if (mysqli_num_rows($result) > 0) {
    //inicia a sessão e redireciona para a página de administração
    echo "Token Valido!";
} else {
    //se não estiverem corretos, exibe uma mensagem de erro
    echo "Token Invalido!";
}
?>