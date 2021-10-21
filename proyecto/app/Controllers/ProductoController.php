<?php

namespace App\Controllers;


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

    public static function nuevoForm()
    {
        $view = new View();
        $view->render('productos/form-alta');
    }

    public static function ver()
    {
        $parameters = Router::getRouteParameters();
        $producto = new Producto();
        $producto = $producto->findByPk($parameters['id']);
        $view = new View();
        $view->render('productos/ver', ['producto' => $producto]);
    }
}
