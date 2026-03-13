<?php
include './controlador/funcoes.php';
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    echo "<script>window.location.href = 'login';</script>";
}
if (isset($_POST['pesquisa'])) {
    $termoPesquisa = $_POST['pesquisa'];
    
    $resultados = pesquisarToken($termoPesquisa);
    if ($_SESSION['perm'] == 'SIM'){
        echo json_encode($resultados);
    }elseif ($_SESSION['admin'] == 'SIM') {
        // Esconde os tokens de termopequisa
        foreach ($resultados as &$resultado) {
            $resultado['token'] = str_repeat('*', strlen($resultado['token'])); // Substitui o token por asteriscos
        }
        
        echo json_encode($resultados);
    }else {
        echo json_encode($resultados);
    }

}
if (isset($_POST['gerartoken'])) {
    $vencimento = new DateTime();
    if ($_POST['tipo'] == 'mensal') {
        $valor = 30;
        $tipo = 'mensal';
        $vencimento->add(new DateInterval('P30D'));
    } elseif ($_POST['tipo'] == 'trimestral') {
        $valor = 70;
        $tipo = 'trimestral';
        $vencimento->add(new DateInterval('P3M'));
    } elseif ($_POST['tipo'] == 'anual') {
        $valor = 150;
        $tipo = 'anual';
        $vencimento->add(new DateInterval('P1Y'));
    }
    if ($_SESSION['admin'] != 'SIM') {
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Você não tem permissão para gerar tokens!'));
        exit;
    }

    $descricao = 'Compra de token';
    $pagamento = gerarpagamento($valor, $_POST['dominio'], $_POST['token'], $tipo, 0, $descricao);

    if ($pagamento !== false) {
        $vencimentoFormatado = $vencimento->format('d/m/Y');

        echo json_encode(array('status' => 'sucesso', 'pagamento' => $pagamento, 'tipo' => $tipo, 'dominio' => $_POST['dominio'], 'token' => $_POST['token'], 'vencimento' => $vencimentoFormatado));
    } else {
        echo json_encode(array('status' => 'erro'));
    }
}

if (isset($_POST['checkar'])) {
    $pagamento = checkarPagamento($_POST['id']);
    if ($pagamento !== false) {
        echo json_encode(array('status' => 'sucesso', 'pagamento' => $pagamento));
    } else {
        echo json_encode(array('status' => 'erro'));
    }
}

if (isset($_POST['enviaremail'])) {
    $email = $_SESSION['email'];
    $codigo = $_POST['codigo'];
    $_SESSION['codigo'] = $codigo;
    $nome = $_SESSION['nome'];
    $email = enviaremail($email, $codigo, $nome);
    if ($email !== false) {
        echo json_encode(array('status' => 'sucesso', 'email' => $email));
    } else {
        echo json_encode(array('status' => 'sucesso'));
    }
}
    
if (isset($_POST['dominioresgate'])) {
        $token = $_POST['tokenresgate'];
        $dominio = $_POST['dominioresgate'];
        $result = resgatarToken($token, $dominio);
        if ($result !== false) {
            sendlogdiscord('tokenresgatado',
'Token resgatado com sucesso!
Token: ' . $token . '
Dominio: ' . $dominio . '
Resgatado por: ' . $_SESSION['nome'] . '
Email: ' . $_SESSION['email']
            );

            echo json_encode(array('status' => 'sucesso', 'mensagem' => 'Token resgatado com sucesso!'));
            unset($_SESSION['codigo']);
        } else {
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Token não encontrado!'));
            unset($_SESSION['codigo']);
        }
    
}

if (isset($_POST['editardominio'])) {
    $id = $_POST['id'];
    $dominio = consultaDominio($id);
    if ($dominio !== false) {
        echo json_encode(array('status' => 'sucesso', 'dominio' => $dominio, 'id' => $id));
    } else {
        echo json_encode(array('status' => 'erro'));
    }
}

if (isset($_POST['editandodominio'])) {
    $token = $_POST['token'];
    $dominio = $_POST['dominio'];
    $result = editarDominio($dominio, $token);
    if ($result !== false) {
        sendlogdiscord(
            'tokeneditado',
'Dominio editado com sucesso!
Dominio: ' . $dominio . '
Token: ' . $token . '
Editado por: ' . $_SESSION['nome'] . '
Email: ' . $_SESSION['email']
        );
        echo json_encode(array('status' => 'sucesso', 'mensagem' => 'Dominio editado com sucesso!'));
    } else {
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Erro ao editar dominio!'));
    }
}

if (isset($_POST['renovardominio'])) {
    $result = dominiorenova($_POST['id']);
    //se o vencimento for nunca, não tem como renovar
    if ($result !== false && $result['vencimento'] !== 'Nunca') {
        echo json_encode(array('status' => 'sucesso', 'dominio' => $result, 'id' => $_POST['id']));
    } else {
        echo json_encode(array('status' => 'erro'));
    }
}

if (isset($_POST['renovartoken'])) {
    $vencimentoatual = $_POST['vencimento'];
    $dataVencimentoAtual = DateTime::createFromFormat('d/m/Y', $vencimentoatual);
    if ($_POST['tipo'] == 'mensal') {
        $valor = 30;
        $tipo = 'mensal';
        $dataVencimentoAtual->add(new DateInterval('P30D'));
    } elseif ($_POST['tipo'] == 'trimestral') {
        $valor = 70;
        $tipo = 'trimestral';
        $dataVencimentoAtual->add(new DateInterval('P3M'));
    } elseif ($_POST['tipo'] == 'anual') {
        $valor = 150;
        $dataVencimentoAtual->add(new DateInterval('P1Y'));
        $tipo = 'anual';
    }
    $vencimento = $dataVencimentoAtual->format('d/m/Y');
    if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') {
    } else {
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Você não tem permissão para renovar tokens!'));
    }

    $descricao = 'Renovação de token';
    $token = consultaToken($_POST['id']);
    $renovar = '1';
    $pagamento = gerarpagamento($valor, $_POST['dominio'], $token, $tipo, $renovar, $descricao);

    if ($pagamento !== false) {
        echo json_encode(array('status' => 'sucesso', 'pagamento' => $pagamento, 'tipo' => $_POST['tipo'], 'dominio' => $_POST['dominio'], 'vencimento' => $vencimento));
    } else {
        echo json_encode(array('status' => 'erro'));
    }
}

if (isset($_POST['pesquisauser'])) {
    $termoPesquisa = $_POST['pesquisauser'];
    
    $resultados = pesquisarUsuario($termoPesquisa);
    if ($_SESSION['perm'] == 'SIM'){
        echo json_encode($resultados);
    }

}

if (isset($_POST['consultaruser'])) {
    $id = $_POST['id'];
    if ($_SESSION['perm'] == 'SIM'){
        $usuario = consultarUsuario($id);
        if ($usuario !== false) {
            echo json_encode(array('status' => 'sucesso', 'usuario' => $usuario));
        } else {
            echo json_encode(array('status' => 'erro'));
        }
    }
}

if (isset($_POST['editaruser'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $perm = $_POST['perm'];
    $admin = $_POST['admin'];
    
    $novasenha = $_POST['novasenha'];
    //se admin for true, perm tem que ser true
    if ($perm == 'SIM') {
        $perm = 'SIM';
    }else{
        $perm = '0';
    }
    if ($admin == 'SIM') {
        $admin = 'SIM';
    }else{
        $admin = '0';
    }

    $senha = $_POST['senha'];
    if ($_SESSION['perm'] == 'SIM'){
        $result = editarUsuario($id, $nome, $email, $perm, $admin, $senha);
        if ($result !== false) {
            echo json_encode(array('status' => 'sucesso', 'mensagem' => 'Usuario editado com sucesso!'));
            sendlogdiscord(
                'contaeditada',
'Conta editada com sucesso!
Nome: ' . $nome . '
Email: ' . $email . '
Permissão: ' . $perm . '
Admin: ' . $admin . '
Editado por: ' . $_SESSION['nome'] . '
Email: ' . $_SESSION['email']
            );
        } else {
            echo json_encode(array('status' => 'erro', 'mensagem' => 'Erro ao editar usuario!'));
        }

    } else {
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Você não tem permissão para editar usuarios!'));
    }
}

if (isset($_POST['acao_atualizacao']) && $_POST['acao_atualizacao'] === 'logs') {
    if ($_SESSION['perm'] == 'SIM') {
        include_once './controlador/atualizacoes_funcoes.php';
        $id = (int)($_POST['id'] ?? 0);
        $logs = logsAtualizacao($id);
        echo json_encode(['status' => 'sucesso', 'logs' => $logs]);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => 'Sem permissão.']);
    }
    exit;
}

if (isset($_POST['gerartokenadmin'])) {
    $dominio = $_POST['dominio'];
    $token = $_POST['token'];
    $tipo = $_POST['tipo'];
    if ($tipo == 'mensal') {
        $vencimento = new DateTime();
        $vencimento->add(new DateInterval('P30D'));
    } elseif ($tipo == 'trimestral') {
        $vencimento = new DateTime();
        $vencimento->add(new DateInterval('P3M'));
    } elseif ($tipo == 'anual') {
        $vencimento = new DateTime();
        $vencimento->add(new DateInterval('P1Y'));
    }
    $vencimentoFormatado = $vencimento->format('d/m/Y');
    $result = gerarTokenadmin($dominio, $token, $tipo, $vencimentoFormatado);
    if ($result !== false) {
        echo json_encode(array('status' => 'sucesso', 'mensagem' => 'Token gerado com sucesso!', 'token' => $token, 'dominio' => $dominio, 'tipo' => $tipo, 'vencimento' => $vencimentoFormatado));
        sendlogdiscord(
            'tokengeradoadmin',
'Token gerado com sucesso! 
Token: ' . $token . ' 
Dominio: ' . $dominio . ' 
Tipo: ' . $tipo . ' 
Vencimento: ' . $vencimentoFormatado . ' 
Gerado por: ' . $_SESSION['nome'] . ' 
Email: ' . $_SESSION['email']
        );
        
        } else {
        echo json_encode(array('status' => 'erro', 'mensagem' => 'Erro ao gerar token!'));
    }
}

?>
