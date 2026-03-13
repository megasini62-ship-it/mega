<?php
$url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); // Obtém a parte da URL sem os parâmetros GET
$routes = [
    '/' => 'views/login.php',
    '/login' => 'views/login.php',
    '/home' => 'views/home.php',
    '/register' => 'views/register.php',
    '/api' => 'views/api.php',
    '/pagamentos' => 'views/pagamentos.php',
    '/usuarios' => 'views/usuarios.php',
    '/account' => 'views/account.php',
    '/logout' => 'views/logout.php',
    '/checkpag' => 'views/checkpag.php',
];

if (array_key_exists($url, $routes)) {
    include $routes[$url];
} else {
    include 'views/404.php';
}
