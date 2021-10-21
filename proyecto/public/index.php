<?php

//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>';

require_once __DIR__ . "/../vendor/autoload.php";

session_start();

$basePath = realpath(__DIR__ . "/..");

//echo "La ruta base es: " . $basePath . "<br>";

//require_once __DIR__ . "/../app/Router.php";
//require_once __DIR__ . "/../app/Controllers/InicioController.php";

use App\Controllers\AuthController;
use App\Controllers\InicioController;
use App\Controllers\ProductoController;

$router = new App\Router($basePath);

/*
 |--------------------------------------------------------------------------
 | Generales
 |--------------------------------------------------------------------------
 */
$router->get('/', [InicioController::class, 'index']);

/*
 |--------------------------------------------------------------------------
 | Autenticación
 |--------------------------------------------------------------------------
 */
$router->get('/registro', [AuthController::class, 'registroForm']);
$router->post('/registro', [AuthController::class, 'registro']);
$router->get('/iniciar-sesion', [AuthController::class, 'loginForm']);
$router->post('/iniciar-sesion', [AuthController::class, 'login']);
$router->post('/cerrar-sesion', [AuthController::class, 'logout']);

/*
 |--------------------------------------------------------------------------
 | Productos
 |--------------------------------------------------------------------------
 */
$router->get('/productos', [ProductoController::class, 'index']);
$router->get('/productos/nuevo', [ProductoController::class, 'nuevoForm']);
//$router->get('/contacto', 'function_contacto');
//$router->get('/admin/users/create', 'function_crear_usuario');

$router->run();

//echo '<pre>';
//var_dump($router);
//echo '</pre>';
