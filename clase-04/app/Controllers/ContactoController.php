<?php

class ContactoController extends Controller
{
    public function index() {
        $username = 'root';
        $password = '98765432';

        // require_once 'views/contacto.php';

        $view = new View();
        $view->render('contacto');
    }

    public function submit() {
        // Validar
        $errores = [];

        // Sanitizar
        $nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);

        if (strlen($nombre) <= 3) {
            $errores['nombre'] = 'El campo nombre es obligatorio y tiene que tener como mimimo 3 caracteres.';
        }

        if (empty($errores)) {
            // Guardar en la DB
            // Enviar por email
        } else {
            $variables = [];
            $variables['nombre'] = $nombre;
            $variables['errores'] = $errores;

            $view = new View();
            $view->render('contacto', $variables);
        }

        var_dump($_POST, $nombre);
    }
}