<?php

/*if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 'inicio';
}*/

//$page = isset($_GET['page']) ? $_GET['page'] : 'inicio';

require_once 'app/View.php';
require_once 'app/Controller.php';
require_once 'app/Controllers/ContactoController.php';
require_once 'app/Controllers/InicioController.php';

require_once 'app/Models/Contacto.php';

/*$contacto = new Contacto();
$contacto->findById(1);

var_dump($contacto);

die;*/

$page = $_GET['page'] ?? 'inicio';
$action = $_GET['action'] ?? 'index';

if ($page == 'inicio') {
    $inicioController = new InicioController();
    $inicioController->runAction($action);
} else if ($page == 'contacto') {
    $contactoController = new ContactoController();
    $contactoController->runAction($action);

    // index, create, store, edit, update, show, destroy

    /*if ($action == 'index') {
        $contactoController->index();
    } else if ($action == 'submit') {
        $contactoController->submit();
    } else {
        require_once 'views/404.php';
    }*/

} else if ($page == 'servicios') {
    require_once 'views/servicios.php';
} else {
    require_once 'views/404.php';
}