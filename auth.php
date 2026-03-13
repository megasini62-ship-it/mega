<?php
include("atlas/conexao.php"); 
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$conn) {
    $response = array("message" => "Erro na conexão com o banco de dados", "status" => 'error');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

if (!isset($_POST['token']) || !isset($_POST['dominio'])) {
    $response = array("message" => "Parâmetros 'token' e/ou 'dominio' ausentes!", "status" => 'error');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

$token = $_POST['token'];
$dominio = $_POST['dominio'];

// SQL para verificar token e data de vencimento
$sql = "SELECT vencimento FROM tokens WHERE token = ? AND dominio = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    $response = array("message" => "Erro na preparação da consulta: " . mysqli_error($conn), "status" => 'error');
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

mysqli_stmt_bind_param($stmt, "ss", $token, $dominio);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);

    // Converter a data de vencimento do banco para o formato DateTime
  if($row['vencimento'] == "Nunca"){
    $response = array(
        "status" => 'success',
        "message" => "Token Válido!",
        "vencimento" => $row['vencimento'] // Opcional: você pode não querer enviar a data de volta
        );
  }else{
    $vencimentoDateTime = DateTime::createFromFormat('d/m/Y', $row['vencimento']);
    $vencimentoDateTime->add(new DateInterval('P4D'));
    $hoje = new DateTime(); // Data de hoje

    if ($vencimentoDateTime < $hoje) {
        $response = array("message" => "Token Expirado!", "status" => 'error');
    } else {
        $response = array(
            "status" => 'success',
            "message" => "Token Válido!",
            "vencimento" => $row['vencimento'] // Opcional: você pode não querer enviar a data de volta
        );
    }
    }
} else {
    $response = array("message" => "Token Inválido!", "status" => 'error');
}

header('Content-Type: application/json');
echo json_encode($response);

mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
