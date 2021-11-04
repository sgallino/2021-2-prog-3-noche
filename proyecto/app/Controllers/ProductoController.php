<?php

namespace App\Controllers;


use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Router;
use App\Validation\Validator;
use App\View;

class ProductoController
{
    public static function index()
    {
        $producto = new Producto();
        $productos = $producto->all();
        $view = new View();
        $view->render('productos/index', ['productos' => $productos]);
    }

    public static function ver()
    {
        $parameters = Router::getRouteParameters();
        $producto = new Producto();
        $producto = $producto->findByPk($parameters['id']);
        $view = new View();
        $view->render('productos/ver', ['producto' => $producto]);
    }

    public static function crearForm()
    {
        // Levantamos los mensajes de error y los datos viejos, si es que existen.
        if(isset($_SESSION['old_data'])) {
            $oldData = $_SESSION['old_data'];
            unset($_SESSION['old_data']);
        } else {
            $oldData = [];
        }
        if(isset($_SESSION['errors'])) {
            $errors = $_SESSION['errors'];
            unset($_SESSION['errors']);
        } else {
            $errors = [];
        }
//        $marca = new Marca();
//        $marcas = $marca->all();
        $marcas = (new Marca())->all();
        $categorias = (new Categoria())->all();
        $view = new View();
        $view->render('productos/form-alta', ['marcas' => $marcas, 'categorias' => $categorias, 'errors' => $errors, 'oldData' => $oldData]);
    }

    public static function crear()
    {
        // El Validator recibe 2 parámetros en su constructor:
        // 1. array - La data a validar.
        // 2. array - Las "reglas" de validación.
        // "Regla" de validación es un término típico en este tipo de implementaciones que significa
        // una validación específica sobre un valor. Por ejemplo, puedo tener una regla que sea checkear
        // que el campo se obligatorio, otra que verifique la cantidad máxima de caracteres, otra que
        // verifica la cantidad mínima de caracteres, otra para formato de email, etc.
        // En el array de "regla", las "keys" deben coincidir con keys del array de datos, y los valores
        // van a ser arrays que contenga la lista de reglas a aplicar sobre el valor de esa key.
        // Las reglas, por supuesto, deben estar pre-definidas por la clase de Validación.
        $validator = new Validator($_POST, [
            'nombre'        => ['required', 'min:3'],
            'id_marca'      => ['required', 'numeric'],
            'id_categoria'  => ['required', 'numeric'],
            'precio'        => ['required', 'numeric'],
        ]);

        if($validator->fails()) {
            $_SESSION['old_data'] = $_POST;
            $_SESSION['errors'] = $validator->getErrors();
            // Redireccionamos al form de nuevo...
            Router::redirect('productos/nuevo');
        }

        $data = [
            'nombre'        => $_POST['nombre'],
            'id_marca'      => $_POST['id_marca'],
            'id_categoria'  => $_POST['id_categoria'],
            'precio'        => $_POST['precio'],
            'descripcion'   => $_POST['descripcion'],
            'imagen'        => '',
        ];

//        $producto = new Producto();
//        $producto->create($data);
        // Idem a lo de arriba, pero sin guardar el objeto en una variable.
        try {
            (new Producto())->create($data);

            $_SESSION['message_success'] = "El producto fue creado con éxito.";
            Router::redirect('productos');
//        header("Location: " . Router::urlTo('productos'));
//        exit;
        } catch(\PDOException $e) {
            // Error al grabar el producto.
            $_SESSION['message_error'] = "Ocurrió un error inesperado al tratar de grabar el producto. Por favor, probá de nuevo más tarde.";
            $_SESSION['old_data'] = $_POST;
            Router::redirect('productos/nuevo');
        }
    }
}
