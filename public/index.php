<?php
declare(strict_types=1);

session_start();

require_once __DIR__ . '/../config/autoloader.php';
$pdoFactory = require_once __DIR__ . '/../config/database.php';
$pdo = $pdoFactory();

use PharmaApp\Repository\UserRepository;
use PharmaApp\Repository\StockRepository;
use PharmaApp\Controller\AuthController;
use PharmaApp\Controller\StockController;

$userRepository = new UserRepository($pdo);
$stockRepository = new StockRepository($pdo);

$authController = new AuthController($userRepository);
$stockController = new StockController($stockRepository);

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if ($requestUri === '/' || $requestUri === '/index.php') {
    $authController->login();
} elseif ($requestUri === '/register') {
    $authController->register();
} elseif ($requestUri === '/logout') {
    $authController->logout();
} elseif ($requestUri === '/dashboard') {
    $stockController->dashboard();
} elseif ($requestUri === '/dashboard/ajouter') {
    $stockController->ajouterLot(); 
} elseif ($requestUri === '/dashboard/vendre') {
    $stockController->vendreMedicament(); 
} elseif ($requestUri === '/dashboard/perimer') {
    $stockController->retirerDuStock(); 
} else {
    http_response_code(404);
    echo "<h1 style='text-align:center;margin-top:10%;font-family:sans-serif;'>Erreur 404 : Page introuvable !</h1>";
}