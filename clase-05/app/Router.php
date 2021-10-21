<?php

namespace App;

class Router
{
    private $gets = [];

    public function get($url, $fn)
    {
        $this->gets[$url] = $fn;
    }

    public function run()
    {
        $url = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

//        echo '<pre>';
//        var_dump($url, $method);
//        echo '</pre>';

        if ($method === 'GET') {
            $fn = $this->gets[$url] ?? null;
        }

        if (is_null($fn)) {
            echo '404';
        } else {
            call_user_func($fn);
        }
    }
}
