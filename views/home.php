<?php
session_start();
include 'header.php'; 
include 'modal.php';

if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') {
    $tokens = pesquisarTokens();
    $texto = 'Todos os domínios';
    
}else{
    $tokens = pesquisarTokenPorId($_SESSION['id']);
    if ($tokens == null) {
        $tokens = [];
    }
    $texto = 'Meus domínios';
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
                <span class="text-muted fw-light">Atlas /</span> Tokens
              </h4>
<div class="card">
                <h5 class="card-header"><?php echo $texto; ?></h5>
                <!-- input pesquisa -->
                <div class="card-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="pesquisa" placeholder="Pesquisar" aria-label="Pesquisar" aria-describedby="button-addon2" id='search'>
                        <button class="btn btn-outline-primary" type="button" id="button-search" >Pesquisar</button>
                    </div>
                    <div class="card-datatable table-responsive">
                  <table class="dt-advanced-search table table-bordered">
        <thead>
            <tr>
                <th>Token</th>
                <th>Dominio</th>
                <th>Vencimento</th>
                <th>Editar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($tokensPaginados as $token) { ?>
                <tr>
                    <td><?php if ($_SESSION['perm'] == 'SIM') {
                            echo $token['token'];
                        } elseif ($_SESSION['admin'] == 'SIM') {
                            echo str_repeat('*', strlen($token['token']));
                        } else {
                            echo $token['token'];
                        } ?></td>
                    <td><?php echo $token['dominio']; ?></td>
                    <td><?php echo $token['vencimento']; ?></td>
                    <td>
    <div class="btn-group" role="group">
        <button class="btn btn-sm btn-primary" onclick="abrirModalEditarDominio(<?php echo $token['id']; ?>)">
            Editar
        </button>
        <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
        <button class="btn btn-sm btn-success" onclick="abrirModalrenovarDominio(<?php echo $token['id']; ?>, 'outroCampo')">
            Renovar
        </button>
        <?php } ?>
    </div>
</td>

                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>




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

<?php include 'footer.php'; ?>
