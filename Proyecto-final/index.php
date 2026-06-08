<?php
/* Este fragmento de código PHP es una implementación de un enrutador (router) para manejar
diferentes rutas dentro de una aplicación web.
A continuación, se muestra un desglose de lo que hace el código: */
require_once __DIR__ . '/config/Autoload.php';

use Controllers\AuthController;
use Controllers\ProductoController;
use Controllers\PublicController;

$route = $_GET['route'] ?? 'catalogo';

$authController = new AuthController();
$productoController = new ProductoController();
$publicController = new PublicController();

$method = $_SERVER['REQUEST_METHOD'];

if (preg_match('#^productos/edit/(\d+)$#', $route, $m)) {
    $_GET['id'] = $m[1];
    $productoController->edit();
    exit;
}

if (preg_match('#^productos/update/(\d+)$#', $route, $m)) {
    $_POST['id'] = $m[1];
    if ($method === 'POST') {
        $productoController->update();
    }
    exit;
}

switch ($route) {
    case 'login':
        $authController->showLogin();
        break;

    case 'auth/login':
        if ($method === 'POST') {
            $authController->login();
        }
        break;

    case 'logout':
        $authController->logout();
        break;

    case 'productos':
        $productoController->index();
        break;

    case 'productos/create':
        $productoController->create();
        break;

    case 'productos/store':
        if ($method === 'POST') {
            $productoController->store();
        }
        break;

    case 'productos/edit':
        $productoController->edit();
        break;

    case 'productos/update':
        if ($method === 'POST') {
            $productoController->update();
        }
        break;

    case 'productos/delete':
        if ($method === 'POST') {
            $productoController->delete();
        }
        break;

    case 'api/productos':
        $apiController = new \Controllers\ApiController();
        $apiController->productos();
        break;

    case 'catalogo':
        $publicController->catalogo();
        break;

    default:
        http_response_code(404);
        echo "<h1>404 - Página no encontrada</h1>";
        break;
}