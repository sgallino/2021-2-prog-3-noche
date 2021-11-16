<?php
/** @var \App\Models\Producto $producto */
?>
<main class="container pb-3">
    <h1><?= $producto->getNombre();?></h1>

    <dl>
        <dt>Precio</dt>
        <dd>$ <?= $producto->getPrecio();?></dd>
        <dt>Categoría</dt>
        <dd><?= $producto->getCategoria()->getNombre();?></dd>
        <dt>Marca</dt>
        <dd><?= $producto->getMarca()->getNombre()?></dd>
        <dt>Descripción</dt>
        <dd><?= $producto->getDescripcion();?></dd>
    </dl>
</main>
