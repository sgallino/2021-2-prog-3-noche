<?php

class Producto
{
    private $nombre;

    public function __construct(string $nombre) {
        //$this->nombre = $nombre;
        $this->setNombre($nombre);
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    public function getNombre() {
        return  $this->nombre;
    }
}