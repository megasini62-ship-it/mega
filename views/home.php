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
<div class="col-12">
  <div class="d-flex align-items-center justify-content-between mb-4">
    <div>
      <h4 class="page-title mb-1">
        <i class="fa-solid fa-key me-2" style="color:#667eea;"></i><?php echo $texto; ?>
      </h4>
      <small class="text-muted">Atlas / Tokens</small>
    </div>
    <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
    <button class="btn btn-gradient-primary d-flex align-items-center gap-2"
            data-bs-toggle="modal" data-bs-target="#modalgerartoken">
      <i class="fa-solid fa-plus"></i> Novo Token
    </button>
    <?php } ?>
  </div>

  <div class="card">
    <div class="card-body">
      <!-- Search -->
      <div class="d-flex gap-2 mb-4">
        <div class="search-wrapper flex-grow-1">
          <i class="fa-solid fa-magnifying-glass search-icon"></i>
          <input type="text" class="form-control" name="pesquisa"
                 placeholder="Pesquisar por domínio ou token..."
                 aria-label="Pesquisar" id="search" style="padding-left:40px;" />
        </div>
        <button class="btn btn-gradient-primary d-flex align-items-center gap-2"
                type="button" id="button-search">
          <i class="fa-solid fa-search"></i> Buscar
        </button>
      </div>

      <!-- Table -->
      <div class="table-responsive">
        <table class="table dt-advanced-search">
          <thead>
            <tr>
              <th><i class="fa-solid fa-key me-1" style="color:#667eea;"></i> Token</th>
              <th><i class="fa-solid fa-globe me-1" style="color:#11998e;"></i> Domínio</th>
              <th><i class="fa-solid fa-calendar me-1" style="color:#f093fb;"></i> Vencimento</th>
              <th><i class="fa-solid fa-gear me-1" style="color:#4facfe;"></i> Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($tokensPaginados as $token) { ?>
              <tr>
                <td>
                  <code style="background:rgba(102,126,234,0.08);padding:3px 8px;border-radius:6px;font-size:0.8rem;">
                    <?php if ($_SESSION['perm'] == 'SIM') {
                        echo htmlspecialchars($token['token']);
                    } elseif ($_SESSION['admin'] == 'SIM') {
                        echo str_repeat('•', min(strlen($token['token']), 20));
                    } else {
                        echo htmlspecialchars($token['token']);
                    } ?>
                  </code>
                </td>
                <td>
                  <span class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-dot fa-xs" style="color:#38ef7d;"></i>
                    <?php echo htmlspecialchars($token['dominio']); ?>
                  </span>
                </td>
                <td>
                  <span style="font-weight:500;color:<?php echo ($token['vencimento'] == 'Nunca') ? '#11998e' : '#2d3748'; ?>;">
                    <?php echo htmlspecialchars($token['vencimento']); ?>
                  </span>
                </td>
                <td>
                  <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-gradient-primary d-flex align-items-center gap-1"
                            onclick="abrirModalEditarDominio(<?php echo $token['id']; ?>)">
                      <i class="fa-solid fa-pen-to-square fa-xs"></i> Editar
                    </button>
                    <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
                    <button class="btn btn-sm btn-gradient-success d-flex align-items-center gap-1"
                            onclick="abrirModalrenovarDominio(<?php echo $token['id']; ?>, 'outroCampo')">
                      <i class="fa-solid fa-rotate fa-xs"></i> Renovar
                    </button>
                    <?php } ?>
                  </div>
                </td>
              </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="d-flex justify-content-center mt-4">
        <nav aria-label="Page navigation">
          <ul class="pagination">
            <li class="page-item <?php echo ($paginaAtual == 1) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=1" aria-label="First">
                <i class="fa-solid fa-angles-left fa-xs"></i>
              </a>
            </li>
            <li class="page-item <?php echo ($paginaAtual <= 1) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=<?php echo ($paginaAtual > 1) ? ($paginaAtual - 1) : 1; ?>" aria-label="Previous">
                <i class="fa-solid fa-angle-left fa-xs"></i>
              </a>
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
              <a class="page-link" href="?page=<?php echo ($paginaAtual < $totalPaginas) ? ($paginaAtual + 1) : $totalPaginas; ?>" aria-label="Next">
                <i class="fa-solid fa-angle-right fa-xs"></i>
              </a>
            </li>
            <li class="page-item <?php echo ($paginaAtual == $totalPaginas) ? 'disabled' : ''; ?>">
              <a class="page-link" href="?page=<?php echo $totalPaginas; ?>" aria-label="Last">
                <i class="fa-solid fa-angles-right fa-xs"></i>
              </a>
            </li>
          </ul>
        </nav>
      </div>

    </div>
  </div>
</div>

<?php include 'footer.php'; ?>
