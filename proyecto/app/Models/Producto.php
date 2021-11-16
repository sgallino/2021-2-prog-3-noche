<?php
namespace App\Models;

use App\Database\Connection;

class Producto extends Model
{
    // Configuramos la clase Model.
    // Sobrescribimos las propiedades de Model con los valores correspondientes a este modelo.
    protected $table = 'productos';
    protected $primaryKey = 'id_producto';
    protected $attributes = ['id_categoria', 'id_marca', 'nombre', 'descripcion', 'precio', 'imagen'];
    protected $relations = [
        'n-1' => [
            // La clave es el nombre de la clase relacionada.
            Categoria::class => [
                // El nombre de la propiedad que tiene el valor de la FK de la relación.
                'fk' => 'id_categoria',
                // El nombre de la prop de _esta_ clase donde queeremos guardar la instancia.
                'prop' => 'categoria',
            ],
            Marca::class => [
                'fk' => 'id_marca',
                'prop' => 'marca',
            ],
        ]
    ];

    protected $id_producto;
    protected $id_categoria;
    protected $id_marca;
    protected $nombre;
    protected $descripcion;
    protected $precio;
    protected $imagen;

    protected $stock;
    protected $cuotas_sin_interes;

    /**
     * @var Categoria
     */
    protected $categoria;

    /**
     * @var Marca
     */
    protected $marca;

    public function update($data)
    {
        $db = Connection::getConnection();
        // TODO: implementar...
    }

    /**
     * @return mixed
     */
    public function getIdProducto()
    {
        return $this->id_producto;
    }

    /**
     * @param mixed $id_producto
     */
    public function setIdProducto($id_producto)
    {
        $this->id_producto = $id_producto;
    }

    /**
     * @return mixed
     */
    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    /**
     * @param mixed $id_categoria
     */
    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    /**
     * @return mixed
     */
    public function getIdMarca()
    {
        return $this->id_marca;
    }

    /**
     * @param mixed $id_marca
     */
    public function setIdMarca($id_marca)
    {
        $this->id_marca = $id_marca;
    }

    /**
     * @return mixed
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param mixed $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return mixed
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * @param mixed $descripcion
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    /**
     * @return mixed
     */
    public function getPrecio()
    {
        return $this->precio;
    }

    /**
     * @param mixed $precio
     */
    public function setPrecio($precio)
    {
        $this->precio = $precio;
    }

    /**
     * @return mixed
     */
    public function getImagen()
    {
        return $this->imagen;
    }

    /**
     * @param mixed $imagen
     */
    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    /**
     * @return Categoria
     */
    public function getCategoria(): Categoria
    {
        return $this->categoria;
    }

    /**
     * @param Categoria $categoria
     */
    public function setCategoria(Categoria $categoria): void
    {
        $this->categoria = $categoria;
    }

    /**
     * @return Marca
     */
    public function getMarca(): Marca
    {
        return $this->marca;
    }

    /**
     * @param Marca $marca
     */
    public function setMarca(Marca $marca): void
    {
        $this->marca = $marca;
    }
}
