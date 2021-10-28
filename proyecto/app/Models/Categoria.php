<?php

namespace App\Models;


class Categoria extends Model
{
    protected $table = 'categorias';
    protected $primaryKey = 'id_categoria';
    protected $attributes = ['nombre'];

    /**
     * @var int
     */
    protected $id_categoria;

    /**
     * @var string
     */
    protected $nombre;

    /**
     * @return int
     */
    public function getIdCategoria(): int
    {
        return $this->id_categoria;
    }

    /**
     * @param int $id_categoria
     */
    public function setIdCategoria(int $id_categoria): void
    {
        $this->id_categoria = $id_categoria;
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
