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
<div class="container-xxl">
      <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
          <div class="card">
            <div class="card-body">
            <div class="app-brand justify-content-center">
                  <img src="https://a.imagem.app/oNsySX.png" alt="logo" width="200px" height="60px" />
              </div>
              <h4 class="mb-2">Bem vindo ao Gerenciador Atlas</h4>
              <p class="mb-4">Aqui voce criar sua conta</p>

              <form id="formAuthentication" class="mb-3" action="register" method="POST">
                <div class="mb-3">
                  <label for="username" class="form-label">Nome</label>
                  <input
                    type="text"
                    class="form-control"
                    id="nome"
                    name="nome"
                    placeholder="Insira seu nome"
                    autofocus />
                </div>
                <div class="mb-3">
                  <label for="email" class="form-label">Email</label>
                  <input type="text" class="form-control" id="email" name="email" placeholder="Insira seu email" />
                </div>
                <div class="mb-3 form-password-toggle">
                  <label class="form-label" for="password">Senha</label>
                  <div class="input-group input-group-merge">
                    <input
                      type="password"
                      id="password"
                      class="form-control"
                      name="senha"
                      placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                      aria-describedby="password" />
                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                  </div>
                </div>

                <div class="mb-3">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
                    <label class="form-check-label" for="terms-conditions">
                      Eu aceito os
                      <a href="termos">termos e condiões</a>
                    </label>
                  </div>
                </div>
                <button class="btn btn-primary d-grid w-100">Criar conta</button>
              </form>

              <div class="divider my-4">
                <div class="divider-text">ou</div>
              </div>
              <p class="text-center">
                <span>Já tem uma conta?</span>
                <a href="login">
                  <span>&nbsp;Entre aqui</span>
                </a>
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
        <?php
        if (isset($_POST['email']) && isset($_POST['senha']) && isset($_POST['nome'])) {
            include_once './controlador/funcoes.php';
        
            $email = $_POST['email'];
            $senha = $_POST['senha'];
            $senha = md5($senha);
            $nome = $_POST['nome'];
        
            $register = cadastrar($nome, $email, $senha);
            echo "<script>console.log('$register');</script>";
            if ($register === 'sucesso') {
                echo "<script>successnotify('Sucesso! Você criou sua conta!');
                setTimeout(function(){ window.location.href = 'login'; }, 1000);</script>";
            } elseif ($register === 'email_existente') {
                echo "<script>errornotify('Email já cadastrado!');</script>";
            } elseif ($register === 'erro_cadastrar') {
                echo "<script>errornotify('Erro ao cadastrar! Tente novamente mais tarde.');</script>";
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
