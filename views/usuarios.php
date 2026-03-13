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
<div class="content-wrapper">

            <div class="container-xxl flex-grow-1 container-p-y">
              <h4 class="py-3 breadcrumb-wrapper mb-4">
                <span class="text-muted fw-light">Atlas /</span> Usuários
              </h4>
<div class="card">
                <h5 class="card-header"><?php echo $texto; ?></h5>
                <!-- input pesquisa -->
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar" aria-label="Pesquisar" aria-describedby="button-addon2" id='pesquisauser'>
                        <button class="btn btn-outline-primary" type="button" id="button-searchuser" >Pesquisar</button>
                    </div>
                    <div class="card-datatable table-responsive">
                  <table class="dt-advanced-search table table-bordered">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Email</th>
                <th>Permissão</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tokensPaginados as $token) { ?>
                <tr>
                    <td><?php echo $token['name']; ?></td>
                    <td><?php echo $token['email']; ?></td>
                    <td><?php if ($token['perm'] == 'SIM') {
                            echo '<span class="text-nowrap"><a href="app-user-list.html"><span class="badge  bg-label-primary m-1">Administrator</span></a></span>';
                        } elseif ($token['admin'] == 'SIM') {
                            echo '<span class="text-nowrap"><a href="app-user-list.html"><span class="badge  bg-label-info m-1">Revendedor</span></a></span>';
                        } else{

                            echo '<a href="app-user-list.html"><span class="badge  bg-label-success m-1">Usuario</span></a>';
                        }
                        ?></td>
                    <td>
    <div class="btn-group" role="group">
        <button class="btn btn-sm btn-primary" onclick="abrirmodaleditaruser(<?php echo $token['id']; ?>)">
            Editar
        </button>
    </div>
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
                //marca o checkbox
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





<div class="d-flex justify-content-center">
    <nav aria-label="Page navigation">
    <ul class="pagination">
        <li class="page-item <?php echo ($paginaAtual == 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=1" aria-label="First"><i class="tf-icon bx bx-chevrons-left"></i></a>
        </li>
        <li class="page-item <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo ($paginaAtual > 1) ? ($paginaAtual - 1) : 1; ?>" aria-label="Previous"><i class="tf-icon bx bx-chevron-left"></i></a>
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
            <a class="page-link" href="?page=<?php echo ($paginaAtual < $totalPaginas) ? ($paginaAtual + 1) : $totalPaginas; ?>" aria-label="Next"><i class="tf-icon bx bx-chevron-right"></i></a>
        </li>
        <li class="page-item <?php echo ($paginaAtual == $totalPaginas) ? 'disabled' : ''; ?>">
            <a class="page-link" href="?page=<?php echo $totalPaginas; ?>" aria-label="Last"><i class="tf-icon bx bx-chevrons-right"></i></a>
        </li>
    </ul>
</nav>
</div>
</div>
</div>

              <hr class="my-5" />
    </div>
    <?php if ($_SESSION['perm'] == 'SIM') { ?>
                    <div class="modal fade" id="modalEditarUser" tabindex="-1" aria-hidden="true">
                          <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="modalCenterTitle">Editar Usuário</h5>
                                <button
                                  type="button"
                                  class="btn-close"
                                  data-bs-dismiss="modal"
                                  aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
                              <div class="card-body">
                              <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">ID</label>
                                    <input
                                      type="text"
                                      id="idusuarioeditar"
                                      class="form-control"
                                      placeholder="Id Usuario" disabled />
                                  </div>
                                </div>
                              <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Nome</label>
                                    <input
                                      type="text"
                                      id="nomeusuarioeditar"
                                      class="form-control"
                                      placeholder="Nome Usuario" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Email</label>
                                    <input
                                      type="text"
                                      id="emailusuarioeditar"
                                      class="form-control"
                                      placeholder="Email Usuario" />
                                  </div>
                                </div>
                                <div class="row">
                                  <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Senha Usuário</label>
                                    <input
                                      type="text"
                                      id="senhausuarioeditar"
                                      class="form-control"
                                      placeholder="Senha Usuário" />
                                  </div>
                                </div>
                                <div class="col-12">
                          <h5>Permissão do Usuário</h5>
                          <!-- Permission table -->
                                    <div class="d-flex">
                                      <div class="form-check me-3 me-lg-2">
                                        <input class="form-check-input" type="checkbox" id="userrevendedor" />
                                        <label class="form-check-label" for="userManagementWrite"> Revendedor </label>
                                      </div>
                                      <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="useradministrador" />
                                        <label class="form-check-label" for="userManagementCreate"> Administrador </label>
                                      </div>
                                    </div>
                                  </td>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                  Fechar
                                </button>
                                <button type="button" id="editarusuario" class="btn btn-primary">Editar</button>
                              </div>
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
                    for (var i = 0; i < tokens.length; i++) {
                        var badgeHTML = "";
                        if (tokens[i]['perm'] == 'SIM') {
                            badgeHTML = '<span class="badge bg-label-primary m-1">Administrator</span>';
                        } else if (tokens[i]['admin'] == 'SIM') {
                            badgeHTML = '<span class="badge bg-label-info m-1">Revendedor</span>';
                        } else {
                            badgeHTML = '<span class="badge bg-label-success m-1">Usuario</span>';
                        }
                        var newRow = "<tr>" +
                            "<td>" + tokens[i]['name'] + "</td>" +
                            "<td>" + tokens[i]['email'] + "</td>" +
                            "<td>" + badgeHTML + "</td>" +
                            "<td>" +
                            "<div class='btn-group' role='group'>" +
                            "<button class='btn btn-sm btn-primary' onclick='abrirmodaleditaruser(" + tokens[i]['id'] + ")'>" +
                            "Editar" +
                            "</button>" +
                            "</div>" +
                            "</td>" +
                            "</tr>";
                        $('.table tbody').append(newRow);
                    }
                }
            });
                //ao clicar em editarusuario
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
