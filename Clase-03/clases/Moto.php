<?php

require_once 'Transporte.php';

class Moto extends Transporte
{
    public function detalle() : string {
        return "La moto tiene {$this->getRuedas()} ruedas \n";
    }

    public function mostrar()
    {
        // TODO: Implement mostrar() method.
    }
}