<?php

abstract class Controller
{
    public function runAction($action) {
        if (method_exists($this, $action)) {
            $this->$action();
        } else {
            require_once 'views/404.php';
        }
    }
}