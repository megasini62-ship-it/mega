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
<html
  lang="pt-br"
  class="light-style layout-navbar-fixed dark-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../../assets/"
  data-template="vertical-menu-template">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Gerenciador Atlas</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="https://a.imagem.app/oNsa4m.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
      rel="stylesheet" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/boxicons.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/fontawesome.css" />
    <link rel="stylesheet" href="../../assets/vendor/fonts/flag-icons.css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../../assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../../assets/css/demo.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/apex-charts/apex-charts.css" />
    <script src="../../assets/vendor/js/helpers.js"></script>
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <script src="../../assets/js/config.js"></script>
    <script src="../../assets/js/chm.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="../assets/css/notify.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="../../assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="../../assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    
  </head>

  <body>
        <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
          <div class="app-brand demo">
          <img href="home" src="https://a.imagem.app/oNsySX.png" alt="logo" width="200px" height="40px" />
            </a>
          </div>

          <div class="menu-divider mt-0"></div>

          <div class="menu-inner-shadow"></div>

          <ul class="menu-inner py-1">
            <!-- Dashboards -->
            <li class="menu-item active open">
              <a href="javascript:void(0);" class="menu-link menu-toggle">
                <i class="menu-icon tf-icons bx bx-home-circle"></i>
                <div>Inicio</div>
              </a>
              <ul class="menu-sub">
                <li class="menu-item active">
                  <a href="home" class="menu-link">
                    <div>Tokens</div>
                  </a>
                </li>
                <li class="menu-item">
                  <a data-bs-toggle="modal"
                          data-bs-target="#modalCenter" class="menu-link">
                    <div>Resgatar Token</div>
                  </a>
                </li>
              </ul>
            </li>
            <?php if ($_SESSION['admin'] == 'SIM' || $_SESSION['perm'] == 'SIM') { ?>
            <li class="menu-item">
              <a href="pagamentos" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-bank"></i>
                <div>Pagamentos</div>
              </a>
            </li>
            <?php } ?>
            <?php if ($_SESSION['perm'] == 'SIM') { ?>
              <li class="menu-header small text-uppercase"><span class="menu-header-text">Super Admin</span></li>
            <li class="menu-item">
              <a data-bs-toggle="modal"
                          data-bs-target="#modaladmincriartoken" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-add-to-queue"></i>
                <div>Gerar Token</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="usuarios" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user-detail"></i> 
                <div>Usuarios</div>
              </a>
            </li>
            <?php } ?>
            <li class="menu-header small text-uppercase"><span class="menu-header-text">Sua Conta</span></li>
            <li class="menu-item">
              <a href="account" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-user-account"></i>
                <div>Conta</div>
              </a>
            </li>
            <li class="menu-item">
              <a href="logout" class="menu-link">
                <i class="menu-icon tf-icons bx bxs-exit"></i>
                <div >Sair</div>
              </a>
            </li>
          </ul>
        </aside>
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->

          <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="container-fluid">
              <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                  <i class="bx bx-menu bx-sm"></i>
                </a>
              </div>

              <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                <ul class="navbar-nav flex-row align-items-center ms-auto">
                  <li class="nav-item navbar-dropdown dropdown-user dropdown">
                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                      <div class="avatar avatar-online">
                        <img src="../../assets/img/avatars/1.png" alt class="rounded-circle" />
                      </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                      <li>
                        <a class="dropdown-item" >
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar avatar-online">
                                <img src="../../assets/img/avatars/1.png" alt class="rounded-circle" />
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <span class="fw-semibold d-block lh-1"><?php echo $_SESSION['nome']; ?>
                              </span>
                              <small><?php if ($_SESSION['admin'] == 'SIM') {
                                      echo 'Revendedor';
                                    } elseif ($_SESSION['perm'] == 'SIM') {
                                      echo 'Administrador';
                                    } else {
                                      echo 'Usuario';
                                    } ?></small>
                            </div>
                          </div>
                        </a>
                      </li>
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="account">
                          <i class="bx bx-cog me-2"></i>
                          <span class="align-middle">Conta</span>
                        </a>
                      </li>
                      
                      <li>
                        <div class="dropdown-divider"></div>
                      </li>
                      <li>
                        <a class="dropdown-item" href="logout">
                          <i class="bx bx-power-off me-2"></i>
                          <span class="align-middle">Sair</span>
                        </a>
                      </li>
                    </ul>
                  </li>
                  <!--/ User -->
                </ul>
              </div>

              <!-- Search Small Screens -->
              <div class="navbar-search-wrapper search-input-wrapper d-none">
                <input
                  type="text"
                  class="form-control search-input container-fluid border-0"
                  placeholder="Search..."
                  aria-label="Search..." />
                <i class="bx bx-x bx-sm search-toggler cursor-pointer"></i>
              </div>
            </div>
          </nav>

          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">
            <!-- Content -->

            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row">
