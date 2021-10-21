<?php

namespace App\Controllers;

//require_once __DIR__ . "/../View.php";

class InicioController
{
    public static function index()
    {
        //echo 'Desde el controlador inicio y el metodo index';
        //require_once __DIR__ . "/../../views/inicio.php";
        $view = new \App\View();
        $view->render('inicio', ['titulo' => 'Pagina de inicio']);
    }
}
