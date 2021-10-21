<?php

namespace App;

class Router
{
    /** @var string */
    private $baseAppPath;
    /** @var string */
    private $publicPath;
    /** @var string - La URL absoluta a la carpeta raíz del proyecto para el cliente. */
    private static $baseUrlPath;
//    private $appPath;
//    private $viewsPath;
//    private $gets = [];

    /** @var array[] - Lista de las rutas, organizadas por sus métodos de HTTP. */
    private $routes = [
        'GET' => [],
        'POST' => [],
        'PATCH' => [],
        'PUT' => [],
        'DELETE' => [],
        'OPTIONS' => [],
    ];

    /** @var array[] - Lista de las rutas que contienen parámetros, organizadas por su métodos de HTTP. */
    private $parameterizedRoutes = [
        'GET' => [],
        'POST' => [],
        'PATCH' => [],
        'PUT' => [],
        'DELETE' => [],
        'OPTIONS' => [],
    ];

    /** @var array - Los parámetros de la ruta con sus valores, en caso de existir. */
    private static $routeParameters = [];

    /**
     * @param string $baseAppPath
     */
    public function __construct($baseAppPath)
    {
        // Normalizamos las "\" a "/".
        $this->baseAppPath = str_replace('\\', '/', $baseAppPath);
//        $this->appPath = $this->baseAppPath . "/app";
        $this->publicPath = $this->baseAppPath . "/public";
//        $this->viewsPath = $this->baseAppPath . "/views";
        $this->generateBaseUrlPath();
    }

    /**
     * Calcula dinámicamente la URL absoluta de base para el sitio.
     */
    protected function generateBaseUrlPath()
    {
        // Nuevo objetivo: http[s]://localhost/da-vinci/web/4to-cuatrimestre/prog-iii/2021.2/proyecto/public
        // Nosotros tenemos en publicPath: H:/www/da-vinci/web/4to-cuatrimestre/prog-iii/2021.2/proyecto/public
        // Básicamente, la URL de base es idéntica a la del publicPath con la diferencia de que
        // en vez de apuntar a la carpeta del disco (en mi caso, H:/www) debe apuntar al hosting
        // con el protocolo, y tal vez el puerto, de ser necesario.
        // Por ejemplo:
        // http://sitio.com.ar
        // http://localhost
        // http://localhost:8080
        // https://localhost:8080
        // Vamos a obtener cada una de esas partes (protocolo, host, puerto) a partir de datos de
        // $_SERVER.
        // Nota: Algunos de esos chequeos _pueden_ necesitar ajustes dependiendo del web server que
        // usemos, si no es el Apache.
        $protocolo = isset($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['SERVER_NAME'];
        $port = $_SERVER['SERVER_PORT'] !== '80' ?
            ":" . $_SERVER['SERVER_PORT'] :
            '';
        $fullBase = $protocolo . "://" . $host . $port;

        // Y ahora hacemos el reemplazo de fullBase en publicPath para generar el valor final.
        self::$baseUrlPath = str_replace($_SERVER['DOCUMENT_ROOT'], $fullBase, $this->publicPath);
//        echo "<pre>";
//        var_dump($this->publicPath, $fullBase, self::$baseUrlPath);
//        print_r($_SERVER);
//        echo "</pre>";
    }

    /**
     * Registra una ruta por GET.
     *
     * @param string $route
     * @param Closure|array|string $fn
     */
    public function get(string $route, $fn)
    {
//        $this->gets[$route] = $fn;
        // Verificamos si la URL es parametrizada o no.
        if(strpos($route, '{') !== false) {
            $this->parameterizedRoutes['GET'][$route] = $fn;
        } else {
            $this->routes['GET'][$route] = $fn;
        }
    }

    /**
     * Registra una ruta por POST.
     *
     * @param string $route
     * @param Closure|array|string $fn
     */
    public function post(string $route, $fn)
    {
        if(strpos($route, '{') !== false) {
            $this->parameterizedRoutes['POST'][$route] = $fn;
        } else {
            $this->routes['POST'][$route] = $fn;
        }
    }

    /**
     * Ejecuta el ruteo.
     */
    public function run()
    {;
//        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];
        $route = $this->parseUrlIntoRoute($_SERVER['REQUEST_URI']);

//        // Extramos la ruta a partir de la URL y el publicPath.
//        $urlWithDocumentRoot = $_SERVER['DOCUMENT_ROOT'] . $url;
//        $route = str_replace($this->publicPath, "", $urlWithDocumentRoot);
//        echo '<pre>';
//        var_dump($urlWithDocumentRoot, $this->publicPath, $url, $method, $route);
//        print_r($_SERVER);
//        echo '</pre>';

//        if ($method === 'GET') {
//            $fn = $this->gets[$route] ?? null;
//        }
//        if(isset($this->routes[$method][$route])) {
//            $fn = $this->routes[$method][$route];
//        } else {
//            $fn = null;
//        }
        $this->executeRoute($method, $route);
    }

    /**
     * Intenta ejecutar la combinación de ruta y método pedida.
     *
     * @param string $method
     * @param string $route
     */
    protected function executeRoute(string $method, string $route)
    {
        // Tratamos de buscar una ruta exacta que matchee.
        $fn = $this->routes[$method][$route] ?? null;

        // Si no la encontramos, vamos a tratar de buscar una ruta parametrizada que matchee.
        if(!$fn) {
            $fn = $this->searchParameterizedRoute($method, $route);
        }

        if (is_null($fn)) {
            echo '404';
        } else {
            call_user_func($fn);
        }
    }

    /**
     * Looks for a parameterized route that matches the requested URL.
     *
     * @param string $method
     * @param string $route
     * @return string|null
     */
    protected function searchParameterizedRoute(string $method, string $requestedRoute): ?array
    {
        foreach($this->parameterizedRoutes[$method] as $route => $fn) {
            $regex = $this->parameterizedToRegex($route);
//            var_dump($requestedRoute, $regex);
            // Las funciones preg_* son las funciones para trabajar con Regex (Expresiones Regulares)
            // en php.
            // Por ejemplo, preg_match_all verifica, y opcionalmente guarda, los resultados que
            // matcheen el patrón de la Regex (1er parámetro) dentro del string (2do parámetro).
            // Si queremos los resultados, usamos el tercer parámetro, que es una variable donde php
            // va a guardar los resultados.
            // Returna 1 si el string matchea la expresión regular, 0 de lo contrario, y false si
            // ocurre algún error.
            // En esencia, acá preguntamos si la regex que corresponde a esta ruta (calculada en el
            // paso anterior), y en caso de ser así, guardamos los parámetros de la ruta.
            if(preg_match_all($regex, $requestedRoute, $matches)) {
                $this->setRouteParameters($matches);
//                echo "<pre>";
//                var_dump(self::$routeParameters, $fn);
//                echo "</pre>";
                return $fn;
            }
        }
        return null;
    }

    /**
     * Transforma la URL parametrizada a su variante en Regex usando grupos de captura denominados
     * (named capture groups).
     *
     * @param string $parameterized
     * @return string
     */
    protected function parameterizedToRegex(string $parameterized): string
    {
        // Primero, reemplazamos todas las "/" por "\/". Esto es necesario porque en las expresiones
        // regulares de php la "/" es el delimitador de una Regex (todas empiezan y terminan con "/").
        $parameterized = str_replace('/', '\\/', $parameterized);
        // Buscamos todos los pares que matcheen con la URL, capturando los parámetros (lo que están en {} ).
        // La regex:
        //  /{[^\/]*}+/
        // Indica justamente que busque los strings que empiecen con "{", contengan cualquier cantidad
        // de caracteres que _no_ sean "/", y luego tengan otra "}".
        preg_match_all('/{[^\/]+}+/', $parameterized, $matches);
        // Examples
//        $pattern = '/{[^\/]*}+/'; // Cualquier cosa que matchee {some_string}.
//        $replace = '/\(\?<[^\/]*>[^\/]*\)*/'; // Cualquier string que cumple con el formato (?<captured_value>[^\/]*) (a.k.a. named capture group) donde "captured_value" pueda ser cualquier cosa salvo una "/".
//        echo "<pre>";
//        var_dump($parameterized);
//        print_r($matches);
//        echo "</pre>";
        // Acá generamos la expresión regular para la comparación de la ruta, y la captura de los
        // valores de los parámetros.
        // En las regex, podemos pedirm que se capturen partes específicas de una Regex usando ().
        // Por defecto, los grupos de captura se identifican por un número (según su aparición).
        // Opcionalmente, podemos ponerle un nombre a ese grupo de captura para poder referenciarlo
        // más fácilmente. La sintaxis para eso es:
        //  (?<nombre>...)
        // Donde "..." sería el resto de la regex que queremos capturar.
        // Partiendo de eso, entonces la regex que queremos generar sería la misma ruta, pero
        // reemplazando los
        //  {parameter}
        // por la expresión regular
        // (?<parameter>[^\/]+)
        // Que sería "Cualquier string excepto la /", asociada al nombre.
        // Por ejemplo, si el parámetro es:
        //  {id}
        // Quedaría:
        //  (?<id>[^\/]+)
        foreach($matches[0] as $match) {
            $replace = substr($match, 1, -1);
            $parameterized = str_replace($match, '(?<' . $replace . '>[^\/]+)', $parameterized);
        }
        // Como quiero la regex, retorno con las / al comienzo y el final.
        // El "^" al comienzo indica que el valor _debe_ empezar precisamente con esto, y el "$"
        // indica que _debe_ terminar precisamente con esto.
        return '/^' . $parameterized . '$/';
    }

    /**
     * Retorna la ruta a partir de la URL.
     *
     * @param string $url
     * @return string
     */
    protected function parseUrlIntoRoute(string $url): string {
        // Extramos la ruta a partir de la URL y el publicPath.
        $urlWithDocumentRoot = $_SERVER['DOCUMENT_ROOT'] . $url;
        return str_replace($this->publicPath, "", $urlWithDocumentRoot);
    }

    /**
     * Genera una URL absoluta al $path indicado.
     *
     * @param string $path
     * @return string
     */
    public static function urlTo(string $path = ''): string
    {
        // El path siempre debería empezar con un barra. Para que el desarrollador no deba
        // preocuparse por siempre agregarla, vamos a detectar si falta esa barra, y agregarla.
        if(strpos($path, '/') !== 0) {
            $path = '/' . $path;
        }
        return self::$baseUrlPath . $path;
    }

    /**
     * @param array[] $matches
     */
    protected static function setRouteParameters(array $matches)
    {
        $params = [];
        foreach($matches as $key => $match) {
            if(!is_int($key)) {
                $params[$key] = $match[0];
            }
        }
        self::$routeParameters = $params;
    }

    /**
     * Los parámetros de la ruta.
     *
     * @return array
     */
    public static function getRouteParameters(): array
    {
//        echo "<pre>";
//        print_r(self::$routeParameters);
//        echo "</pre>";
        return self::$routeParameters;
    }
}
