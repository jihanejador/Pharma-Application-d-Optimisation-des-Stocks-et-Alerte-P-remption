<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/autoloader.php';
$pdoFactory = require_once __DIR__ . '/../config/database.php';
$pdo = $pdoFactory();

// Appel avec le nouveau namespace racine PharmaApp
use PharmaApp\Repository\UserRepository;
use PharmaApp\Controller\AuthController;

$userRepository = new UserRepository($pdo);
$authController = new AuthController($userRepository);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestUri === '/' || $requestUri === '/index.php') {
    $authController->login();
} elseif ($requestUri === '/register') {
    $authController->register();
} elseif ($requestUri === '/logout') {
    $authController->logout();
} elseif ($requestUri === '/dashboard') {
    if (!isset($_SESSION['user_id'])) { header('Location: /'); exit; }
    echo "<h1>Bienvenue dans le Dashboard. Connexion et Inscription validées en MVC Strict et FETCH_OBJ !</h1><a href='/logout'>Déconnexion</a>";
} else {
    http_response_code(404);
    echo "<h1 style='text-align:center;margin-top:10%;font-family:sans-serif;'>Erreur 404 : Page introuvable !</h1>";
}