<?php

class InicioController extends Controller
{
    public function index() {
        // require_once 'views/inicio.php';
        $view = new View();
        $view->render('inicio');
    }
}