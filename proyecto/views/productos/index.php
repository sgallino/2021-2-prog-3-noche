<?php
use App\Auth\Auth;

/** @var Producto[] $productos */

$auth = new Auth;
?>
<main class="container pb-3">
    <h1>Listado de Productos</h1>

    <?php
    if($auth->isAuthenticated()): ?>
    <div class="mb-3">
        <a href="<?= \App\Router::urlTo('productos/nuevo');?>">Crear un nuevo producto</a>
    </div>
    <?php
    endif; ?>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Precio</th>
            <th>Acciones</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($productos as $producto): ?>
            <tr>
                <td><?= $producto->getIdProducto();?></td>
                <td><?= $producto->getNombre();?></td>
                <td>$ <?= $producto->getPrecio();?></td>
                <td>
                    <a href="<?= \App\Router::urlTo('productos/' . $producto->getIdProducto());?>">Ver</a>
                    <form action="<?= \App\Router::urlTo('productos/' . $producto->getIdProducto() . "/eliminar");?>" method="post">
                        <button class="btn btn-outline-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>
</main>
