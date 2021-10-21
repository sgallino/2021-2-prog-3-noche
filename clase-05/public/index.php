<?php

//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>';

require_once __DIR__ . "/../vendor/autoload.php";

//require_once __DIR__ . "/../app/Router.php";
//require_once __DIR__ . "/../app/Controllers/InicioController.php";

use App\Controllers\InicioController;

$router = new App\Router();

$router->get('/', [InicioController::class, 'index']);
$router->get('/contacto', 'function_contacto');
$router->get('/admin/users/create', 'function_crear_usuario');

$router->run();

//echo '<pre>';
//var_dump($router);
//echo '</pre>';