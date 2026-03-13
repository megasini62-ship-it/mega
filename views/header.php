<?php
include_once './controlador/funcoes.php';
$perm = consultaPerm($_SESSION['id']);
deletarPagamentosPendentes();
$_SESSION['perm'] = $perm['perm'];
$_SESSION['admin'] = $perm['admin'];
if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
  echo "<script>window.location.href = 'login';</script>";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Gerenciador Atlas</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="https://a.imagem.app/oNsa4m.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/modern-theme.css" />
    <link rel="stylesheet" href="assets/css/animations.css" />
  </head>

  <body>
    <div class="modern-layout d-flex" style="min-height:100vh;">
      <!-- =================== SIDEBAR =================== -->
      <aside id="sidebar" class="layout-menu" style="width:260px;min-height:100vh;flex-shrink:0;transition:all 0.3s ease;">
        <div class="app-brand d-flex align-items-center justify-content-between px-3 py-3" style="border-bottom:1px solid rgba(255,255,255,0.06);">
          <a href="home">
            <img src="https://a.imagem.app/oNsySX.png" alt="Atlas Logo" style="max-width:160px;height:auto;filter:drop-shadow(0 2px 8px rgba(102,126,234,0.4));" />
          </a>
          <button class="d-xl-none btn btn-sm p-1" id="closeSidebar" style="background:rgba(255,255,255,0.1);border:none;color:rgba(255,255,255,0.7);border-radius:6px;">
            <i class="fa-solid fa-xmark fa-lg"></i>
          </button>
        </div>

        <nav class="px-2 py-3">
          <ul class="list-unstyled mb-0">
            <!-- Inicio -->
            <li class="mb-1">
              <a href="home" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none" style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-house fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Tokens</span>
              </a>
            </li>

            <!-- Resgatar Token -->
            <li class="mb-1">
              <a href="#" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none"
                 data-bs-toggle="modal" data-bs-target="#modalCenter"
                 style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#11998e,#38ef7d);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-ticket fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Resgatar Token</span>
              </a>
            </li>

            <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
            <!-- Pagamentos -->
            <li class="mb-1">
              <a href="pagamentos" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none" style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f093fb,#f5576c);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-credit-card fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Pagamentos</span>
              </a>
            </li>
            <?php } ?>

            <?php if ($_SESSION['perm'] == 'SIM') { ?>
            <li class="mt-3 mb-1 px-3">
              <small style="color:rgba(255,255,255,0.3);font-size:0.68rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;">Super Admin</small>
            </li>

            <!-- Gerar Token (admin) -->
            <li class="mb-1">
              <a href="#" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none"
                 data-bs-toggle="modal" data-bs-target="#modaladmincriartoken"
                 style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#4facfe,#00f2fe);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-circle-plus fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Gerar Token</span>
              </a>
            </li>

            <!-- Usuários -->
            <li class="mb-1">
              <a href="usuarios" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none" style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#fa709a,#fee140);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-users fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Usuários</span>
              </a>
            </li>
            <?php } ?>

            <li class="mt-3 mb-1 px-3">
              <small style="color:rgba(255,255,255,0.3);font-size:0.68rem;font-weight:600;letter-spacing:1px;text-transform:uppercase;">Sua Conta</small>
            </li>

            <!-- Conta -->
            <li class="mb-1">
              <a href="account" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none" style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#30cfd0,#330867);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-circle-user fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Conta</span>
              </a>
            </li>

            <!-- Sair -->
            <li class="mb-1">
              <a href="logout" class="menu-link d-flex align-items-center gap-3 px-3 py-2 rounded text-decoration-none" style="color:rgba(255,255,255,0.75);transition:all 0.25s;">
                <span style="width:32px;height:32px;border-radius:8px;background:linear-gradient(135deg,#f5576c,#f093fb);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                  <i class="fa-solid fa-right-from-bracket fa-sm text-white"></i>
                </span>
                <span style="font-size:0.875rem;font-weight:500;">Sair</span>
              </a>
            </li>
          </ul>
        </nav>
      </aside>
      <!-- / Sidebar -->

      <!-- Sidebar overlay for mobile -->
      <div id="sidebarOverlay" class="d-none d-xl-none" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:1040;" onclick="closeSidebar()"></div>

      <!-- =================== MAIN CONTENT =================== -->
      <div class="flex-grow-1 d-flex flex-column" style="min-width:0;">
        <!-- Navbar -->
        <nav class="layout-navbar navbar navbar-expand-xl align-items-center px-4" style="min-height:64px;position:sticky;top:0;z-index:100;">
          <div class="d-flex align-items-center gap-3 w-100">
            <!-- Mobile menu toggle -->
            <button class="d-xl-none btn btn-sm p-2" id="openSidebar" onclick="openSidebar()" style="background:rgba(102,126,234,0.1);border:1px solid rgba(102,126,234,0.2);color:#667eea;border-radius:8px;">
              <i class="fa-solid fa-bars"></i>
            </button>

            <div class="ms-auto d-flex align-items-center gap-3">
              <!-- User dropdown -->
              <div class="dropdown">
                <a href="#" class="d-flex align-items-center gap-2 text-decoration-none" data-bs-toggle="dropdown" aria-expanded="false">
                  <div style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#667eea,#764ba2);display:flex;align-items:center;justify-content:center;border:2px solid rgba(102,126,234,0.3);">
                    <i class="fa-solid fa-user fa-sm text-white"></i>
                  </div>
                  <div class="d-none d-sm-block">
                    <div style="font-size:0.85rem;font-weight:600;color:#2d3748;line-height:1.2;"><?php echo htmlspecialchars($_SESSION['nome']); ?></div>
                    <div style="font-size:0.72rem;">
                      <?php if ($_SESSION['perm'] == 'SIM') { ?>
                        <span class="role-badge-nav role-admin-badge">Administrador</span>
                      <?php } elseif ($_SESSION['admin'] == 'SIM') { ?>
                        <span class="role-badge-nav role-revendedor-badge">Revendedor</span>
                      <?php } else { ?>
                        <span class="role-badge-nav role-usuario-badge">Usuário</span>
                      <?php } ?>
                    </div>
                  </div>
                  <i class="fa-solid fa-chevron-down fa-xs" style="color:#718096;"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2" style="min-width:200px;">
                  <li>
                    <div class="px-3 py-2 border-bottom">
                      <div style="font-size:0.85rem;font-weight:600;color:#2d3748;"><?php echo htmlspecialchars($_SESSION['nome']); ?></div>
                      <div style="font-size:0.78rem;color:#718096;"><?php echo htmlspecialchars($_SESSION['email']); ?></div>
                    </div>
                  </li>
                  <li><a class="dropdown-item d-flex align-items-center gap-2 mt-1" href="account">
                    <i class="fa-solid fa-gear" style="color:#667eea;width:16px;"></i> Conta
                  </a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item d-flex align-items-center gap-2" href="logout" style="color:#f5576c;">
                    <i class="fa-solid fa-right-from-bracket" style="width:16px;"></i> Sair
                  </a></li>
                </ul>
              </div>
            </div>
          </div>
        </nav>
        <!-- / Navbar -->

        <!-- Content area -->
        <div class="flex-grow-1 p-4" style="background:var(--bg-light, #f7f9fc);">
          <div class="row">

