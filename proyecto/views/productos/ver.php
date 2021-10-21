<?php
/** @var Producto $producto */
?>
<main class="container pb-3">
    <h1><?= $producto->getNombre();?></h1>

    <dl>
        <dt>Precio</dt>
        <dd>$ <?= $producto->getPrecio();?></dd>
        <dt>Categoría</dt>
        <dd><?= $producto->getIdCategoria();?></dd>
        <dt>Marca</dt>
        <dd><?= $producto->getIdMarca();?></dd>
        <dt>Descripción</dt>
        <dd><?= $producto->getDescripcion();?></dd>
    </dl>
</main>
