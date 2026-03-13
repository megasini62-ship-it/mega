<?php
session_start();
include 'header.php'; 
include 'modal.php';
if ($_SESSION['perm'] == 'SIM') {
    $tokens = listarUsuarios();
    $texto = 'Todos os usuários';
}else{
    echo "<script>window.location.href = 'home';</script>";
    exit;
}

$itensPorPagina = 10;
$paginaAtual = isset($_GET['page']) ? $_GET['page'] : 1;
$totalItens = count($tokens);
$totalPaginas = ceil($totalItens / $itensPorPagina);
$inicio = ($paginaAtual - 1) * $itensPorPagina;
$tokensPaginados = array_slice($tokens, $inicio, $itensPorPagina);
?>
<div class="col-12">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title mb-1">
        <i class="fa-solid fa-users me-2" style="color:#fa709a;"></i><?php echo $texto; ?>
      </h4>
      <small class="text-muted">Atlas / Usuários</small>
    </div>
  </div>

  <div class="card">
    <div class="card-body">
      <!-- Search -->
      <div class="d-flex gap-2 mb-4">
        <div class="search-wrapper flex-grow-1">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input type="text" class="form-control" name="pesquisa"
                 placeholder="Pesquisar por nome ou email..."
                 aria-label="Pesquisar" id="pesquisauser" style="padding-left:40px;" />
        </div>
        <button class="btn btn-gradient-primary d-flex align-items-center gap-2"
                type="button" id="button-searchuser">
          <i class="fa-solid fa-search"></i> Buscar
        </button>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="dt-advanced-search table">
          <thead>
            <tr>
              <th><i class="fa-solid fa-user me-1" style="color:#667eea;"></i> Nome</th>
              <th><i class="fa-solid fa-envelope me-1" style="color:#4facfe;"></i> Email</th>
              <th><i class="fa-solid fa-shield me-1" style="color:#fa709a;"></i> Permissão</th>
              <th><i class="fa-solid fa-gear me-1" style="color:#11998e;"></i> Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tokensPaginados as $token) { ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center gap-2">
                    <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                      <i class="fa-solid fa-user fa-xs text-white"></i>
                    </div>
                    <span style="font-weight:500;"><?php echo htmlspecialchars($token['name']); ?></span>
                  </div>
                </td>
                <td style="color:#718096;"><?php echo htmlspecialchars($token['email']); ?></td>
                <td>
                  <?php if ($token['perm'] == 'SIM') { ?>
                    <span class="badge-admin">
                      <i class="fa-solid fa-crown fa-xs me-1"></i>Administrator
                    </span>
                  <?php } elseif ($token['admin'] == 'SIM') { ?>
                    <span class="badge-revendedor">
                      <i class="fa-solid fa-star fa-xs me-1"></i>Revendedor
                    </span>
                  <?php } else { ?>
                    <span class="badge-usuario">
                      <i class="fa-solid fa-user fa-xs me-1"></i>Usuário
                    </span>
                  <?php } ?>
                </td>
                <td>
                  <button class="btn btn-sm btn-gradient-primary d-flex align-items-center gap-1"
                          onclick="abrirmodaleditaruser(<?php echo $token['id']; ?>)">
                    <i class="fa-solid fa-pen-to-square fa-xs"></i> Editar
                  </button>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <script>
        function abrirmodaleditaruser(id) {
            $('#modalEditarUser').modal('show');
            $.ajax({
                url: 'api',
                method: 'POST',
                data: { id: id, consultaruser: true },
                dataType: 'json',
                success: function (json) {
                    $('#idusuarioeditar').val(json.usuario.id);
                    $('#nomeusuarioeditar').val(json.usuario.name);
                    $('#emailusuarioeditar').val(json.usuario.email);
                    if (json.usuario.perm == 'SIM') {
                        $('#useradministrador').prop('checked', true);
                    } else {
                        $('#useradministrador').prop('checked', false);
                    }
                    if (json.usuario.admin == 'SIM') {
                        $('#userrevendedor').prop('checked', true);
                    } else {
                        $('#userrevendedor').prop('checked', false);
                    }
                },
                error: function (error) {
                    console.error('Erro ao editar o token:', error);
                }
            });
        }
      </script>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <li class="page-item <?php echo ($paginaAtual == 1) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=1"><i class="fa-solid fa-angles-left fa-xs"></i></a>
            </li>
            <li class="page-item <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=<?php echo ($paginaAtual > 1) ? ($paginaAtual - 1) : 1; ?>"><i class="fa-solid fa-angle-left fa-xs"></i></a>
            </li>
            <?php
            $numPaginasExibidas = 5;
            $paginaInicial = max(1, $paginaAtual - floor($numPaginasExibidas / 2));
            $paginaFinal = min($totalPaginas, $paginaInicial + $numPaginasExibidas - 1);
            for ($i = $paginaInicial; $i <= $paginaFinal; $i++) { ?>
              <li class="page-item <?php echo ($paginaAtual == $i) ? 'active' : ''; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
              </li>
            <?php } ?>
            <li class="page-item <?php echo ($paginaAtual >= $totalPaginas) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=<?php echo ($paginaAtual < $totalPaginas) ? ($paginaAtual + 1) : $totalPaginas; ?>"><i class="fa-solid fa-angle-right fa-xs"></i></a>
            </li>
            <li class="page-item <?php echo ($paginaAtual == $totalPaginas) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $totalPaginas; ?>"><i class="fa-solid fa-angles-right fa-xs"></i></a>
            </li>
          </ul>
        </nav>
      </div>

    </div>
  </div>
</div>

<?php if ($_SESSION['perm'] == 'SIM') { ?>
<div class="modal fade" id="modalEditarUser" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">
          <i class="fa-solid fa-user-pen me-2"></i>Editar Usuário
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-4">
        <div class="mb-3">
          <label class="form-label fw-semibold">ID</label>
          <input type="text" id="idusuarioeditar" class="form-control" placeholder="Id Usuario" disabled />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nome</label>
          <input type="text" id="nomeusuarioeditar" class="form-control" placeholder="Nome do Usuário" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email</label>
          <input type="text" id="emailusuarioeditar" class="form-control" placeholder="Email do Usuário" />
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Nova Senha (deixe em branco para manter)</label>
          <input type="text" id="senhausuarioeditar" class="form-control" placeholder="Nova Senha" />
        </div>
        <div class="mb-2">
          <label class="form-label fw-semibold">Permissão do Usuário</label>
          <div class="d-flex gap-4 mt-2">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="userrevendedor" />
              <label class="form-check-label" for="userrevendedor">
                <span class="badge-revendedor"><i class="fa-solid fa-star fa-xs me-1"></i>Revendedor</span>
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="useradministrador" />
              <label class="form-check-label" for="useradministrador">
                <span class="badge-admin"><i class="fa-solid fa-crown fa-xs me-1"></i>Administrador</span>
              </label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Fechar</button>
        <button type="button" id="editarusuario" class="btn btn-gradient-primary">
          <i class="fa-solid fa-floppy-disk me-1"></i> Salvar
        </button>
      </div>
    </div>
  </div>
</div>
<?php } ?>

<?php include 'footer.php'; ?>

<script>
    $('#button-searchuser').click(function () {
        var pesquisauser = $('#pesquisauser').val();
        $.ajax({
            url: 'api',
            method: 'POST',
            data: { pesquisauser: pesquisauser },
            dataType: 'json',
            success: function (json) {
                if (json.length > 0) {
                    atualizarTabelaTokens(json);
                } else {
                    errornotify('Nenhum resultado encontrado!')
                }
            },
            error: function (error) {
                console.error('Erro ao realizar a pesquisa:', error);
            }
        });

        function atualizarTabelaTokens(tokens) {
            $('.table tbody tr').remove();
            function escHtml(str) {
                var d = document.createElement('div');
                d.appendChild(document.createTextNode(str));
                return d.innerHTML;
            }
            for (var i = 0; i < tokens.length; i++) {
                var badgeHTML = "";
                if (tokens[i]['perm'] == 'SIM') {
                    badgeHTML = '<span class="badge-admin"><i class="fa-solid fa-crown fa-xs me-1"></i>Administrator</span>';
                } else if (tokens[i]['admin'] == 'SIM') {
                    badgeHTML = '<span class="badge-revendedor"><i class="fa-solid fa-star fa-xs me-1"></i>Revendedor</span>';
                } else {
                    badgeHTML = '<span class="badge-usuario"><i class="fa-solid fa-user fa-xs me-1"></i>Usuário</span>';
                }
                var safeName = escHtml(tokens[i]['name'] || '');
                var safeEmail = escHtml(tokens[i]['email'] || '');
                var safeId = parseInt(tokens[i]['id'], 10) || 0;
                var newRow = "<tr>" +
                    "<td><div class='d-flex align-items-center gap-2'><div style='width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;'><i class='fa-solid fa-user fa-xs text-white'></i></div><span style='font-weight:500;'>" + safeName + "</span></div></td>" +
                    "<td style='color:#718096;'>" + safeEmail + "</td>" +
                    "<td>" + badgeHTML + "</td>" +
                    "<td>" +
                    "<button class='btn btn-sm btn-gradient-primary d-flex align-items-center gap-1' onclick='abrirmodaleditaruser(" + safeId + ")'>" +
                    "<i class='fa-solid fa-pen-to-square fa-xs'></i> Editar" +
                    "</button>" +
                    "</td>" +
                    "</tr>";
                $('.table tbody').append(newRow);
            }
        }
    });

    $('#editarusuario').click(function () {
        var id = $('#idusuarioeditar').val();
        var nome = $('#nomeusuarioeditar').val();
        var email = $('#emailusuarioeditar').val();
        var perm = $('#useradministrador').is(':checked');
        var admin = $('#userrevendedor').is(':checked');
        var senha = $('#senhausuarioeditar').val();
        if (senha != '') {
            novasenha = true;
        }else{
            novasenha = false;
        }
        if (perm == true) {
            perm = 'SIM';
        } else {
            perm = 'NAO';
        }
        if (admin == true) {
            admin = 'SIM';
        } else {
            admin = 'NAO';
        }
        $.ajax({
            url: 'api',
            method: 'POST',
            data: { id: id, nome: nome, email: email, perm: perm, admin: admin, editaruser: true, senha: senha, novasenha: novasenha },
            dataType: 'json',
            success: function (json) {
                if (json.status == 'sucesso') {
                    successnotify('Usuário editado com sucesso!');
                    setTimeout(function () {
                        location.reload();
                    }, 1000);
                } else {
                    errornotify('Erro ao editar o usuário!');
                }
            },
            error: function (error) {
                console.error('Erro ao editar o usuário:', error);
            }
        });
    });
</script>
