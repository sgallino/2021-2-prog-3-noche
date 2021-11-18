<?php
/** @var \App\Models\Producto $producto */

use App\Router;
?>
<main class="container pb-3">
    <h1><?= $producto->getNombre();?></h1>

    <?php
    // TODO: Hacer un método en Router para la ruta de public
    if(!empty($producto->getImagen()) && file_exists(Router::publicPath('/imgs/' . $producto->getImagen()))):
        // TODO: Agregar un campo en la tabla para guardar un alt para la imagen.
    ?>
        <img src="<?= \App\Router::urlTo('imgs/' . $producto->getImagen());?>" alt="<?= $producto->getNombre();?>">
    <?php
    else:
    ?>
    <p>Sin imagen</p>
    <?php
    endif;
    ?>

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
