<?php
include 'conexao.php';
$timezone = date_default_timezone_set('America/Sao_Paulo');

$sql = "SELECT * FROM tokens";
$result = $conn -> query($sql);
while ($row = $result -> fetch_assoc()){
    
    $token = $row['token'];
    $dono = $row['dono'];
    $dominio = $row['dominio'];
    $datahoje = date('d/m/Y');
    $vencimento = $row['vencimento'];
    $proximovencimento = date('d/m/Y', strtotime('+1 days'));
    

if ($datahoje == $vencimento){

//$sql2 = "DELETE FROM tokens WHERE token = '$token'";
//$conn -> query($sql2);
    $url = "https://discord.com/api/webhooks/1110927436624965644/1FF-uRxcr1iKkmBP4qlBKhIImURjwniELVKapZwuR3OCT0byEZS_sxbsjg4zyksVQxcT";
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $mensagem = '
    
    
    ```Token Expirado Removido com sucesso!!!
    Token - '.$token.'
    Dono - '.$dono.'
    Dominio - '.$dominio.'
    Vencimento - '.$vencimento.'```
    
    
    
    
    ';
    
    $POST = [ 'username' => 'LOG EXPIRADOS ATLAS', 'content' => ''.$mensagem.'' ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}
if ($proximovencimento == $vencimento){
    $url = "https://discord.com/api/webhooks/1110928295555497984/OXvNln09BIK18J_miFaL9pMUsVggrgxzTDnkH_3gLUwrczYefJsL6jQixtqwOnYOB1fR";
    $headers = [ 'Content-Type: application/json; charset=utf-8' ];
    $mensagem = '
    
    
    ```Token Expira amanhã!!!
    Token - '.$token.'
    Dono - '.$dono.'
    Dominio - '.$dominio.'
    Vencimento - '.$vencimento.'```
    
    
    
    
    ';
    
    $POST = [ 'username' => 'LOG PROXIMO VENCER ATLAS', 'content' => ''.$mensagem.'' ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($POST));
    $response   = curl_exec($ch);
}



}
?>