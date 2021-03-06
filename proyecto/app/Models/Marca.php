<?php

namespace App\Models;


class Marca extends Model
{
    protected $table = "marcas";
    protected $primaryKey = "id_marca";
    protected $attributes = ["nombre"];

    /**
     * @var int
     */
    protected $id_marca;

    /**
     * @var string
     */
    protected $nombre;

    /**
     * @return int
     */
    public function getIdMarca(): int
    {
        return $this->id_marca;
    }

    /**
     * @param int $id_marca
     */
    public function setIdMarca(int $id_marca): void
    {
        $this->id_marca = $id_marca;
    }

    /**
     * @return string
     */
    public function getNombre(): string
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre(string $nombre): void
    {
        $this->nombre = $nombre;
    }
}
