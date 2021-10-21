<?php

class Camion extends Transporte
{
    private $carga;

    public function __construct(int $ruedas, int $carga)
    {
        parent::__construct($ruedas);
        $this->carga = $carga;
    }

    public function detalle() : string {
        $toneladas = $this->carga / 1000;
        return "El Camion tiene {$this->getRuedas()} ruedas y puede cargar $toneladas toneladas";
    }

    public function mostrar()
    {
        // TODO: Implement mostrar() method.
    }
}