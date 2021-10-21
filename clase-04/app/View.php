<?php

class View
{
    public function render($view, $variables = []) {
        extract($variables);

        require_once 'views/layouts/app.php';
        // require_once "views/$view.php";
    }
}