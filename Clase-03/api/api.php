<?php

//require_once 'Controllers/Persona.php';
//require_once 'Models/Persona.php';

spl_autoload_register(function ($class) {
    // echo $class;
    $file = str_replace('\\', '/', $class) . '.php';

    if (file_exists($file)) {
        require_once $file;
    }
});

use Models\Persona as Persona;
use Controllers\Persona as PersonaController;

$model = new Persona();
$controller = new PersonaController();

var_dump($model, $controller);

echo $model->mensaje();

PersonaController::saludar();