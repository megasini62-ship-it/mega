<?php 
include './controlador/funcoes.php';
if (isset($_SESSION['email']) && isset($_SESSION['senha'])) {
	if (login($_SESSION['email'], $_SESSION['senha'])) {
		header('Location: home');
	}else{
		session_destroy();
	}
}
?>
<!DOCTYPE html>

<html
  lang="pt-br"
  class="customizer-hide dark-style"
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
    <link rel="stylesheet" href="../../assets/vendor/libs/formvalidation/dist/css/formValidation.min.css" />
    <link rel="stylesheet" href="../../assets/vendor/css/pages/page-auth.css" />
    <script src="../../assets/vendor/js/helpers.js"></script>
    <script src="../../assets/vendor/js/template-customizer.js"></script>
    <script src="../../assets/js/config.js"></script>
    <script src="../../assets/js/chm.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" type="text/css" href="../assets/css/notify.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
  <body>
    <div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <div class="card">
            <div class="card-body">
              <div class="app-brand justify-content-center">
                  <img src="https://a.imagem.app/oNsySX.png" alt="logo" width="200px" height="60px" />
              </div>
              <h4 class="mb-2">Bem vindo ao Gerenciador Atlas</h4>
              <p class="mb-4">Aqui voce pode gerenciar seus dominios</p>

              <form id="formAuthentication" class="mb-3" action="login" method="POST">
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input
                    type="text"
                    class="form-control"
                    id="email"
                    name="email"
                    value = "<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"
                    placeholder="Informe seu email"
                    autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                  <div class="d-flex justify-content-between">
                    <label class="form-label" for="password">Senha</label>
                  </div>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      value = "<?php echo isset($_POST['senha']) ? $_POST['senha'] : '' ?>"
                      class="form-control"
                      name="senha"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <button class="btn btn-primary d-grid w-100" type="submit">Entrar</button>
                </div>
              </form>

              <div class="divider my-4">
                <div class="divider-text">ou</div>
              </div>
              <p class="text-center">
                <span>Ainda não tem uma conta?</span>
                <a href="register">
                  <span>&nbsp;Cadastre-se agora</span>
                </a>
              </p>
            </div>
          </div>
        </div>
        <?php
        if (isset($_POST['email']) && isset($_POST['senha'])) {
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $loginData = login($email, $senha);
            if ($loginData !== false) {
                $_SESSION['id'] = $loginData['id'];
                $_SESSION['nome'] = $loginData['nome'];
                $_SESSION['email'] = $loginData['email'];
                $_SESSION['admin'] = $loginData['admin'];
                $_SESSION['senha'] = $senha;
                $_SESSION['perm'] = $loginData['perm'];
                echo "<script>successnotify('Sucesso! Você será redirecionado em 1 segundo!'); 
                setTimeout(function(){ window.location.href = 'home'; }, 1000);</script>";
            } else {
                echo "<script>errornotify('Erro! Usuário ou senha incorretos!');</script>";
            }
        }
        
        ?>
      </div>
    </div> 
    <script src="../../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../../assets/vendor/libs/popper/popper.js"></script>
    <script src="../../assets/vendor/js/bootstrap.js"></script>
    <script src="../../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../../assets/vendor/libs/hammer/hammer.js"></script>
    <script src="../../assets/vendor/libs/i18n/i18n.js"></script>
    <script src="../../assets/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="../../assets/vendor/js/menu.js"></script>
    <script src="../../assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js"></script>
    <script src="../../assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js"></script>
    <script src="../../assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js"></script>
    <script src="../../assets/js/main.js"></script>
    
    <script src="../../assets/js/pages-auth.js"></script>
  </body>
</html>
