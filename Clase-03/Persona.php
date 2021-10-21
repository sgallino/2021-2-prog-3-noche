<?php

trait Nombre {
    private $nombre;

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getNombre() : string
    {
        return $this->nombre;
    }
}

trait Apellido {
    private $apellido;

    /**
     * @param mixed $apellido
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;
    }

    /**
     * @return mixed
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    abstract public function saludar() : string;
}


class Persona
{
    use Nombre, Apellido;

    public function saludar(): string
    {
        return "Hola, soy $this->nombre $this->apellido";
    }
}

class Producto {
    use Nombre;

    public function getNombre(): string
    {
        return 'Desde la clase Producto ' . $this->nombre;
    }
}

$persona = new Persona();
$persona->setNombre('Juan');
$persona->setApellido('Perez');
echo $persona->getNombre() . ' ' . $persona->getApellido() . "\n";
echo $persona->saludar() . "\n";

$producto = new Producto();
$producto->setNombre('Producto 1');
echo $producto->getNombre() . "\n";