<?php

use App\Models\Categoria;
use App\Models\Marca;

/** @var Marca[] $marcas */
/** @var Categoria[] $categorias */
/** @var array $errors */
/** @var array $oldData */
?>
<main class="container pb-3">
    <h1>Crear nuevo producto</h1>

    <p>Completá el form a continuación para crear un producto.</p>

    <form action="<?= \App\Router::urlTo('productos/nuevo');?>" method="post" enctype="multipart/form-data">
        <?php
        require __DIR__ . '/_form.php';
        ?>
    </form>
</main>
