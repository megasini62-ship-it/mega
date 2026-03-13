<?php
/**
 * API pública para verificação de atualizações por sites remotos.
 *
 * Uso: GET /api_atualizacao?token=XXX&dominio=exemplo.com&versao_atual=1.0.0
 */

header('Content-Type: application/json; charset=utf-8');

include_once './controlador/funcoes.php';
include_once './controlador/atualizacoes_funcoes.php';

$token        = isset($_GET['token'])       ? trim($_GET['token'])       : '';
$dominio      = isset($_GET['dominio'])     ? trim($_GET['dominio'])     : '';
$versao_atual = isset($_GET['versao_atual'])? trim($_GET['versao_atual']): '0.0.0';

if (empty($token) || empty($dominio)) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Parâmetros obrigatórios ausentes: token, dominio']);
    exit;
}

// Sanitize inputs
$token        = htmlspecialchars(strip_tags($token));
$dominio      = htmlspecialchars(strip_tags($dominio));
$versao_atual = preg_replace('/[^0-9.]/', '', $versao_atual);

$resultado = verificarAtualizacao($token, $dominio, $versao_atual);

if (!$resultado['valido']) {
    echo json_encode(['status' => 'erro', 'mensagem' => 'Token ou domínio inválido.']);
    exit;
}

if (!$resultado['tem_atualizacao']) {
    echo json_encode([
        'status'           => 'success',
        'tem_atualizacao'  => false,
        'mensagem'         => 'Você já está na versão mais recente.',
    ]);
    exit;
}

$at = $resultado['atualizacao'];

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base_url  = $protocol . '://' . $_SERVER['HTTP_HOST'];

// Register the API check in log
$ip         = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';

echo json_encode([
    'status'          => 'success',
    'tem_atualizacao' => true,
    'atualizacao'     => [
        'id'           => (int)$at['id'],
        'titulo'       => $at['titulo'],
        'versao'       => $at['versao'],
        'descricao'    => $at['descricao'],
        'tamanho'      => (int)$at['tamanho'],
        'hash_md5'     => $at['hash_md5'],
        'prioridade'   => $at['prioridade'],
        'url_download' => $base_url . '/download_atualizacao?id=' . $at['id'] . '&token=' . urlencode($token),
    ],
]);
