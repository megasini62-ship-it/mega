<?php
/**
 * Script cliente para sites remotos.
 *
 * Coloque este arquivo na raiz do seu site remoto e configure
 * as variáveis abaixo. Execute-o manualmente ou via cron job
 * para verificar e aplicar atualizações automaticamente.
 *
 * Cron diário (exemplo):
 *   0 3 * * * php /caminho/para/atualizar_sistema.php >> /var/log/atualizacao.log 2>&1
 */

$config = [
    'url_api'       => 'https://seupainel.com/api_atualizacao',
    'url_download'  => 'https://seupainel.com/download_atualizacao',
    'token'         => 'SEU_TOKEN_AQUI',
    'dominio'       => $_SERVER['HTTP_HOST'] ?? 'localhost',
    'versao_atual'  => '1.0.0',
    'pasta_backup'  => __DIR__ . '/backups/',
    'pasta_temp'    => __DIR__ . '/temp/',
    'pasta_raiz'    => __DIR__ . '/',
];

// --- Funções auxiliares ---

function log_msg($msg) {
    echo '[' . date('Y-m-d H:i:s') . '] ' . $msg . PHP_EOL;
}

function verificarAtualizacaoRemota($config) {
    $url = $config['url_api']
         . '?token=' . urlencode($config['token'])
         . '&dominio=' . urlencode($config['dominio'])
         . '&versao_atual=' . urlencode($config['versao_atual']);

    $ctx = stream_context_create(['http' => ['timeout' => 15]]);
    $resp = @file_get_contents($url, false, $ctx);
    if ($resp === false) {
        log_msg('ERRO: Não foi possível conectar à API.');
        return null;
    }
    $json = json_decode($resp, true);
    if (!$json || $json['status'] !== 'success') {
        log_msg('ERRO: Resposta inválida da API.');
        return null;
    }
    return $json;
}

function baixarAtualizacao($url_download, $pasta_temp) {
    if (!is_dir($pasta_temp)) {
        mkdir($pasta_temp, 0755, true);
    }
    $arquivo_temp = $pasta_temp . 'atualizacao_' . time() . '.zip';
    $ctx = stream_context_create(['http' => ['timeout' => 120]]);
    $dados = @file_get_contents($url_download, false, $ctx);
    if ($dados === false || strlen($dados) === 0) {
        log_msg('ERRO: Falha ao baixar o arquivo ZIP.');
        return false;
    }
    file_put_contents($arquivo_temp, $dados);
    return $arquivo_temp;
}

function fazerBackup($pasta_raiz, $pasta_backup) {
    if (!is_dir($pasta_backup)) {
        mkdir($pasta_backup, 0755, true);
    }
    $arquivo_backup = $pasta_backup . 'backup_' . date('Ymd_His') . '.zip';
    $zip = new ZipArchive();
    if ($zip->open($arquivo_backup, ZipArchive::CREATE) !== true) {
        log_msg('ERRO: Não foi possível criar o arquivo de backup.');
        return false;
    }
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($pasta_raiz, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::LEAVES_ONLY
    );
    foreach ($iterator as $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($pasta_raiz));
            // Skip backup and temp directories
            $relPathNorm = str_replace('\\', '/', $relativePath);
            if (strpos($relPathNorm, 'backups/') === 0 || strpos($relPathNorm, 'temp/') === 0) {
                continue;
            }
            $zip->addFile($filePath, $relativePath);
        }
    }
    $zip->close();
    log_msg('Backup criado: ' . $arquivo_backup);
    return $arquivo_backup;
}

function verificarHash($arquivo, $hash_esperado) {
    if (empty($hash_esperado)) {
        return true; // Hash not provided, skip check
    }
    return md5_file($arquivo) === $hash_esperado;
}

function extrairAtualizacao($arquivo_zip, $pasta_destino) {
    $zip = new ZipArchive();
    if ($zip->open($arquivo_zip) !== true) {
        log_msg('ERRO: Não foi possível abrir o arquivo ZIP.');
        return false;
    }
    $zip->extractTo($pasta_destino);
    $zip->close();
    return true;
}

function rollback($arquivo_backup, $pasta_raiz) {
    log_msg('Iniciando rollback para: ' . $arquivo_backup);
    $zip = new ZipArchive();
    if ($zip->open($arquivo_backup) !== true) {
        log_msg('ERRO: Não foi possível abrir o backup para rollback.');
        return false;
    }
    $zip->extractTo($pasta_raiz);
    $zip->close();
    log_msg('Rollback concluído com sucesso.');
    return true;
}

// --- Fluxo principal ---

log_msg('=== Verificando atualizações ===');

$resultado = verificarAtualizacaoRemota($config);

if ($resultado === null) {
    log_msg('Encerrando: não foi possível verificar atualizações.');
    exit(1);
}

if (!$resultado['tem_atualizacao']) {
    log_msg('Sistema já está na versão mais recente (' . $config['versao_atual'] . '). Nenhuma atualização disponível.');
    exit(0);
}

$at = $resultado['atualizacao'];
log_msg('Nova versão disponível: v' . $at['versao'] . ' — ' . $at['titulo']);
log_msg('Prioridade: ' . strtoupper($at['prioridade']));

// 1. Fazer backup
log_msg('Fazendo backup...');
$backup = fazerBackup($config['pasta_raiz'], $config['pasta_backup']);
if (!$backup) {
    log_msg('AVISO: Backup falhou. Abortando atualização por segurança.');
    exit(1);
}

// 2. Baixar atualização
log_msg('Baixando atualização...');
$arquivo_zip = baixarAtualizacao($at['url_download'], $config['pasta_temp']);
if (!$arquivo_zip) {
    log_msg('ERRO: Falha no download. Abortando.');
    exit(1);
}

// 3. Verificar hash
if (!verificarHash($arquivo_zip, $at['hash_md5'])) {
    log_msg('ERRO: Hash MD5 inválido. O arquivo pode estar corrompido. Abortando.');
    @unlink($arquivo_zip);
    exit(1);
}
log_msg('Hash MD5 verificado com sucesso.');

// 4. Extrair e aplicar
log_msg('Aplicando atualização...');
if (!extrairAtualizacao($arquivo_zip, $config['pasta_raiz'])) {
    log_msg('ERRO: Falha ao extrair ZIP. Iniciando rollback...');
    rollback($backup, $config['pasta_raiz']);
    exit(1);
}

// 5. Limpeza
@unlink($arquivo_zip);

log_msg('✅ Atualização v' . $at['versao'] . ' aplicada com sucesso!');
log_msg('Lembre-se de atualizar a variável $config[\'versao_atual\'] para: ' . $at['versao']);
exit(0);
