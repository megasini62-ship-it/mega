<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title>Gerenciador Atlas – Cadastro</title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="https://a.imagem.app/oNsa4m.png" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <link rel="stylesheet" href="assets/css/modern-theme.css" />
    <link rel="stylesheet" href="assets/css/animations.css" />
  </head>
  <body>
    <div class="auth-page-wrapper">
      <!-- Floating particles -->
      <div class="particle"></div>
      <div class="particle"></div>
      <div class="particle"></div>
      <div class="particle"></div>
      <div class="particle"></div>

      <div class="auth-card">
        <div class="auth-logo">
          <img src="https://a.imagem.app/oNsySX.png" alt="Logo Gerenciador Atlas" />
        </div>

        <h1 class="auth-title">Criar sua conta ✨</h1>
        <p class="auth-subtitle">Preencha os dados abaixo para se cadastrar</p>

        <form id="formAuthentication" action="register" method="POST">
          <div class="mb-3">
            <label for="nome" class="auth-form-label">
              <i class="fa-solid fa-user" style="color:#60a5fa"></i> Nome
            </label>
            <input
              type="text"
              class="auth-input form-control"
              id="nome"
              name="nome"
              placeholder="Seu nome completo"
              autofocus />
          </div>

          <div class="mb-3">
            <label for="email" class="auth-form-label">
              <i class="fa-solid fa-envelope" style="color:#a78bfa"></i> Email
            </label>
            <input
              type="text"
              class="auth-input form-control"
              id="email"
              name="email"
              placeholder="seu@email.com" />
          </div>

          <div class="mb-3">
            <label class="auth-form-label" for="password">
              <i class="fa-solid fa-lock" style="color:#c084fc"></i> Senha
            </label>
            <div class="auth-input-group">
              <input
                type="password"
                id="password"
                class="auth-input form-control"
                name="senha"
                placeholder="••••••••••••"
                aria-describedby="password" />
              <button type="button" class="toggle-password" onclick="togglePassword('password', this)">
                <i class="fa-solid fa-eye-slash"></i>
              </button>
            </div>
          </div>

          <div class="mb-4">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="terms-conditions" name="terms" />
              <label class="auth-checkbox-label form-check-label" for="terms-conditions">
                Eu aceito os <a href="termos">termos e condições</a>
              </label>
            </div>
          </div>

          <button class="auth-btn btn" type="submit">
            <i class="fa-solid fa-user-plus me-2"></i> Criar conta
          </button>
        </form>

        <div class="auth-divider">ou</div>

        <p class="auth-link-text">
          Já tem uma conta?&nbsp;<a href="login">Entre aqui 🚀</a>
        </p>
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

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
      function successnotify(msg) {
        Toastify({ text: msg, duration: 3000, gravity: "top", position: "right",
          style: { background: "linear-gradient(135deg, #11998e, #38ef7d)", borderRadius: "10px" }
        }).showToast();
      }
      function errornotify(msg) {
        Toastify({ text: msg, duration: 3000, gravity: "top", position: "right",
          style: { background: "linear-gradient(135deg, #f093fb, #f5576c)", borderRadius: "10px" }
        }).showToast();
      }
      function togglePassword(inputId, btn) {
        var input = document.getElementById(inputId);
        var icon = btn.querySelector('i');
        if (input.type === 'password') {
          input.type = 'text';
          icon.className = 'fa-solid fa-eye';
        } else {
          input.type = 'password';
          icon.className = 'fa-solid fa-eye-slash';
        }
      }
    </script>
  </body>
</html>
