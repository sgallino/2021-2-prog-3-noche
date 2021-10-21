<?php

namespace App;

class Router
{
    /** @var string */
    private $baseAppPath;
    /** @var string */
    private $publicPath;
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
        $this->routes['GET'][$route] = $fn;
    }

    /**
     * Registra una ruta por POST.
     *
     * @param string $route
     * @param Closure|array|string $fn
     */
    public function post(string $route, $fn)
    {
        $this->routes['POST'][$route] = $fn;
    }

    /**
     * Ejecuta el ruteo.
     */
    public function run()
    {
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
        $fn = $this->routes[$method][$route] ?? null;

        if (is_null($fn)) {
            echo '404';
        } else {
            call_user_func($fn);
        }
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
}
