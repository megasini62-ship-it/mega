<?php
/**
 * Download seguro de arquivos de atualização.
 *
 * Uso: GET /download_atualizacao?id=5&token=XXX
 */

include_once './controlador/funcoes.php';
include_once './controlador/atualizacoes_funcoes.php';

$id    = isset($_GET['id'])    ? (int)$_GET['id']            : 0;
$token = isset($_GET['token']) ? trim($_GET['token'])         : '';

if ($id <= 0 || empty($token)) {
    http_response_code(400);
    die('Parâmetros inválidos.');
}

// Validate token (allow admin_preview only from localhost for testing)
$ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

if ($token !== 'admin_preview') {
    $dominio = $_SERVER['HTTP_HOST'] ?? '';
    $sqlToken = "SELECT id FROM tokens WHERE token = ? AND dominio = ?";
    $stmtToken = mysqli_prepare($conn, $sqlToken);
    mysqli_stmt_bind_param($stmtToken, 'ss', $token, $dominio);
    mysqli_stmt_execute($stmtToken);
    $resToken = mysqli_stmt_get_result($stmtToken);
    $rowToken = mysqli_fetch_assoc($resToken);
    mysqli_stmt_close($stmtToken);

    if (!$rowToken) {
        http_response_code(403);
        die('Token inválido ou não autorizado.');
    }
}

// Fetch update record
$atualizacao = buscarAtualizacaoPorId($id);

if (!$atualizacao) {
    http_response_code(404);
    die('Atualização não encontrada.');
}

if ($atualizacao['status'] !== 'ativo') {
    http_response_code(403);
    die('Esta atualização não está disponível para download.');
}

$caminho = realpath('./uploads/atualizacoes/' . $atualizacao['arquivo']);
$pastaBase = realpath('./uploads/atualizacoes');

// Path traversal prevention
if ($caminho === false || strpos($caminho, $pastaBase) !== 0 || !file_exists($caminho)) {
    http_response_code(404);
    die('Arquivo não encontrado.');
}

// Register download log
$dominio    = $_SERVER['HTTP_HOST'] ?? 'desconhecido';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
registrarDownload($id, $token, $dominio, $ip, $user_agent);

// Serve the file
$nomeDownload = 'atualizacao_v' . preg_replace('/[^0-9.]/', '', $atualizacao['versao']) . '.zip';

header('Content-Description: File Transfer');
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="' . $nomeDownload . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($caminho));

if (ob_get_level()) {
    ob_end_clean();
}
readfile($caminho);
exit;
