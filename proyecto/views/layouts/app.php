<?php
/** @var string $content */

use App\Auth\Auth;

$messageSuccess = $_SESSION['message_success'] ?? null;
$messageError = $_SESSION['message_error'] ?? null;
unset($_SESSION['message_success'], $_SESSION['message_error']);

$auth = new Auth();
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CMS de Productos</title>
<!--    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">-->
    <link href="css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">DV Productos</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar" aria-controls="navbar" aria-expanded="false" aria-label="Abrir/cerrar menú de navegación">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbar">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">Home</a>
                        <!-- <a class="nav-link active" aria-current="page" href="#">Home</a> -->
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="productos">Productos</a>
                    </li>
                    <?php
                    if($auth->isAuthenticated()): ?>
                    <li class="nav-item">
                        <form action="cerrar-sesion" method="post">
                            <button class="btn nav-link" type="submit">Cerrar Sesión (<?= $auth->getUser()->getEmail();?>)</button>
                        </form>
                    </li>
                    <?php
                    else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="iniciar-sesion">Iniciar Sesión</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="registro">Registrarse</a>
                    </li>
                    <?php
                    endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <?php //echo $header ?? ''; ?>

    <div class="container mt-2">
    <?php
    if($messageSuccess): ?>
        <div class="alert alert-success" role="alert">
            <?= $messageSuccess;?>
        </div>
    <?php
    endif; ?>

    <?php
    if($messageError): ?>
    <div class="alert alert-danger" role="alert">
        <?= $messageError;?>
    </div>
    <?php
    endif; ?>
    </div>

    <?php echo $content; ?>
</body>
</html>
