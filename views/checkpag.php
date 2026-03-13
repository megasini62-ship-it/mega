<?php
ignore_user_abort(true);
set_time_limit(0);
$start_time = microtime(true);
$lockfile = 'lockfile.txt';
#aguarda 3 segundos para executar o script
sleep(3);
// Abre o arquivo de bloqueio com exclusão e bloqueio
$handle = fopen($lockfile, 'w+');
if (!flock($handle, LOCK_EX | LOCK_NB)) {
    echo "Outra pessoa já está acessando a página, tente novamente mais tarde.";
    exit;
}


include_once './controlador/funcoes.php';
$pagamentos = checkTodosPagamentos();

foreach ($pagamentos as $pagamento) {
    if ($pagamento['renovar'] == '1' && $pagamento['status'] == 'approved') {
        $token = consultaTokenToken($pagamento['token']);
        $vencimentotoken = $token['vencimento'];
        $dataVencimentoAtual = DateTime::createFromFormat('d/m/Y', $vencimentotoken);

        if ($pagamento['tipo'] == 'mensal') {
            $dataVencimentoAtual->add(new DateInterval('P30D'));
        } elseif ($pagamento['tipo'] == 'trimestral') {
            $dataVencimentoAtual->add(new DateInterval('P3M'));
        } elseif ($pagamento['tipo'] == 'anual') {
            $dataVencimentoAtual->add(new DateInterval('P1Y'));
        }
        $vencimento = $dataVencimentoAtual->format('d/m/Y');
        $sql2 = "UPDATE tokens SET vencimento = ? WHERE token = ?";
        $stmt2 = mysqli_prepare($conn, $sql2);
        mysqli_stmt_bind_param($stmt2, "ss", $vencimento, $pagamento['token']);
        mysqli_stmt_execute($stmt2);
        mysqli_stmt_close($stmt2);
        $status = 'Aprovado';
        $sql3 = "UPDATE pagamentos SET status = ? WHERE id = ?";
        $stmt3 = mysqli_prepare($conn, $sql3);
        
        if ($stmt3) {
            mysqli_stmt_bind_param($stmt3, "ss", $status, $pagamento['id']);
            $success = mysqli_stmt_execute($stmt3);
            if ($success) {
                sendlogdiscord('tokengerado',
                'Token Gerado com sucesso!!!' . "\n" .
                'Token - ' . $pagamento['token'] . "\n" .
                'Dominio - ' . $pagamento['dominio'] . "\n" .
                'Vencimento - ' . $vencimento . "\n" .
                'Email - ' . $pagamento['email']
            );
                mysqli_stmt_close($stmt3);
            } else {
                $error = mysqli_stmt_error($stmt3);
                mysqli_stmt_close($stmt3);
            }
        } 
    }elseif ($pagamento['renovar'] == '0' && $pagamento['status'] == 'approved') {
        $dataVencimentoAtual = new DateTime();

        if ($pagamento['tipo'] == 'mensal') {
            $dataVencimentoAtual->add(new DateInterval('P30D'));
        } elseif ($pagamento['tipo'] == 'trimestral') {
            $dataVencimentoAtual->add(new DateInterval('P3M'));
        } elseif ($pagamento['tipo'] == 'anual') {
            $dataVencimentoAtual->add(new DateInterval('P1Y'));
        }
        $vencimento = $dataVencimentoAtual->format('d/m/Y');
		$checkSql = "SELECT COUNT(*) FROM tokens WHERE token = ?";
		$checkStmt = mysqli_prepare($conn, $checkSql);
		mysqli_stmt_bind_param($checkStmt, "s", $pagamento['token']);
		mysqli_stmt_execute($checkStmt);
		mysqli_stmt_bind_result($checkStmt, $count);
		mysqli_stmt_fetch($checkStmt);
		mysqli_stmt_close($checkStmt);
		
		if ($count > 0) {
			echo "Record already exists!";
		} else {
			$sql2 = "INSERT INTO tokens (token, dono, dominio, vencimento, contato) VALUES (?, '0', ?, ?, '0')";
			$stmt2 = mysqli_prepare($conn, $sql2);
			mysqli_stmt_bind_param($stmt2, "sss", $pagamento['token'], $pagamento['dominio'], $vencimento);
			mysqli_stmt_execute($stmt2);
			$status = 'Aprovado';
		
        $sql3 = "UPDATE pagamentos SET status = ? WHERE id = ?";
        $stmt3 = mysqli_prepare($conn, $sql3);
        
        if ($stmt3) {
            mysqli_stmt_bind_param($stmt3, "ss", $status, $pagamento['id']);
            $success = mysqli_stmt_execute($stmt3);
            if ($success) {
                mysqli_stmt_close($stmt3);
                sendlogdiscord('tokengerado',
                'Token Gerado com sucesso!!!' . "\n" .
                'Token - ' . $pagamento['token'] . "\n" .
                'Dominio - ' . $pagamento['dominio'] . "\n" .
                'Vencimento - ' . $vencimento . "\n" .
                'Email - ' . $pagamento['email']
            );
            

            } else {
                $error = mysqli_stmt_error($stmt3);
                mysqli_stmt_close($stmt3);
            }
        }
    }
}
}
$end_time = microtime(true);

// Calcula a diferença entre os tempos
$time_diff = $end_time - $start_time;

echo "O código levou " . $time_diff . " segundos para ser executado";
// Libera o bloqueio e fecha o arquivo de bloqueio
flock($handle, LOCK_UN);
fclose($handle);

// Remove o arquivo de bloqueio
unlink($lockfile);
