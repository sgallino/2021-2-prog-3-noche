<?php

namespace App\Models;

use App\Database\Connection;

/**
 * Clase modelo de base.
 * Ofrece las funcionalidades básicas para interactuar con base de MySQL.
 */
class Model
{
    /**
     * @var string - El nombre de la tabla con la que el modelo mapea.
     */
    protected $table = "";

    /**
     * @var string - El nombre del campo PK de la tabla.
     */
    protected $primaryKey = "";

//    protected $attributes = ['nombre', 'id_categoria', 'id_marca', 'descripcion', 'precio', 'imagen'];
    /**
     * @var array - Las columnas que la tabla tiene y puede cargar en un INSERT, UPDATE, etc.
     */
    protected $attributes = [];

    /**
     * Obtiene todos los productos.
     *
     * @return static[]
     */
    public function all(): array
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM " . $this->table;
        $stmt = $db->prepare($query);
        $stmt->execute();

        // Definimos a PDOStatement que queremos que cada registro sea una instancia de Producto.
        // self, recordamos, se reemplaza por el nombre la clase en la que estamos.
        // En este caso, estamos en Model, así que siempre este método all() va a referenciar con el
        // self a Model. Indistintamente de si el método es ejecutado por una subclase (ej: Producto).
        // Si queremos obtener el nombre de la clase que ejecuta el método, podemos usar la keyword
        // "static" en vez de "self".
        // En resumen:
        // self => El nombre de la clase en donde el método está definido.
        // static => El nombre de la clase que ejecuta el método.

//        echo "Valor de self::class: " . self::class . "<br>";
//        echo "Valor de static::class: " . static::class . "<br>";
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        $registros = $stmt->fetchAll();

        return $registros;
    }

    /**
     * @param int $pk
     * @return static|null
     */
//    public function findByPk(int $pk): ?static // A partir de php 8+, podemos tipar el tipo de retorno como "static", indicando que tiene que ser la clase que ejecuta el método.
    public function findByPk(int $pk): ?Model
    {
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        $query = "SELECT * FROM " . $this->table . "
                    WHERE " . $this->primaryKey . " = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);
        $stmt->setFetchMode(\PDO::FETCH_CLASS, static::class);
        $model = $stmt->fetch();

        if(!$model) {
            return null;
        }

        return $model;
    }

    /**
     * Insertar un registro en la base de datos.
     *
     * @param $data
     * @throws \PDOException
     */
    public function create($data)
    {
        $db = Connection::getConnection();
        $insertFields = $this->getInsertFields($data);
        // Generamos con ayuda de array_fill un array que tenga tantos "?" como campos tenemos.
        $insertHolders = array_fill(0, count($insertFields), '?');
        $insertValues = $this->getInsertValues($insertFields, $data);

        // Armamos los fragmentos del query a partir de los valores calculados.
        $insertFieldList = implode(', ' , $insertFields);
        $insertHolderList = implode(', ' , $insertHolders);

        $query = "INSERT INTO " . $this->table . " (" . $insertFieldList . ")
                  VALUES (" . $insertHolderList . ")";
        $stmt = $db->prepare($query);
        $stmt->execute($insertValues);
    }

    /**
     * Retorna un array con las columnas que deben usarse para el INSERT.
     * Se calculan en base a las claves de $data que existan en static::$attributes.
     *
     * @param array $data
     * @return array
     */
    protected function getInsertFields(array $data): array
    {
        /*
         * Para generar la lista de campos para el INSERT, necesitamos que los campos sean:
         * - Solo campos válidos para el modelo (que estén definidos en $attributes).
         * - Solo sean campos para los cuales recibimos valores para insertar (que estén definidos en
         *  $data).
         */
        $fields = [];
        // Recorremos los datos recibimos, y verificamos cuáles están en $attributes.
        foreach($data as $key => $value) {
            if(in_array($key, $this->attributes)) {
                $fields[] = $key;
            }
        }
        return $fields;
    }

    /**
     * Retorna un array con los valores para el execute del statement.
     * Los valores se definen en el orden de los $fields, a partir de $data.
     *
     * Ejemplo de valores que podríamos tener:
     * $fields = ['id_categoria', 'id_marca', 'descripcion', 'nombre'];
     * $data = [
     *      'nombre' => 'saraza',
     *      'id_marca' => 1,
     *      'id_categoria' => 4,
     *      'descripcion' => 'lalalala',
     *      'imagen' => 'asd.jpg',
     *      '_token' => 'qjhocxifj209ruf0vjmio4j',
     *      ...
     * ];
     *
     * Resultado esperado:
     * $values = [
     *      4, // El valor de id_categoria
     *      1, // El valor de id_marca
     *      'lalalala', // El valor de descripcion
     *      'saraza', // El valor de nombre
     * ];
     *
     * @param array $fields
     * @param array $data
     * @return array
     */
    protected function getInsertValues(array $fields, array $data): array
    {
        $values = [];
        // Recorremos los datos recibimos, y verificamos cuáles están en $attributes.
        foreach($fields as $field) {
            $values[] = $data[$field];
        }
        return $values;
    }
}
