<?php

//echo '<pre>';
//var_dump($_SERVER);
//echo '</pre>';

require_once __DIR__ . "/../vendor/autoload.php";

//session_start();
\App\Session\Session::start();

$basePath = realpath(__DIR__ . "/..");

\App\Env\Loader::cargar($basePath);

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
$router->get('/productos/nuevo', [ProductoController::class, 'crearForm']);
$router->post('/productos/nuevo', [ProductoController::class, 'crear']);
$router->post('/productos/{id}/eliminar', [ProductoController::class, 'eliminar']);
// Entre {} vamos a definir los "parámetros" de las rutas.
// Un parámetro es un valor que es dinámico, y que queremos poder obtener luego para manipularlo.
// Por ejemplo, si tenemos una ruta:
//  /productos/{id}
// Debería matchear la ruta:
//  /productos/2
// Y desde el código, deberíamos poder obtener de algún lado ese "id" con valor 2.
$router->get('/productos/{id}', [ProductoController::class, 'ver']);
//$router->get('/contacto', 'function_contacto');
//$router->get('/admin/users/create', 'function_crear_usuario');

$router->run();

//echo '<pre>';
//var_dump($router);
//echo '</pre>';
