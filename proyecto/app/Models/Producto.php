<?php
namespace App\Models;

use App\Database\Connection;

class Producto
{
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
     * Obtiene todos los productos.
     *
     * @return Producto[]
     */
    public function all(): array
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM productos";
        $stmt = $db->prepare($query);
        $stmt->execute();
//        $salida = [];
//
//        while($fila = $stmt->fetch(\PDO::FETCH_ASSOC)) {
//            $prod = new Producto();
//            $prod->setIdProducto($fila['id_producto']);
//            $prod->setNombre($fila['nombre']);
//            $prod->setIdCategoria($fila['id_categoria']);
//            // ...
//            $salida[] = $prod;
//        }
        // Definimos a PDOStatement que queremos que cada registro sea una instancia de Producto.
        $stmt->setFetchMode(\PDO::FETCH_CLASS, self::class);
        $listaProductos = $stmt->fetchAll();

        return $listaProductos;
    }

    public function findByPk($pk)
    {
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        // TODO: implementar...
    }

    public function create($data)
    {
        $db = Connection::getConnection();
        // TODO: implementar...
    }

    public function update($data)
    {
        $db = Connection::getConnection();
        // TODO: implementar...
    }

    public function delete()
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
}
