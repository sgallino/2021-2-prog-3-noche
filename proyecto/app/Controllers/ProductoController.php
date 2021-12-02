<?php

namespace App\Controllers;


use App\Auth\Auth;
use App\Filesystem\FileUpload;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Router;
use App\Session\Session;
use App\Validation\Validator;
use App\View;

class ProductoController
{
    /**
     * @var Auth
     */
    protected static $auth;

    /**
     * @var string[][] Reglas de validación.
     */
    protected static $rules = [
        'nombre'        => ['required', 'min:3'],
        'id_marca'      => ['required', 'numeric'],
        'id_categoria'  => ['required', 'numeric'],
        'precio'        => ['required', 'numeric'],
    ];

    public static function index()
    {
        $producto = new Producto();

        // Parámetros de búsqueda.
        $whereConditions = [];
        $searchValues = [];

        // Filtramos los valores que llegan por GET y tienen valor (no están vacíos) para agregarlos al
        // array de parámetros de búsqueda.
        // Como pedimos en el método all(), cada parámetro de búsqueda debe cumplir con el formato:
        // [campo, operador, valor]
        // También guardamos en $searchValues los valores para poder re-poblarlos en el form para el
        // usuario.
        if(!empty($_GET['nombre'])) {
            $searchValues['nombre'] = $_GET['nombre'];
            $whereConditions[] = ['nombre', 'LIKE', '%' . $_GET['nombre'] . '%'];
        }

        if(!empty($_GET['id_categoria'])) {
            $searchValues['id_categoria'] = $_GET['id_categoria'];
            $whereConditions[] = ['id_categoria', '=', $_GET['id_categoria']];
        }

//        $productos = $producto->all($whereConditions, [Marca::class, Categoria::class]);
//        $productos = $producto->all($whereConditions, [Marca::class, Categoria::class], 10);
        // Usamos una interfaz "fluida" (fluent) para encadenar métodos.
        $productos = $producto
            ->withPagination(10)
            // Ejemplos de cómo podrían ser los otros métodos usando una interfaz fluida.
//            ->where($whereConditions)
//            ->withRelations([Marca::class, Categoria::class])
//            ->all();
            ->all($whereConditions, [Marca::class, Categoria::class]);
        $paginacion = $producto->getPagination();
        // Traemos las categorías para el select del buscador.
        $categorias = (new Categoria())->all();
        $view = new View();
        $view->render('productos/index', ['productos' => $productos, 'categorias' => $categorias, 'searchValues' => $searchValues, 'paginacion' => $paginacion]);
    }

    public static function ver()
    {
        $parameters = Router::getRouteParameters();
        $producto = new Producto();
//        $producto = $producto->findByPk($parameters['id']);
        // Pedimos que se carguen también las relaciones.
        $relations = [Categoria::class, Marca::class];
        $producto = $producto->findByPk($parameters['id'], $relations);
//        echo "<pre>";
//        print_r($relations);
//        echo "</pre>";
//        echo "<pre>";
//        print_r($producto);
//        echo "</pre>";
        $view = new View();
        $view->render('productos/ver', ['producto' => $producto]);
    }

    public static function crearForm()
    {
        self::requireAuthetication();

        // Levantamos los mensajes de error y los datos viejos, si es que existen.
        $errors = Session::flash('errors', []);
        $oldData = Session::flash('old_data', [
            'id_categoria' => '',
            'id_marca' => '',
        ]);
        $marcas = (new Marca())->all();
        $categorias = (new Categoria())->all();
        $view = new View();
        $view->render('productos/form-alta', ['marcas' => $marcas, 'categorias' => $categorias, 'errors' => $errors, 'oldData' => $oldData]);
    }

    public static function crear()
    {
        self::requireAuthetication();

        // El Validator recibe 2 parámetros en su constructor:
        // 1. array - La data a validar.
        // 2. array - Las "reglas" de validación.
        // "Regla" de validación es un término típico en este tipo de implementaciones que significa
        // una validación específica sobre un valor. Por ejemplo, puedo tener una regla que sea checkear
        // que el campo se obligatorio, otra que verifique la cantidad máxima de caracteres, otra que
        // verifica la cantidad mínima de caracteres, otra para formato de email, etc.
        // En el array de "regla", las "keys" deben coincidir con keys del array de datos, y los valores
        // van a ser arrays que contenga la lista de reglas a aplicar sobre el valor de esa key.
        // Las reglas, por supuesto, deben estar pre-definidas por la clase de Validación.
        $validator = new Validator($_POST, self::$rules);

        if($validator->fails()) {
            Session::set('old_data', $_POST);
            Session::set('errors', $validator->getErrors());
            // Redireccionamos al form de nuevo...
            Router::redirect('productos/nuevo');
        }

        $data = [
            'nombre'        => $_POST['nombre'],
            'id_marca'      => $_POST['id_marca'],
            'id_categoria'  => $_POST['id_categoria'],
            'precio'        => $_POST['precio'],
            'descripcion'   => $_POST['descripcion'],
            'imagen'        => '',
        ];

        // Upload de la imagen.
        $imagen = $_FILES['imagen'];
        if(!empty($imagen['tmp_name'])) {
            $uploader = new FileUpload($_FILES['imagen']);
            $data['imagen'] = $uploader->save(Router::publicPath('/imgs/'));
        }

//        $producto = new Producto();
//        $producto->create($data);
        // Idem a lo de arriba, pero sin guardar el objeto en una variable.
        try {
            (new Producto())->create($data);

            Session::set('message_success', "El producto fue creado con éxito.");
            Router::redirect('productos');
//        header("Location: " . Router::urlTo('productos'));
//        exit;
        } catch(\PDOException $e) {
            // Error al grabar el producto.
            Session::set('message_error', "Ocurrió un error inesperado al tratar de grabar el producto. Por favor, probá de nuevo más tarde.");
            Session::set('old_data', $_POST);
            Router::redirect('productos/nuevo');
        }
    }

    public static function editarForm()
    {
        self::requireAuthetication();

        $params = Router::getRouteParameters();
        $producto = (new Producto())->findByPk($params['id']);

        $marcas = (new Marca())->all();
        $categorias = (new Categoria())->all();

        $errors = Session::flash('errors', []);
        $oldData = Session::flash('old_data', [
            'nombre' => $producto->getNombre(),
            'precio' => $producto->getPrecio(),
            'descripcion' => $producto->getDescripcion(),
            'id_categoria' => $producto->getIdCategoria(),
            'id_marca' => $producto->getIdMarca(),
        ]);

        $view = new View();
        $view->render('productos/form-editar', [
            'producto'      => $producto,
            'marcas'        => $marcas,
            'categorias'    => $categorias,
            'errors'        => $errors,
            'oldData'       => $oldData,
        ]);
    }

    public static function editar()
    {
        self::requireAuthetication();

        $params = Router::getRouteParameters();
        $id = $params['id'];

        $validator = new Validator($_POST, self::$rules);

        if($validator->fails()) {
            Session::set('old_data', $_POST);
            Session::set('errors', $validator->getErrors());
            // Redireccionamos al form de nuevo...
            Router::redirect('productos/' . $id . '/editar');
        }

        $producto = (new Producto())->findByPk($id);

        $data = [
            'nombre' => $_POST['nombre'],
            'id_marca' => $_POST['id_marca'],
            'id_categoria' => $_POST['id_categoria'],
            'precio' => $_POST['precio'],
            'descripcion' => $_POST['descripcion'],
            'imagen' => $producto->getImagen(), // Por defecto, asumimos que va a mantener la imagen actual.
        ];

        if(!empty($_FILES['imagen']['tmp_name'])) {
            $uploader = new FileUpload($_FILES['imagen']);
            $data['imagen'] = $uploader->save(Router::publicPath('/imgs/'));
        }

        try {
            (new Producto)->update($id, $data);
            Session::set('message_success', 'El producto <b>' . $producto->getNombre() . '</b> se editó correctamente.');
            Router::redirect('productos');
        } catch(\Exception $e) {
            Session::set('message_error', "Ocurrió un error inesperado al tratar de editar el producto.");
            Session::set('old_data', $_POST);
            // Redireccionamos al form de nuevo...
            Router::redirect('productos/' . $id . '/editar');
        }
    }

    public static function eliminar()
    {
        self::requireAuthetication();

        $params = Router::getRouteParameters();

        try {
            (new Producto())->delete($params['id']);

            Session::set('message_success', "Producto eliminado exitosamente.");
        } catch(\Exception $e) {
            Session::set('message_error', "Ocurrió un error inesperado al tratar de eliminar el producto. Por favor, probá de nuevo más tarde.");
        }

        Router::redirect('productos');
    }

    /**
     * Verifica que el usuario esté autenticado.
     * De no estarlo, lo redirecciona automáticamente al iniciar sesión.
     */
    protected static function requireAuthetication()
    {
        self::$auth = new Auth();
        if(!self::$auth->isAuthenticated()) {
            Session::set('message_error', "Tenés que iniciar sesión antes de poder acceder a esta pantalla.");
            Router::redirect('iniciar-sesion');
        }
    }
}
