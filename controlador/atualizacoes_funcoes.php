<?php
/**
 * Funções do Sistema de Atualização Remota
 */

define('MAX_UPLOAD_SIZE', 52428800); // 50 MB

/**
 * Lista todas as atualizações
 */
function listarAtualizacoes() {
    global $conn;
    $sql = "SELECT a.*, c.name AS criado_por_nome
            FROM atualizacoes a
            LEFT JOIN contas c ON c.id = a.criado_por
            ORDER BY a.data_criacao DESC";
    $result = mysqli_query($conn, $sql);
    $atualizacoes = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $atualizacoes[] = $row;
    }
    return $atualizacoes;
}

/**
 * Cria uma nova atualização
 */
function criarAtualizacao($dados, $arquivo) {
    global $conn;

    $pasta = './uploads/atualizacoes/';
    if (!is_dir($pasta)) {
        mkdir($pasta, 0755, true);
    }

    $nomeOriginal = basename($arquivo['name']);
    $ext = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));

    if ($ext !== 'zip') {
        return 'arquivo_invalido';
    }

    if ($arquivo['size'] > MAX_UPLOAD_SIZE) {
        return 'arquivo_grande';
    }

    $nomeArquivo = 'atualizacao_' . uniqid() . '.zip';
    $caminhoFinal = $pasta . $nomeArquivo;

    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoFinal)) {
        return 'erro_upload';
    }

    $hash = md5_file($caminhoFinal);
    $tamanho = filesize($caminhoFinal);

    $titulo     = htmlspecialchars(strip_tags($dados['titulo']));
    $versao     = htmlspecialchars(strip_tags($dados['versao']));
    $descricao  = htmlspecialchars(strip_tags($dados['descricao']));
    $prioridade = in_array($dados['prioridade'], ['baixa', 'media', 'alta', 'critica']) ? $dados['prioridade'] : 'media';
    $status     = in_array($dados['status'], ['ativo', 'inativo']) ? $dados['status'] : 'ativo';
    $criado_por = (int)$dados['criado_por'];

    $sql = "INSERT INTO atualizacoes (titulo, versao, descricao, arquivo, tamanho, hash_md5, prioridade, status, criado_por)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'ssssisssi',
        $titulo, $versao, $descricao, $nomeArquivo, $tamanho, $hash, $prioridade, $status, $criado_por
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return $ok ? 'sucesso' : 'erro_db';
}

/**
 * Edita uma atualização existente
 */
function editarAtualizacao($id, $dados) {
    global $conn;
    $titulo     = htmlspecialchars(strip_tags($dados['titulo']));
    $versao     = htmlspecialchars(strip_tags($dados['versao']));
    $descricao  = htmlspecialchars(strip_tags($dados['descricao']));
    $prioridade = in_array($dados['prioridade'], ['baixa', 'media', 'alta', 'critica']) ? $dados['prioridade'] : 'media';
    $status     = in_array($dados['status'], ['ativo', 'inativo']) ? $dados['status'] : 'ativo';

    $sql = "UPDATE atualizacoes SET titulo = ?, versao = ?, descricao = ?, prioridade = ?, status = ? WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'sssssi', $titulo, $versao, $descricao, $prioridade, $status, $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

/**
 * Deleta uma atualização e seu arquivo
 */
function deletarAtualizacao($id) {
    global $conn;
    $id = (int)$id;

    $sql = "SELECT arquivo FROM atualizacoes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if (!$row) {
        return false;
    }

    $caminho = './uploads/atualizacoes/' . $row['arquivo'];
    if (file_exists($caminho)) {
        unlink($caminho);
    }

    $sql = "DELETE FROM atualizacoes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

/**
 * Verifica se há atualização disponível para o token/domínio/versão informados
 */
function verificarAtualizacao($token, $dominio, $versao_atual) {
    global $conn;

    // Validate token exists and is active
    $sqlToken = "SELECT id FROM tokens WHERE token = ? AND dominio = ?";
    $stmtToken = mysqli_prepare($conn, $sqlToken);
    mysqli_stmt_bind_param($stmtToken, 'ss', $token, $dominio);
    mysqli_stmt_execute($stmtToken);
    $resToken = mysqli_stmt_get_result($stmtToken);
    $rowToken = mysqli_fetch_assoc($resToken);
    mysqli_stmt_close($stmtToken);

    if (!$rowToken) {
        return ['valido' => false, 'motivo' => 'token_invalido'];
    }

    // Get latest active update with version greater than current
    $sql = "SELECT id, titulo, versao, descricao, tamanho, hash_md5, prioridade
            FROM atualizacoes
            WHERE status = 'ativo'
            ORDER BY data_criacao DESC
            LIMIT 1";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    if (!$row) {
        return ['valido' => true, 'tem_atualizacao' => false];
    }

    if (version_compare($row['versao'], $versao_atual, '>')) {
        return ['valido' => true, 'tem_atualizacao' => true, 'atualizacao' => $row];
    }

    return ['valido' => true, 'tem_atualizacao' => false];
}

/**
 * Registra um download no log
 */
function registrarDownload($atualizacao_id, $token, $dominio, $ip, $user_agent = '') {
    global $conn;
    $atualizacao_id = (int)$atualizacao_id;
    $token      = htmlspecialchars(strip_tags($token));
    $dominio    = htmlspecialchars(strip_tags($dominio));
    $ip         = htmlspecialchars(strip_tags($ip));
    $user_agent = htmlspecialchars(strip_tags($user_agent));

    $sql = "INSERT INTO atualizacoes_log (atualizacao_id, token, dominio, ip, user_agent) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'issss', $atualizacao_id, $token, $dominio, $ip, $user_agent);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Increment download counter
    $sqlUpdate = "UPDATE atualizacoes SET downloads = downloads + 1 WHERE id = ?";
    $stmtUpdate = mysqli_prepare($conn, $sqlUpdate);
    mysqli_stmt_bind_param($stmtUpdate, 'i', $atualizacao_id);
    mysqli_stmt_execute($stmtUpdate);
    mysqli_stmt_close($stmtUpdate);
}

/**
 * Retorna estatísticas de downloads para uma atualização
 */
function estatisticasAtualizacoes($atualizacao_id) {
    global $conn;
    $atualizacao_id = (int)$atualizacao_id;
    $sql = "SELECT status, COUNT(*) AS total FROM atualizacoes_log WHERE atualizacao_id = ? GROUP BY status";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $atualizacao_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $stats = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $stats[$row['status']] = (int)$row['total'];
    }
    mysqli_stmt_close($stmt);
    return $stats;
}

/**
 * Busca os logs de download de uma atualização
 */
function logsAtualizacao($atualizacao_id) {
    global $conn;
    $atualizacao_id = (int)$atualizacao_id;
    $sql = "SELECT * FROM atualizacoes_log WHERE atualizacao_id = ? ORDER BY data_download DESC LIMIT 100";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $atualizacao_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $logs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $logs[] = $row;
    }
    mysqli_stmt_close($stmt);
    return $logs;
}

/**
 * Busca uma atualização pelo ID
 */
function buscarAtualizacaoPorId($id) {
    global $conn;
    $id = (int)$id;
    $sql = "SELECT * FROM atualizacoes WHERE id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 'i', $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
    return $row ?: false;
}
