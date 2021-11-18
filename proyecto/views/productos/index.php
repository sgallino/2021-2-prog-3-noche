<?php
use App\Auth\Auth;
use App\Models\Producto;
use App\Models\Categoria;
use App\Router;

/** @var Producto[] $productos */
/** @var Categoria[] $categorias */
/** @var array $searchValues */

$auth = new Auth;
?>
<main class="container pb-3">
    <h1>Listado de Productos</h1>

    <h2>Buscador</h2>

    <div class="mb-3">
        <form action="<?= Router::urlTo('/productos');?>" method="get">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= $searchValues['nombre'] ?? '';?>" class="form-control">
            </div>
            <div class="mb-3">
                <label for="id_categoria" class="form-label">Categoría</label>
                <select name="id_categoria" id="id_categoria" class="form-control">
                    <option value="">Todas</option>
                    <?php
                    foreach($categorias as $categoria):
                    ?>
                        <option
                            value="<?= $categoria->getIdCategoria();?>"
                            <?= ($searchValues['id_categoria'] ?? null) == $categoria->getIdCategoria() ? 'selected' : '';?>
                        ><?= $categoria->getNombre();?></option>
                    <?php
                    endforeach;
                    ?>
                </select>
            </div>
            <button class="btn btn-primary" type="submit">Buscar</button>
        </form>
    </div>

    <?php
    if($auth->isAuthenticated()): ?>
    <div class="mb-3">
        <a href="<?= Router::urlTo('productos/nuevo');?>">Crear un nuevo producto</a>
    </div>
    <?php
    endif; ?>

    <h2 class="visually-hidden">Listado de productos</h2>

    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Marca</th>
            <th>Categoría</th>
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
                <td><?= $producto->getMarca()->getNombre();?></td>
                <td><?= $producto->getCategoria()->getNombre();?></td>
                <td>$ <?= $producto->getPrecio();?></td>
                <td>
                    <a href="<?= Router::urlTo('productos/' . $producto->getIdProducto());?>">Ver</a>
                    <form action="<?= Router::urlTo('productos/' . $producto->getIdProducto() . "/eliminar");?>" method="post">
                        <button class="btn btn-outline-danger">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>
</main>
