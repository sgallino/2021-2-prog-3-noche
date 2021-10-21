<?php

class Persona {
    private $nombre;
    // Atributo $apellido como public
    private $apellido;
    private $edad;

    public function __construct($nombre, $apellido, $edad) {
        //$this->nombre = $nombre;
        $this->setNombre($nombre);
        //$this->apellido = $apellido;
        $this->setApellido($apellido);
        $this->setEdad($edad);
    }    

    // Set para nombre
    public function setNombre($nombre) {
        if (!empty(trim($nombre))) {
            $this->nombre = $nombre;
        }
    }

    public function setApellido($apellido) {
        $this->apellido = strtolower($apellido);
    }

    public function getApellido() {
        return ucwords($this->apellido);
    }

    public function setEdad($edad) {
        if ($edad > 0) {
            $this->edad = $edad;
        }
    }

    public function getEdad() {
        return $this->edad;
    }

    public function nombreCompleto() {
        return $this->nombre . ' ' . $this->apellido;
    }
}