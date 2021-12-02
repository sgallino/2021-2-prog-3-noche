<?php

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Router;

/** @var Producto $producto */
/** @var Marca[] $marcas */
/** @var Categoria[] $categorias */
/** @var array $errors */
/** @var array $oldData */
?>
<main class="container pb-3">
    <h1>Editar producto <?= $producto->getIdProducto();?></h1>

    <p>Modificá la info del form a continuación para editar el producto.</p>

    <form action="<?= \App\Router::urlTo('productos/' . $producto->getIdProducto() . '/editar');?>" method="post" enctype="multipart/form-data">
        <?php
        // Marcamos que estamos editando.
        $editando = true;
        require __DIR__ . '/_form.php';
        ?>
    </form>
</main>
