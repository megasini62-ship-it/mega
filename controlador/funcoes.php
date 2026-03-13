<?php 
error_reporting(0);
session_start();
//time zone
date_default_timezone_set('America/Sao_Paulo');
include './controlador/conexao.php';
require_once './vendor/pix/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include './lib/vendor/autoload.php';
require './lib/vendor/phpmailer/phpmailer/src/Exception.php';
require './lib/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require './lib/vendor/phpmailer/phpmailer/src/SMTP.php';
$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);
if (!$conn) {
    die('Não foi possível conectar: ' . mysqli_error($conn));
}

function login($email, $senha) {
    global $conn;
    $senha = md5($senha);
    $sql = "SELECT id, name, email, admin, perm FROM contas WHERE email = ? AND senha = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $email, $senha);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result_id, $result_nome, $result_email, $result_admin, $result_perm);
    mysqli_stmt_fetch($stmt);
    if ($result_nome !== null) {
        mysqli_stmt_close($stmt);
        $data = array(
            'id' => $result_id,
            'nome' => $result_nome,
            'email' => $result_email,
            'admin' => $result_admin,
            'perm' => $result_perm
        );
        return $data;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function consultarUsuario($id){
    global $conn;
    $sql = "SELECT * FROM contas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row !== null) {
        mysqli_stmt_close($stmt);
        return $row;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }

}

function editarUsuario($id, $nome, $email, $perm, $admin, $senha) {
    global $conn;
    if (!empty($senha)) {
        $senha = md5($senha);
        $sql = "UPDATE contas SET name = ?, email = ?, senha = ?, perm = ?, admin = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssi", $nome, $email, $senha, $perm, $admin, $id);
        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            return true;
        } else {
            return false;
        }
    }else{
    $sql = "UPDATE contas SET name = ?, email = ?, perm = ?, admin = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssi", $nome, $email, $perm, $admin, $id);
    $success = mysqli_stmt_execute($stmt);
    if ($success) {
        return true;
    } else {
        return false;
    }
}
}

function enviaremail($email, $codigo, $nome) {
    
    $mail = new PHPMailer(true);
    try {        
        $mail->isSMTP();                                      
        $mail->Host       = 'smtp.gmail.com';                
        $mail->SMTPAuth   = true;                                 
        $mail->Username   = 'atlasdesenvolvimentovpn@gmail.com';           
        $mail->Password   = '';                          
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;  
        $mail->Port       = 465;                               
        $mail->setFrom('atlasdesenvolvimentovpn@gmail.com', 'Suporte Atlas Painel');
        $mail->addAddress($email, $_SESSION['nome']);
        $mail->CharSet = 'UTF-8';
        $mail->isHTML(true);  
        $mail->Subject = 'Codigo de Verificação 2 Factores';
    $mail->Body = '<!DOCTYPE html>
    <html>
    <head>
    <style>
        body {
        font-family: Arial, sans-serif;
        background-color: #333;
        color: #fff;
        margin: 0;
        padding: 0;
        }

        .container {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #333;
        border: 1px solid #555;
        border-radius: 5px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
        text-align: center;
        }

        h1 {
        color: #ff69b4;
        }

        p {
        color: #fff;
        margin-bottom: 10px;
        }

        .verification-code {
        display: inline-block;
        padding: 10px 20px;
        background-color: #ff69b4;
        color: #fff;
        font-weight: bold;
        border-radius: 4px;
        text-decoration: none;
        margin-top: 20px;
        }

        .verification-text {
        font-size: 18px;
        color: #fff;
        }

        .footer {
        margin-top: 20px;
        font-size: 12px;
        color: #999;
        }
    </style>
    </head>
    <body>
    <div class="container">
        <h1>Codigo de Confirmação Atlas</h1>
        <p>Ol '.$nome.', Seu código para resgatar o token</p>
        <div class="verification-text">
        <p>Seu código de verificaão é:</p>
        <p class="verification-code">'.$codigo.'</p>
        </div>
        <p>Insira esse código no formulário para resgatar o token.</p>
        <a href="https://atlaspainel.shop/atlas/home.php" class="verification-code">Acessa o Gerenciador</a>
        <p class="footer">Esta é uma mensagem automática, por favor, não responda.</p>
    </div>
    </body>
    </html>';
        $mail->AltBody = 'Olá, '.$nome.'\n\nSeu código de verificação é: '.$codigo.'\n\nAtenciosamente,\nEquipe Atlas Painel';
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}

function consultaPerm($id) {
    global $conn;
    $sql = "SELECT admin, perm FROM contas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    $rows = mysqli_stmt_num_rows($stmt);

    if ($rows > 0) {
        mysqli_stmt_bind_result($stmt, $result_admin, $result_perm);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        $data = array(
            'admin' => $result_admin,
            'perm' => $result_perm
        );
        return $data;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}


function gerarpagamento($valor, $dominio, $token, $tipo, $renovar, $descricao) {
    global $conn;
    $access_token = 'MP';  
    $dt = new DateTime();
    $interval = date_interval_create_from_date_string('10 minutes');
    $dt->add($interval);
    $formatted_date = $dt->format('Y-m-d\TH:i:s.000O');
    MercadoPago\SDK::setAccessToken($access_token);
    $payment = new MercadoPago\Payment();
    $payment->transaction_amount = $valor;
    $payment->description = "Compra token painel";
    $payment->payment_method_id = "pix";
    $payment->date_of_expiration = $formatted_date;
    $payment->payer = array(
        "email" => $_SESSION['email'],
        "first_name" => "Venda",
        "last_name" => "Painel",
        "identification" => array(
            "type" => "CPF",
            "number" => "19119119100"
         ),
        "address"=>  array(
            "zip_code" => "06233200",
            "street_name" => "Av. das Nações Unidas",
            "street_number" => "3003",
            "neighborhood" => "Bonfim",
            "city" => "Osasco",
            "federal_unit" => "SP"
         )
      );
    $result = $payment->save();
    $data = array(
        'expiracao' => $payment->date_of_expiration,
        'payment_id' => $payment->id,
        'qr_code_base64' => $payment->point_of_interaction->transaction_data->qr_code_base64,
        'qr_code' => $payment->point_of_interaction->transaction_data->qr_code
    );
    if ($result) {
        $status = 'Pendente';

        $stmt = $conn->prepare("INSERT INTO pagamentos (valor, status, descricao, data_pagamento, email_comprador, qr_code_copia, qr_code_base64, tipo, token, dominio, renovar, idpayment) VALUES (?, ?, ?, NOW(), ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssss", $valor, $status, $descricao, $_SESSION['email'], $data['qr_code'], $data['qr_code_base64'], $tipo, $token, $dominio, $renovar, $data['payment_id']);
        $result = $stmt->execute();
        return $data;
    } else {
        return false;
    }
}

function checkTodosPagamentos(){
    global $conn;
    $sql = "SELECT * FROM pagamentos WHERE status = 'Pendente'";
    $result = mysqli_query($conn, $sql);
    $pagamentos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $pagamento_id = $row['idpayment'];
        $status = verificarStatusPagamento($pagamento_id); // Chama a função para verificar o status
        $pagamentos[] = array('id' => $row['id'], 'status' => $status, 'token' => $row['token'], 'dominio' => $row['dominio'], 'renovar' => $row['renovar'], 'tipo' => $row['tipo'], 'email' => $row['email_comprador']);
    }
    return $pagamentos;
}


function verificarStatusPagamento($payment_id){
    $access_token = 'MP';
    MercadoPago\SDK::setAccessToken($access_token);
    try {
        $payment = MercadoPago\Payment::find_by_id($payment_id);
        $status = $payment->status;
        return $status;
    } catch (Exception $e) {
        return 'Erro ao buscar status';
    }
}



//deleta todos pagamentos Pendentes que estao a mais de 11 minutos
function deletarPagamentosPendentes() {
    global $conn;
    $sql = "DELETE FROM pagamentos WHERE status = 'Pendente' AND data_pagamento < DATE_SUB(NOW(), INTERVAL 1 HOUR)";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        return true;
    } else {
        return false;
    }
}


// Função para listar pagamentos do email
function consultapagamentos($email){
    global $conn;
    $sql = "SELECT * FROM pagamentos WHERE email_comprador = ? AND (status = 'Aprovado' OR data_pagamento > DATE_SUB(NOW(), INTERVAL 10 MINUTE))";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $pagamentos = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $pagamentos[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $pagamentos;
}

function listarUsuarios() {
    global $conn;
    $sql = "SELECT * FROM contas ORDER BY perm DESC, admin DESC";
    $result = mysqli_query($conn, $sql);
    $usuarios = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $usuarios[] = $row;
    }
    return $usuarios;
}



//consulta todos pagamentos aprovados nos ultimos 30 dias do email e soma os valores
function consultarPagamentosAprovados($email) {
    global $conn;
    $sql = "SELECT SUM(valor) AS total FROM pagamentos WHERE status = 'Aprovado' AND email_comprador = ? AND data_pagamento > DATE_SUB(NOW(), INTERVAL 30 DAY)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result_total);
    mysqli_stmt_fetch($stmt);
    if ($result_total !== null) {
        mysqli_stmt_close($stmt);
        return $result_total;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function cadastrar($nome, $email, $senha) {
    global $conn;
    $sqlVerificaEmail = "SELECT COUNT(*) AS total FROM contas WHERE email = ?";
    $stmtVerificaEmail = mysqli_prepare($conn, $sqlVerificaEmail);
    mysqli_stmt_bind_param($stmtVerificaEmail, "s", $email);
    mysqli_stmt_execute($stmtVerificaEmail);
    $resultVerificaEmail = mysqli_stmt_get_result($stmtVerificaEmail);
    $row = mysqli_fetch_assoc($resultVerificaEmail);
    if ($row['total'] > 0) {
        return 'email_existente';
    }
    $sql = "INSERT INTO contas (name, email, senha, admin, perm) VALUES (?, ?, ?, '0', '0')";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sss", $nome, $email, $senha);
    $success = mysqli_stmt_execute($stmt);
    if ($success) {
        return 'sucesso';
    } else {
        return 'erro_cadastrar';
    }
}
//pesquisa todos tokens
function pesquisarTokens() {
    global $conn;
    $sql = "SELECT * FROM tokens";
    $result = mysqli_query($conn, $sql);
    $tokens = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $tokens[] = $row;
    }
    return $tokens;
}

//pesquisa token por id
function pesquisarTokenPorId($id) {
    global $conn;
    $sql = "SELECT * FROM tokens WHERE dono = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $tokens = array();
    while ($row = mysqli_fetch_assoc($result)) {
        $tokens[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $tokens;

}

function pesquisarToken($pesquisa) {
    global $conn;
    $params = array();

    if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') {
        $sql = "SELECT * FROM tokens WHERE dominio LIKE ? ";
        $params[] = "%$pesquisa%";
    } else {
        $sql = "SELECT * FROM tokens WHERE (dominio LIKE ? OR token LIKE ?) AND dono = ?";
        $params[] = "%$pesquisa%";
        $params[] = "%$pesquisa%";
        $params[] = $_SESSION['id'];
    }

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $types = str_repeat('s', count($params)); 
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $tokens = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $tokens[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $tokens;
    } else {
        return false;
    }
}

//pesquisar usuario


function pesquisarUsuario($pesquisa) {
    global $conn;
    $params = array();

    if ($_SESSION['perm'] == 'SIM') {
        $sql = "SELECT * FROM contas WHERE name LIKE ? OR email LIKE ?";
        $params[] = "%$pesquisa%";
        $params[] = "%$pesquisa%";
    } else {
        return false;
    }

    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        $types = str_repeat('s', count($params)); 
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $usuarios = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $usuarios[] = $row;
        }
        mysqli_stmt_close($stmt);
        return $usuarios;
    } else {
        return false;
    }
}



function gerarToken($tamanho) {
    $caracteres = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $token = '';
    
    for ($i = 0; $i < $tamanho; $i++) {
        $posicao = rand(0, strlen($caracteres) - 1);
        $token .= $caracteres[$posicao];
    }
    
    return $token;
}


//consulta token do id 
function consultaToken($id) {
    global $conn;
    $sql = "SELECT token FROM tokens WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result_token);
    mysqli_stmt_fetch($stmt);
    if ($result_token !== null) {
        mysqli_stmt_close($stmt);
        return $result_token;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

//consulta token pelo token
function consultaTokenToken($token) {
    global $conn;
    $sql = "SELECT * FROM tokens WHERE token = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row !== null) {
        mysqli_stmt_close($stmt);
        return $row;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

//funcao resgatar token pesquisa o token que o dono e zero
function resgatarToken($token, $dominio) {
    global $conn;
    $sql = "SELECT * FROM tokens WHERE token = ? AND dominio = ? AND dono = '0'";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $token, $dominio);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    if ($row !== null) {
        mysqli_stmt_close($stmt);
        $sql = "UPDATE tokens SET dono = ? WHERE token = ? AND dominio = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "iss", $_SESSION['id'], $token, $dominio);
        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            return true;
        } else {
            return false;
        }
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}


function consultaDominio($id) {
    global $conn;

    if ($_SESSION['perm'] == 'SIM') {
        $sql = "SELECT id, dominio, token FROM tokens WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result_id, $result_dominio, $result_token);
        mysqli_stmt_fetch($stmt);
        if ($result_id !== null) {
            mysqli_stmt_close($stmt);
            $data = array(
                'id' => $result_id,
                'dominio' => $result_dominio,
                'token' => $result_token
            );
            return $data;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    } else {
        $sql = "SELECT id, dominio, token FROM tokens WHERE id = ? AND dono = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ii", $id, $_SESSION['id']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result_id, $result_dominio, $result_token);
        mysqli_stmt_fetch($stmt);
        if ($result_id !== null) {
            mysqli_stmt_close($stmt);
            $data = array(
                'id' => $result_id,
                'dominio' => $result_dominio,
                'token' => $result_token
            );
            return $data;
        } else {
            mysqli_stmt_close($stmt);
            return false;
        }
    }
}

//funcao consulta dominio para renovar 
function dominiorenova($id){
    global $conn;
    if ($_SESSION['perm'] == 'SIM' || $_SESSION['admin'] == 'SIM'){
    $sql = "SELECT dominio, vencimento FROM tokens WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result_dominio, $result_vencimento);
    mysqli_stmt_fetch($stmt);
    if ($result_dominio !== null) {
        mysqli_stmt_close($stmt);
        $data = array(
            'dominio' => $result_dominio,
            'vencimento' => $result_vencimento
        );
        return $data;
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
    } else {
        return false;
    }
}


function editarDominio($dominio, $token) {
    global $conn;
    if ($_SESSION['perm'] == 'SIM') {
        $sql = "UPDATE tokens SET dominio = ? WHERE token = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ss", $dominio, $token);
        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            return true;
        } else {
            return false;
        }
    } else {
        $sql = "UPDATE tokens SET dominio = ? WHERE token = ? AND dono = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "ssi", $dominio, $token, $_SESSION['id']);
        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            return true;
        } else {
            return false;
        }
    }
}

//funcao troca senha 
function trocarsenha($id, $senhaantiga, $senhanova){
    global $conn;
    $senhaantiga = md5($senhaantiga);
    $senhanova = md5($senhanova);
    $sql = "SELECT senha FROM contas WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result_senha);
    mysqli_stmt_fetch($stmt);
    if ($result_senha !== null) {
        mysqli_stmt_close($stmt);
        if ($senhaantiga == $result_senha){
            $sql = "UPDATE contas SET senha = ? WHERE id = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "si", $senhanova, $id);
            $success = mysqli_stmt_execute($stmt);
            if ($success) {
                return true;
            } else {
                return false;
            }
        }else{
            return false;
        }
    } else {
        mysqli_stmt_close($stmt);
        return false;
    }
}

function gerarTokenadmin($dominio, $token, $tipo, $vencimentoFormatado) {
    global $conn;
    if ($_SESSION['perm'] == 'SIM') {
        $sql = "INSERT INTO tokens (dominio, token, contato, vencimento, dono) VALUES (?, ?, '0', ?, '0')";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sss", $dominio, $token, $vencimentoFormatado);
        $success = mysqli_stmt_execute($stmt);
        if ($success) {
            return true;
        } else {
            return false;
        }
}
}

function sendlogdiscord($tipo, $mensagem) {
    // Defina as URLs do webhook com base no tipo
    if ($tipo == 'tokengerado') {
        $url = 'https://discordapp.com/api/webhooks//GzlYh6WDxQwc65oyQfXLEiGZ3CNlq6OxD5bUo59qRnbm9CuIMsqbBjimSf2G02s3iI9T';
    } elseif ($tipo == 'tokenresgatado') {
        $url = 'https://discordapp.com/api/webhooks//-ZD3G2-VtX-umpAL4CHqE5adx8q-XFdNcjV79_wW4CDe6-qwkfhI9ZJSDuFZLsoUbzJA';
    } elseif ($tipo == 'tokeneditado') {
        $url = 'https://discordapp.com/api/webhooks//ZZrDrdNYIFVROgngG4S14sRYwTuMrK2L9fqd-FIJM0roJKYbu5haDWDibpKPD-Hi2JdV';
    } elseif ($tipo == 'tokengeradoadmin') {
        $url = 'https://discordapp.com/api/webhooks//d9necUi4YI_ioo2Fm2zoMgtLoa8MgXte5NbheK-aeDMFWTndPMtLitAbfMAD0uOwP0vQ';
    } elseif ($tipo = 'contaeditada'){
        $url = 'https://discordapp.com/api/webhooks//ae7ZRkBQZXAv0l3tWjk7rPKHOrDU1e9f4t2dE7E0GsqROSMH5gQZoXLeJcwYYHhw9Yqi';
    }

    $headers = ['Content-Type: application/json; charset=utf-8'];

    // Mensagem formatada em Markdown
    $formattedMessage = "```$mensagem```";

    // Dados para enviar no corpo da requisição HTTP
    $data = ['content' => $formattedMessage];

    // Configuração da requisição HTTP
    $options = [
        'http' => [
            'header'  => implode("\r\n", $headers),
            'method'  => 'POST',
            'content' => json_encode($data),
        ],
    ];

    // Contexto para a requisição HTTP
    $context  = stream_context_create($options);

    // Envie a requisição HTTP para o webhook
    $result = file_get_contents($url, false, $context);

    // Verifique se a requisição foi bem-sucedida
    if ($result === false) {
        return false; // Se houver um erro no envio
    } else {
        return true; // Se a mensagem for enviada com sucesso
    }
}
