<?php

require_once 'Transporte.php';

class Auto extends Transporte
{
    private $puertas;

    public function __construct(int $ruedas, int $puertas)
    {
        parent::__construct($ruedas);
        //$this->setRuedas($ruedas);
        $this->puertas = $puertas;
    }

    public function detalle() : string {
        return "El Auto tiene {$this->getRuedas()} ruedas y tiene $this->puertas puertas";
    }

    public function mostrar()
    {
        // TODO: Implement mostrar() method.
    }
}