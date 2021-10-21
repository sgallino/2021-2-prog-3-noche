<?php

require_once 'TransporteInterface.php';

// Vehiculos con ruedas
abstract class Transporte implements TransporteInterface
{
    private $ruedas;

    public function __construct(int $ruedas)
    {
        //$this->ruedas = $ruedas;
        $this->setRuedas($ruedas);
    }

    abstract public function mostrar();

    public function setRuedas(int $ruedas) {
        if ($ruedas > 0) {
            $this->ruedas = $ruedas;
        }
    }

    public function getRuedas()
    {
        return $this->ruedas;
    }

    public function detalle() : string {
        return "El transporte tiene $this->ruedas ruedas";
    }

    public function saludar(string $nombre): string
    {
        return "Hola, " . $nombre;
    }
}