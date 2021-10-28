<?php

namespace App\Controllers;


use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Router;
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
//        $marca = new Marca();
//        $marcas = $marca->all();
        $marcas = (new Marca())->all();
        $categorias = (new Categoria())->all();
        $view = new View();
        $view->render('productos/form-alta', ['marcas' => $marcas, 'categorias' => $categorias]);
    }

    public static function crear()
    {
        // TODO: Validar...

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
