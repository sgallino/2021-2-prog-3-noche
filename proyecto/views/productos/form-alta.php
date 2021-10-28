<?php

use App\Models\Categoria;
use App\Models\Marca;

/** @var Marca[] $marcas */
/** @var Categoria[] $categorias */
?>
<main class="container pb-3">
    <h1>Crear nuevo producto</h1>

    <p>Completá el form a continuación para crear un producto.</p>

    <form action="<?= \App\Router::urlTo('productos/nuevo');?>" method="post">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" id="nombre" name="nombre" class="form-control">
        </div>
        <div class="mb-3">
            <label for="id_marca" class="form-label">Marca</label>
            <select id="id_marca" name="id_marca" class="form-control">
                <option value="">Seleccioná la marca...</option>
                <?php
                foreach($marcas as $marca):
                ?>
                    <option value="<?= $marca->getIdMarca();?>">
                        <?= $marca->getNombre();?>
                    </option>
                <?php
                endforeach;
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_categoria" class="form-label">Categoría</label>
            <select id="id_categoria" name="id_categoria" class="form-control">
                <option value="">Seleccioná la categoría...</option>
                <?php
                foreach($categorias as $categoria):
                    ?>
                    <option value="<?= $categoria->getIdCategoria();?>">
                        <?= $categoria->getNombre();?>
                    </option>
                <?php
                endforeach;
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="text" id="precio" name="precio" class="form-control">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea id="descripcion" name="descripcion" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Grabar</button>
    </form>
</main>
