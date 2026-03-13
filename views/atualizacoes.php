<?php
session_start();
include 'header.php';
include 'modal.php';

if ($_SESSION['perm'] != 'SIM') {
    echo "<script>window.location.href = 'home';</script>";
    exit;
}

include_once './controlador/atualizacoes_funcoes.php';

$atualizacoes = listarAtualizacoes();

// Handle form actions via POST (non-AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['acao'])) {
        $acao = $_POST['acao'];

        if ($acao === 'criar' && isset($_FILES['arquivo'])) {
            $dados = [
                'titulo'     => $_POST['titulo'] ?? '',
                'versao'     => $_POST['versao'] ?? '',
                'descricao'  => $_POST['descricao'] ?? '',
                'prioridade' => $_POST['prioridade'] ?? 'media',
                'status'     => $_POST['status'] ?? 'ativo',
                'criado_por' => $_SESSION['id'],
            ];
            $resultado = criarAtualizacao($dados, $_FILES['arquivo']);
            $msgCriar = $resultado;
        }

        if ($acao === 'editar') {
            $id = (int)($_POST['id'] ?? 0);
            $dados = [
                'titulo'     => $_POST['titulo'] ?? '',
                'versao'     => $_POST['versao'] ?? '',
                'descricao'  => $_POST['descricao'] ?? '',
                'prioridade' => $_POST['prioridade'] ?? 'media',
                'status'     => $_POST['status'] ?? 'ativo',
            ];
            $ok = editarAtualizacao($id, $dados);
            $msgEditar = $ok ? 'sucesso' : 'erro';
        }

        if ($acao === 'deletar') {
            $id = (int)($_POST['id'] ?? 0);
            deletarAtualizacao($id);
        }

        // Reload list after action
        $atualizacoes = listarAtualizacoes();
    }
}
?>

<div class="content-wrapper">
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 breadcrumb-wrapper mb-4">
      <span class="text-muted fw-light">Atlas /</span> Atualizações
    </h4>

    <!-- Alerts -->
    <?php if (isset($msgCriar)): ?>
      <?php if ($msgCriar === 'sucesso'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          ✅ Atualização criada com sucesso!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php else: ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          ❌ Erro ao criar atualização: <?php echo htmlspecialchars($msgCriar); ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($msgEditar)): ?>
      <?php if ($msgEditar === 'sucesso'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          ✅ Atualização editada com sucesso!
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php else: ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          ❌ Erro ao editar atualização.
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endif; ?>
    <?php endif; ?>

    <!-- Header Card -->
    <div class="card mb-4" style="background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); color:#fff;">
      <div class="card-body d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div>
          <h4 class="card-title mb-1" style="color:#fff;">🔄 Gerenciar Atualizações</h4>
          <p class="mb-0" style="opacity:.85;">Envie e gerencie atualizações para os sites remotos.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
          <button class="btn btn-light fw-semibold" data-bs-toggle="modal" data-bs-target="#modalNovaAtualizacao">
            📦 Nova Atualização
          </button>
          <button class="btn btn-outline-light fw-semibold" data-bs-toggle="modal" data-bs-target="#modalEstatisticas">
            📊 Estatísticas
          </button>
        </div>
      </div>
    </div>

    <!-- Update Cards Grid -->
    <div class="row g-3" id="listaAtualizacoes">
      <?php if (empty($atualizacoes)): ?>
        <div class="col-12">
          <div class="card text-center p-5">
            <p class="text-muted mb-0">Nenhuma atualização cadastrada. Clique em <strong>📦 Nova Atualização</strong> para começar.</p>
          </div>
        </div>
      <?php else: ?>
        <?php foreach ($atualizacoes as $at):
          $gradient = 'linear-gradient(135deg,#11998e,#38ef7d)';
          $badgeClass = 'bg-success';
          if ($at['prioridade'] === 'critica') {
              $gradient = 'linear-gradient(135deg,#ff416c,#ff4b2b)';
              $badgeClass = 'bg-danger';
          } elseif ($at['prioridade'] === 'alta') {
              $gradient = 'linear-gradient(135deg,#f093fb,#f5576c)';
              $badgeClass = 'bg-warning text-dark';
          } elseif ($at['prioridade'] === 'media') {
              $gradient = 'linear-gradient(135deg,#fa709a,#fee140)';
              $badgeClass = 'bg-warning text-dark';
          }
          $tamanhoMB = $at['tamanho'] ? round($at['tamanho'] / 1048576, 2) . ' MB' : '—';
          $dataCriacao = date('d/m/Y', strtotime($at['data_criacao']));
          $statusBadge = $at['status'] === 'ativo'
              ? '<span class="badge bg-success">✅ Ativo</span>'
              : '<span class="badge bg-secondary">⏸️ Inativo</span>';
          $prioridadeLabel = ucfirst($at['prioridade']);
        ?>
        <div class="col-12 col-md-6 col-xl-4">
          <div class="card h-100 shadow-sm">
            <div class="card-header text-white" style="background:<?php echo $gradient; ?>; border-radius:.375rem .375rem 0 0;">
              <div class="d-flex align-items-center justify-content-between">
                <span class="fw-bold fs-6">📦 v<?php echo htmlspecialchars($at['versao']); ?> — <?php echo htmlspecialchars($at['titulo']); ?></span>
                <?php echo $statusBadge; ?>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex flex-wrap gap-2 mb-2 text-muted small">
                <span>📅 <?php echo $dataCriacao; ?></span>
                <span>👤 <?php echo htmlspecialchars($at['criado_por_nome'] ?? 'Sistema'); ?></span>
                <span>💾 <?php echo $tamanhoMB; ?></span>
                <span>⬇️ <?php echo (int)$at['downloads']; ?> downloads</span>
              </div>
              <div class="mb-2">
                <span class="badge <?php echo $badgeClass; ?>">🔴 Prioridade: <?php echo $prioridadeLabel; ?></span>
              </div>
              <?php if ($at['descricao']): ?>
                <p class="small text-muted mb-0" style="white-space:pre-wrap;"><?php echo htmlspecialchars(mb_substr($at['descricao'], 0, 120)) . (mb_strlen($at['descricao']) > 120 ? '…' : ''); ?></p>
              <?php endif; ?>
            </div>
            <div class="card-footer d-flex flex-wrap gap-1">
              <button class="btn btn-sm btn-outline-info btn-logs"
                      data-id="<?php echo $at['id']; ?>">
                📜 Logs
              </button>
              <a href="download_atualizacao?id=<?php echo $at['id']; ?>&token=admin_preview" class="btn btn-sm btn-outline-secondary">
                ⬇️ Download
              </a>
              <button class="btn btn-sm btn-outline-primary btn-editar"
                      data-id="<?php echo $at['id']; ?>"
                      data-titulo="<?php echo htmlspecialchars($at['titulo'], ENT_QUOTES); ?>"
                      data-versao="<?php echo htmlspecialchars($at['versao'], ENT_QUOTES); ?>"
                      data-descricao="<?php echo htmlspecialchars($at['descricao'], ENT_QUOTES); ?>"
                      data-prioridade="<?php echo htmlspecialchars($at['prioridade'], ENT_QUOTES); ?>"
                      data-status="<?php echo htmlspecialchars($at['status'], ENT_QUOTES); ?>">
                ✏️ Editar
              </button>
              <button class="btn btn-sm btn-outline-danger btn-deletar"
                      data-id="<?php echo $at['id']; ?>"
                      data-nome="<?php echo htmlspecialchars($at['titulo'], ENT_QUOTES); ?>">
                🗑️ Deletar
              </button>
            </div>
          </div>
        </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

  </div>
</div>

<!-- ===== Modal: Nova Atualização ===== -->
<div class="modal fade" id="modalNovaAtualizacao" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;">
        <h5 class="modal-title">📦 Nova Atualização</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="acao" value="criar">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-semibold">Título da Atualização</label>
              <input type="text" name="titulo" class="form-control" placeholder="Ex: Correção de Bugs v2.0" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Versão</label>
              <input type="text" name="versao" class="form-control" placeholder="Ex: 2.0.0" pattern="[0-9]+\.[0-9]+\.[0-9]+" title="Formato: x.x.x" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Prioridade</label>
              <select name="prioridade" class="form-select">
                <option value="baixa">🟢 Baixa</option>
                <option value="media" selected>🟡 Média</option>
                <option value="alta">🟠 Alta</option>
                <option value="critica">🔴 Crítica</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" class="form-select">
                <option value="ativo">✅ Ativo</option>
                <option value="inativo">⏸️ Inativo</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Descrição / Changelog</label>
              <textarea name="descricao" class="form-control" rows="4" placeholder="Descreva as mudanças desta versão..."></textarea>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Arquivo ZIP <span class="text-muted">(máx. 50 MB)</span></label>
              <div id="dropZone" class="border border-2 border-dashed rounded p-4 text-center" style="cursor:pointer; transition:.2s;"
                   ondragover="event.preventDefault(); this.classList.add('border-primary');"
                   ondragleave="this.classList.remove('border-primary');"
                   ondrop="handleDrop(event)">
                <div id="dropZoneText">
                  <span style="font-size:2rem;">📁</span><br>
                  <span class="text-muted">Arraste o arquivo ZIP aqui ou</span><br>
                  <button type="button" class="btn btn-sm btn-outline-primary mt-2" onclick="document.getElementById('arquivoUpload').click()">Clique para enviar</button>
                </div>
                <div id="dropZoneFile" class="d-none text-success fw-semibold"></div>
              </div>
              <input type="file" name="arquivo" id="arquivoUpload" accept=".zip" class="d-none" onchange="mostrarArquivo(this)">
            </div>
            <!-- Progress bar (shown during upload) -->
            <div class="col-12 d-none" id="progressContainer">
              <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width:0%" id="progressBar"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn text-white fw-semibold" style="background:linear-gradient(135deg,#667eea,#764ba2);">✅ Enviar Atualização</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ===== Modal: Editar Atualização ===== -->
<div class="modal fade" id="modalEditarAtualizacao" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;">
        <h5 class="modal-title">✏️ Editar Atualização</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST">
        <input type="hidden" name="acao" value="editar">
        <input type="hidden" name="id" id="editarId">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-md-8">
              <label class="form-label fw-semibold">Título</label>
              <input type="text" name="titulo" id="editarTitulo" class="form-control" required>
            </div>
            <div class="col-md-4">
              <label class="form-label fw-semibold">Versão</label>
              <input type="text" name="versao" id="editarVersao" class="form-control" required>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Prioridade</label>
              <select name="prioridade" id="editarPrioridade" class="form-select">
                <option value="baixa">🟢 Baixa</option>
                <option value="media">🟡 Média</option>
                <option value="alta">🟠 Alta</option>
                <option value="critica">🔴 Crítica</option>
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Status</label>
              <select name="status" id="editarStatus" class="form-select">
                <option value="ativo">✅ Ativo</option>
                <option value="inativo">⏸️ Inativo</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Descrição / Changelog</label>
              <textarea name="descricao" id="editarDescricao" class="form-control" rows="4"></textarea>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-primary fw-semibold">💾 Salvar</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ===== Modal: Logs ===== -->
<div class="modal fade" id="modalLogs" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">📜 Logs de Download</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div id="logsConteudo">
          <p class="text-center text-muted">Carregando...</p>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== Modal: Deletar ===== -->
<div class="modal fade" id="modalDeletar" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header border-0">
        <h5 class="modal-title">🗑️ Confirmar Exclusão</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center">
        <p>Tem certeza que deseja excluir a atualização <strong id="deletarNome"></strong>?<br>
        <span class="text-danger">Esta ação não pode ser desfeita.</span></p>
      </div>
      <form method="POST">
        <input type="hidden" name="acao" value="deletar">
        <input type="hidden" name="id" id="deletarId">
        <div class="modal-footer border-0 justify-content-center">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
          <button type="submit" class="btn btn-danger">🗑️ Excluir</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- ===== Modal: Estatísticas ===== -->
<div class="modal fade" id="modalEstatisticas" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#667eea,#764ba2);color:#fff;">
        <h5 class="modal-title">📊 Estatísticas de Downloads</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>Versão</th>
                <th>Título</th>
                <th>Prioridade</th>
                <th>Status</th>
                <th>Downloads</th>
                <th>Data</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($atualizacoes as $at): ?>
              <tr>
                <td><span class="badge bg-label-primary">v<?php echo htmlspecialchars($at['versao']); ?></span></td>
                <td><?php echo htmlspecialchars($at['titulo']); ?></td>
                <td><?php echo ucfirst($at['prioridade']); ?></td>
                <td><?php echo $at['status'] === 'ativo' ? '<span class="badge bg-success">Ativo</span>' : '<span class="badge bg-secondary">Inativo</span>'; ?></td>
                <td><?php echo (int)$at['downloads']; ?></td>
                <td><?php echo date('d/m/Y H:i', strtotime($at['data_criacao'])); ?></td>
              </tr>
              <?php endforeach; ?>
              <?php if (empty($atualizacoes)): ?>
              <tr><td colspan="6" class="text-center text-muted">Nenhuma atualização ainda.</td></tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include 'footer.php'; ?>

<script>
// --- Drag & Drop ---
function handleDrop(e) {
    e.preventDefault();
    document.getElementById('dropZone').classList.remove('border-primary');
    var file = e.dataTransfer.files[0];
    if (file && file.name.toLowerCase().endsWith('.zip')) {
        var dt = new DataTransfer();
        dt.items.add(file);
        document.getElementById('arquivoUpload').files = dt.files;
        mostrarArquivoNome(file.name, file.size);
    } else {
        alert('Por favor, selecione um arquivo ZIP válido.');
    }
}

function mostrarArquivo(input) {
    if (input.files && input.files[0]) {
        mostrarArquivoNome(input.files[0].name, input.files[0].size);
    }
}

function mostrarArquivoNome(nome, tamanho) {
    var mb = (tamanho / 1048576).toFixed(2);
    document.getElementById('dropZoneText').classList.add('d-none');
    document.getElementById('dropZoneFile').classList.remove('d-none');
    document.getElementById('dropZoneFile').innerHTML = '✅ ' + nome + ' (' + mb + ' MB)';
}

// --- Event delegation for edit, delete and logs buttons ---
document.addEventListener('DOMContentLoaded', function () {

    document.getElementById('listaAtualizacoes').addEventListener('click', function (e) {
        var btn = e.target.closest('button');
        if (!btn) return;

        if (btn.classList.contains('btn-editar')) {
            document.getElementById('editarId').value       = btn.dataset.id;
            document.getElementById('editarTitulo').value   = btn.dataset.titulo;
            document.getElementById('editarVersao').value   = btn.dataset.versao;
            document.getElementById('editarDescricao').value= btn.dataset.descricao;
            document.getElementById('editarPrioridade').value = btn.dataset.prioridade;
            document.getElementById('editarStatus').value   = btn.dataset.status;
            new bootstrap.Modal(document.getElementById('modalEditarAtualizacao')).show();
        }

        if (btn.classList.contains('btn-deletar')) {
            document.getElementById('deletarId').value = btn.dataset.id;
            document.getElementById('deletarNome').textContent = btn.dataset.nome;
            new bootstrap.Modal(document.getElementById('modalDeletar')).show();
        }

        if (btn.classList.contains('btn-logs')) {
            verLogs(btn.dataset.id);
        }
    });
});

// --- Logs via AJAX ---
function verLogs(id) {
    document.getElementById('logsConteudo').innerHTML = '<p class="text-center text-muted">Carregando...</p>';
    new bootstrap.Modal(document.getElementById('modalLogs')).show();
    $.ajax({
        url: 'api',
        method: 'POST',
        data: { acao_atualizacao: 'logs', id: id },
        dataType: 'json',
        success: function(json) {
            if (json.logs && json.logs.length > 0) {
                var html = '<div class="table-responsive"><table class="table table-bordered table-sm align-middle">' +
                    '<thead class="table-light"><tr><th>Token</th><th>Domínio</th><th>IP</th><th>Status</th><th>Data</th></tr></thead><tbody>';
                json.logs.forEach(function(log) {
                    var badge = log.status === 'baixado' ? 'bg-primary' : (log.status === 'instalado' ? 'bg-success' : 'bg-danger');
                    html += '<tr>' +
                        '<td><code>' + log.token.substring(0, 16) + '…</code></td>' +
                        '<td>' + log.dominio + '</td>' +
                        '<td>' + log.ip + '</td>' +
                        '<td><span class="badge ' + badge + '">' + log.status + '</span></td>' +
                        '<td>' + log.data_download + '</td>' +
                        '</tr>';
                });
                html += '</tbody></table></div>';
                document.getElementById('logsConteudo').innerHTML = html;
            } else {
                document.getElementById('logsConteudo').innerHTML = '<p class="text-center text-muted">Nenhum download registrado ainda.</p>';
            }
        },
        error: function() {
            document.getElementById('logsConteudo').innerHTML = '<p class="text-center text-danger">Erro ao carregar logs.</p>';
        }
    });
}
</script>
