<?php
use App\Auth\Auth;
use App\Models\Producto;
use App\Models\Categoria;
use App\Router;

/** @var Producto[] $productos */
/** @var Categoria[] $categorias */
/** @var array $searchValues */
/** @var array $paginacion */

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
                    <a class="btn btn-outline-primary" href="<?= Router::urlTo('productos/' . $producto->getIdProducto());?>">Ver</a>
                <?php
                if($auth->isAuthenticated()):
                ?>
                    <a class="btn btn-outline-secondary" href="<?= Router::urlTo('productos/' . $producto->getIdProducto() . "/editar");?>">Editar</a>
                    <form action="<?= Router::urlTo('productos/' . $producto->getIdProducto() . "/eliminar");?>" method="post">
                        <button class="btn btn-outline-danger">Eliminar</button>
                    </form>
                <?php
                endif;
                ?>
                </td>
            </tr>
        <?php
        endforeach; ?>
        </tbody>
    </table>

    <?php
    // Agregamos el paginador solamente si hay páginas.
    if($paginacion['paginas'] > 1):
        // Creamos una variable con la URL de base para este documento, incluyendo el parámetro para
        // la página.
        // Esto debe incluir los valores de búsqueda, de haberlos.
        $url = App\Router::urlTo('productos') . "?";

        if(count($searchValues) > 0) {
            foreach($searchValues as $key => $value) {
//                $url .= $key . "=" . $value . "&";
//                $url .= "$key=$value&";
                $url .= "{$key}={$value}&";
            }
        }

        $url .= "p=";
    ?>
    <nav aria-label="Páginas de resultados">
        <ul class="pagination">
        <?php
        if($paginacion['paginaActual'] > 1):
        ?>
            <li class="page-item">
                <a class="page-link" href="<?= $url . ($paginacion['paginaActual'] - 1);?>" aria-label="Página anterior">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php
        else:
        ?>
            <li class="page-item disabled" aria-label="Página anterior">
                <span class="page-link"><span aria-hidden="true">&laquo;</span></span>
            </li>
        <?php
        endif;

        // Generamos el listado de páginas.
        for($i = 1; $i <= $paginacion['paginas']; $i++):
            if($paginacion['paginaActual'] != $i):
        ?>
            <li class="page-item"><a class="page-link" href="<?= $url . $i;?>"><?= $i;?></a></li>
        <?php
            else:
        ?>
            <li class="page-item active" aria-current="page"><span class="page-link"><?= $i;?></span></li>
        <?php
            endif;
        ?>
        <?php
        endfor;

        if($paginacion['paginaActual'] < $paginacion['paginas']):
        ?>
            <li class="page-item">
                <a class="page-link" href="<?= $url . ($paginacion['paginaActual'] + 1);?>" aria-label="Página siguiente">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php
        else:
        ?>
            <li class="page-item disabled" aria-label="Página siguiente">
                <span class="page-link"><span aria-hidden="true">&raquo;</span></span>
            </li>
        <?php
        endif;
        ?>
        </ul>
    </nav>
    <?php
    endif; ?>
</main>
