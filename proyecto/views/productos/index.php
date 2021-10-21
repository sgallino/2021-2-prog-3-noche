<?php
/** @var Producto[] $productos */
?>
<main class="container py-3">
    <h1>Listado de Productos</h1>

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
                <td></td>
            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>
</main>
