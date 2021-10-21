<?php

class Producto
{
    protected $id_producto;
    protected $id_categoria;
    protected $id_marca;
    protected $nombre;
    protected $descripcion;
    protected $precio;
    protected $imagen;

//    protected $db;

//    public function __construct($db)
//    {
//        $this->db = $db;
//    }

    public function findByPk($pk)
    {
        // ...
//        require __DIR__ . '/../conexion.php';
//        $dsn = 'mysql:dbname=cwm_2020_2_n;host=127.0.0.1';
//        $user = 'root';
//        $pass = '';
//
//        try {
//            $gbd = new PDO($dsn, $user, $pass);
//            echo "Conexión a la base de datos iniciada...<br>";
//        } catch (PDOException $e) {
//            echo 'Falló la conexión: ' . $e->getMessage();
//        }
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        echo "Buscando por la PK " . $pk . "...<br>";
    }

    public function all()
    {
        // ...
//        require __DIR__ . '/../conexion.php';
//        $dsn = 'mysql:dbname=cwm_2020_2_n;host=127.0.0.1';
//        $user = 'root';
//        $pass = '';
//
//        try {
//            $gbd = new PDO($dsn, $user, $pass);
//            echo "Conexión a la base de datos iniciada...<br>";
//        } catch (PDOException $e) {
//            echo 'Falló la conexión: ' . $e->getMessage();
//        }
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        echo "Buscando todos los productos...<br>";
    }

    public function create($data)
    {
        // ...
//        require __DIR__ . '/../conexion.php';
//        $dsn = 'mysql:dbname=cwm_2020_2_n;host=127.0.0.1';
//        $user = 'root';
//        $pass = '';
//
//        try {
//            $gbd = new PDO($dsn, $user, $pass);
//            echo "Conexión a la base de datos iniciada...<br>";
//        } catch (PDOException $e) {
//            echo 'Falló la conexión: ' . $e->getMessage();
//        }
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        echo "Creando un producto...<br>";
    }

    public function update($data)
    {
        // ...
//        require __DIR__ . '/../conexion.php';
//        $dsn = 'mysql:dbname=cwm_2020_2_n;host=127.0.0.1';
//        $user = 'root';
//        $pass = '';
//
//        try {
//            $gbd = new PDO($dsn, $user, $pass);
//            echo "Conexión a la base de datos iniciada...<br>";
//        } catch (PDOException $e) {
//            echo 'Falló la conexión: ' . $e->getMessage();
//        }
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        echo "Actualizando un producto...<br>";
    }

    public function delete()
    {
        // ...
//        require __DIR__ . '/../conexion.php';
//        $dsn = 'mysql:dbname=cwm_2020_2_n;host=127.0.0.1';
//        $user = 'root';
//        $pass = '';
//
//        try {
//            $gbd = new PDO($dsn, $user, $pass);
//            echo "Conexión a la base de datos iniciada...<br>";
//        } catch (PDOException $e) {
//            echo 'Falló la conexión: ' . $e->getMessage();
//        }
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        echo "Eliminando un producto...<br>";
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
