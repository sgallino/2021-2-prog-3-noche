<?php

namespace App\Controllers;


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

        $productos = $producto->all($whereConditions, [Marca::class, Categoria::class]);
        // Traemos las categorías para el select del buscador.
        $categorias = (new Categoria())->all();
        $view = new View();
        $view->render('productos/index', ['productos' => $productos, 'categorias' => $categorias, 'searchValues' => $searchValues]);
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
        // Levantamos los mensajes de error y los datos viejos, si es que existen.
        $errors = Session::flash('errors', []);
        $oldData = Session::flash('old_data', [
            'id_categoria' => '',
            'id_marca' => '',
        ]);
//        $marca = new Marca();
//        $marcas = $marca->all();
        $marcas = (new Marca())->all();
        $categorias = (new Categoria())->all();
        $view = new View();
        $view->render('productos/form-alta', ['marcas' => $marcas, 'categorias' => $categorias, 'errors' => $errors, 'oldData' => $oldData]);
    }

    public static function crear()
    {
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
        $validator = new Validator($_POST, [
            'nombre'        => ['required', 'min:3'],
            'id_marca'      => ['required', 'numeric'],
            'id_categoria'  => ['required', 'numeric'],
            'precio'        => ['required', 'numeric'],
        ]);

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
            // TODO: Ajustar la ruta para que se obtenga de Router.
//            $data['imagen'] = $uploader->save(__DIR__ . '/../../public/imgs');
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

    public static function eliminar()
    {
        $params = Router::getRouteParameters();

        try {
            (new Producto())->delete($params['id']);

            Session::set('message_success', "Producto eliminado exitosamente.");
        } catch(\Exception $e) {
            Session::set('message_error', "Ocurrió un error inesperado al tratar de eliminar el producto. Por favor, probá de nuevo más tarde.");
        }

        Router::redirect('productos');
    }
}
