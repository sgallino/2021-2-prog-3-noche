<?php

namespace App\Controllers;


use App\Models\Producto;
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
}
