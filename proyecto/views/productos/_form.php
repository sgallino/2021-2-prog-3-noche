<?php
// Vista "parcial" con el form para el alta/editar producto.

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Router;

/** @var Producto $producto|null */
/** @var Marca[] $marcas */
/** @var Categoria[] $categorias */
/** @var array $errors */
/** @var array $oldData */
/** @var bool $editando */

// Si no está definida esta variable, asumimos que estamos creando.
$editando = $editando ?? false;
?>
<div class="mb-3">
    <label for="nombre" class="form-label">Nombre</label>
    <input
        type="text"
        id="nombre"
        name="nombre"
        class="form-control"
        value="<?= $oldData['nombre'] ?? '';?>"
        <?= isset($errors['nombre']) ? 'aria-describedby="error-nombre"' : '';?>
    >
    <?php
    if(isset($errors['nombre'])):
        ?>
        <div class="text-danger" id="error-nombre"><span class="visually-hidden">Error: </span><?= $errors['nombre'][0];?></div>
    <?php
    endif; ?>
</div>
<div class="mb-3">
    <label for="id_marca" class="form-label">Marca</label>
    <select
        id="id_marca"
        name="id_marca"
        class="form-control"
        <?= isset($errors['id_marca']) ? 'aria-describedby="error-id_marca"' : '';?>
    >
        <option value="">Seleccioná la marca...</option>
        <?php
        foreach($marcas as $marca):
            ?>
            <option
                value="<?= $marca->getIdMarca();?>"
                <?= $marca->getIdMarca() == $oldData['id_marca'] ? "selected" : "";?>
            >
                <?= $marca->getNombre();?>
            </option>
        <?php
        endforeach;
        ?>
    </select>
    <?php
    if(isset($errors['id_marca'])):
        ?>
        <div class="text-danger" id="error-id_marca"><span class="visually-hidden">Error: </span><?= $errors['id_marca'][0];?></div>
    <?php
    endif; ?>
</div>
<div class="mb-3">
    <label for="id_categoria" class="form-label">Categoría</label>
    <select
        id="id_categoria"
        name="id_categoria"
        class="form-control"
        <?= isset($errors['id_categoria']) ? 'aria-describedby="error-id_categoria"' : '';?>
    >
        <option value="">Seleccioná la categoría...</option>
        <?php
        foreach($categorias as $categoria):
            ?>
            <option
                value="<?= $categoria->getIdCategoria();?>"
                <?= $categoria->getIdCategoria() == $oldData['id_categoria'] ? "selected" : "";?>
            >
                <?= $categoria->getNombre();?>
            </option>
        <?php
        endforeach;
        ?>
    </select>
    <?php
    if(isset($errors['id_categoria'])):
        ?>
        <div class="text-danger" id="error-id_categoria"><span class="visually-hidden">Error: </span><?= $errors['id_categoria'][0];?></div>
    <?php
    endif; ?>
</div>
<div class="mb-3">
    <label for="precio" class="form-label">Precio</label>
    <input
        type="text"
        id="precio"
        name="precio"
        class="form-control"
        value="<?= $oldData['precio'] ?? '';?>"
        <?= isset($errors['precio']) ? 'aria-describedby="error-precio"' : '';?>
    >
    <?php
    if(isset($errors['precio'])):
        ?>
        <div class="text-danger" id="error-precio"><span class="visually-hidden">Error: </span><?= $errors['precio'][0];?></div>
    <?php
    endif; ?>
</div>
<div class="mb-3">
    <label for="descripcion" class="form-label">Descripción</label>
    <textarea
        id="descripcion"
        name="descripcion"
        class="form-control"
                <?= isset($errors['descripcion']) ? 'aria-describedby="error-descripcion"' : '';?>
            ><?= $oldData['descripcion'] ?? '';?></textarea>
</div>
<?php
if($editando):
?>
<div class="mb-3">
    <p>Imagen actual</p>
    <?php
    if(!empty($producto->getImagen()) && file_exists(Router::publicPath('/imgs/' . $producto->getImagen()))):
        ?>
        <img src="<?= Router::urlTo('/imgs/' . $producto->getImagen());?>" alt="">
    <?php
    else:
        ?>
        <p>No tiene imagen.</p>
    <?php
    endif;
    ?>
</div>
<?php
endif;
?>
<div class="mb-3">
    <label for="imagen" class="form-label">Imagen</label>
    <input type="file" id="imagen" name="imagen" class="form-control">
    <?php
    if($editando):
    ?>
    <p class="text-muted">Solo elegí una imagen si querés modificar la actual.</p>
    <?php
    endif;
    ?>
</div>
<button type="submit" class="btn btn-primary">Grabar</button>
