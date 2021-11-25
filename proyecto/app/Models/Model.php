<?php

namespace App\Models;

use App\Database\Connection;
use PDO;

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
     * @var array - Las relaciones que el modelo tiene. Está dividido en 4 claves: 1-1, 1-n, n-1, n-n.
     */
    protected $relations = [
        '1-1' => [],
        '1-n' => [],
        'n-1' => [
            // Cada relación va a llevar como clave el nombre de la clase que representa la tabla
            // a la que lo asociamos.
            // Como valor, llevará los datos que se necesiten, como el nombre de la FK.
            // Ej:
//            self::class => [
//                'fk' => 'campo_fk',   // <--- El campo de la FK
//                'prop' => 'propiedad', // <--- La propiedad dentro de la clase donde guardar la
//                                              instancia relacionada.
//            ]
        ],
        'n-n' => [],
    ];

    /**
     * @var array - La data de la paginación.
     */
    protected $pagination = [
        'usandoPaginacion' => false,
        'porPagina' => null,
        'total' => null,
        'registroInicial' => null, // El primer valor del LIMIT.
        'paginas' => null,
        'paginaActual' => 1,
    ];

    /**
     * @param int $porPagina
     * @return static
     */
    public function withPagination(int $porPagina = 10): self
    {
        $this->pagination['usandoPaginacion'] = true;
        $this->pagination['porPagina'] = $porPagina;
        // Buscamos por GET el parámetro de la página, que debería ser 'p'.
        $this->pagination['paginaActual'] = (int) ($_GET['p'] ?? 1);
        // Calculamos el primer valor del LIMIT, multiplicando la página actual por la cantidad de
        // registros por página.
        // Nota: Como la página empieza en 1, tenemos que restar al resultado de la multiplicación la
        // cantidad de registros por página. El motivo es que si hacemos la cuenta, por ejemplo para
        // la página 1, sin esa resta queda:
        //  1 * 10 = 10
        // Cuando necesitamos que sea 0, que es de donde empiezan los resultados.
        //  (1 * 10) - 10 = 0
        $this->pagination['registroInicial'] = ($porPagina * $this->pagination['paginaActual']) - $porPagina;

        // Retornamos la propia instancia para poder permitir encadenar otros métodos a éste.
        return $this;
    }

    /**
     * Prepara y ejecuta el query para obtener el total de registros para el $query.
     *
     * @param string $query - Fragmento del query que deba modificar lo que sigue al FROM.
     * @param array $whereValues - Los valores para el execute en base a lo que agregue el $query.
     */
    protected function ejecutarQueryPaginacion(string $query = "", array $whereValues = [])
    {
        $db = Connection::getConnection();
        $query = "SELECT COUNT(*) AS total FROM " . $this->table . " " . $query;
        $stmt = $db->prepare($query);
        $stmt->execute($whereValues);
        // Como es una consulta por una columna de sumatoria (COUNT) sin una cláusula GROUP BY, solo
        // puede haber un resultado.
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->pagination['total'] = $resultado['total'];
        // Calculamos el total de páginas.
        // Esto lo obtenemos diviendo el total de registros por la cantidad que mostramos por página,
        // redondeando para arriba (con la función ceil).
        $this->pagination['paginas'] = ceil($resultado['total'] / $this->pagination['porPagina']);
    }

    /**
     * Obtiene todos los productos.
     *
     * @param array $where - Las cláusulas de búsqueda. Cada valor del array debe ser un array de 3 posiciones: campo, operador, valor. Ej: ['nombre', 'LIKE', '%TV%'] o ['categoria_id', '=', 2].
     * @param array $relations - Las relaciones que cargar para cada registro.
     * @return static[]
     */
    public function all(array $where = [], array $relations = []): array
    {
        $db = Connection::getConnection();
        $query = "SELECT * FROM " . $this->table;

        // Cláusulas de búsqueda.
        $whereValues = [];
        $queryWhere = "";
        if(count($where) > 0) {
            $whereData = [];
            foreach($where as $whereItem) {
                // $whereItem[0] => El campo.
                // $whereItem[1] => El operador.
                // $whereItem[2] => El valor.
                // Armamos el holder denominado usando el mismo de la columna.
                // Ej:
                // $whereData[] = 'nombre LIKE :nombre';
                // $whereData['nombre'] = '%TV%';
                $whereData[] = $whereItem[0] . " " . $whereItem[1] . " :" . $whereItem[0];
                $whereValues[$whereItem[0]] = $whereItem[2];
            }

            // Agregamos la cláusula del WHERE al query.
            // La parte del WHERE la separamos en una variable aparte para poder, si hace falta,
            // agregarla al query del total de registros para la paginación.
            $queryWhere = " WHERE " . implode(" AND ", $whereData);
            $query .= $queryWhere;
        }

        // Preguntamos si hay que usar una paginación.
        if($this->pagination['usandoPaginacion']) {
            // Necesitamos armar un segundo query para obtener los resultados totales, y calcular la
            // cantidad de páginas, y agregar el LIMIT a la consulta principal.
            $this->ejecutarQueryPaginacion($queryWhere, $whereValues);

            // Agregamos el LIMIT
            $query .= " LIMIT " . $this->pagination['registroInicial'] . ", " . $this->pagination['porPagina'];
        }

        $stmt = $db->prepare($query);
        $stmt->execute($whereValues);

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
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $registers = $stmt->fetchAll();

        if(count($relations) > 0) {
            // Cargamos las relaciones.
            // Esta "solución" tiene el problema de que estamos ejecutando un query para cargar las
            // relaciones por cada registro que obtuvimos, generando el problema conocido como
            // "query N+1".
//            /** @var self $registro */
//            foreach($registers as $registro) {
//                $registro->loadRelations($relations);
//            }
            // En su lugar, vamos a hacer otro approach que requiere un poco más de código en php,
            // pero que baje muy considerablemente el impacto a la base de datos, sobretodo mientras
            // más data haya.
            $registers = $this->loadRelationsForManyItems($registers, $relations);
        }

        return $registers;
    }

    /**
     * Carga todas las relaciones pedidas para cada uno de los modelos del array $registers,
     * evitando el problema de "query N+1".
     *
     * @param array $registers
     * @param array $relations
     * @return array
     */
    protected function loadRelationsForManyItems(array $registers, array $relations): array
    {
        /*
         * Para resolver esto, vamos a necesitar crear un query que traiga solo los valores requeridos
         * para una determinada relación.
         * Por ejemplo, si los $registers que traemos son productos, y entre esos productos solo hay
         * en uso las categorías [1, 2, 5, 7, 10], entonces solo vamos a traer con un SELECT los datos
         * de las categorías cuyo id esté entre los valores [1, 2, 5, 7, 10].
         * Por lo tanto, lo primero que necesitamos es obtener los ids de la relación.
         */
        // TODO: Implementar para todos los tipos de relaciones.
        foreach($relations as $relation) {
            if(isset($this->relations['n-1'][$relation])) {
                // Obtenemos las fks de los datos de cada registro de $registers.
                $relationData = $this->relations['n-1'][$relation];
                $fkName = $relationData['fk'];
                $relationProp = $relationData['prop'];
                $fks = [];
                foreach($registers as $register) {
                    // Preguntamos si el valor ya existe en array, y sino lo agregamos.
                    $fkValue = $register->{$fkName};
                    if(!in_array($fkValue, $fks)) {
                        $fks[] = $fkValue;
                    }
                }

                // Hacemos un query para traer todos los registros que tengan como PK esos valores.
                // Instanciamos una clase de la relación (ej: App\Models\Categoria) para cargar los
                // modelos de esa clase.
                $relatedObject = new $relation;
                $relatedCollection = $relatedObject->findAllByPk($fks);

                // Actualizamos las claves del array de la colección de modelos relacionados para que
                // utilicen la PK del modelo.
                $sortedCollection = [];
                /** @var static $item */
                foreach($relatedCollection as $item) {
                    $pkName = $item->primaryKey;
                    $sortedCollection[$item->{$pkName}] = $item;
                }

                // Recorremos los $registers de nuevo para asociarles el modelo relacionado.
                foreach($registers as $register) {
                    $relatedFk = $register->{$fkName};
                    $register->{$relationProp} = $sortedCollection[$relatedFk];
                }
            }
        }
        return $registers;
    }

    /**
     * @param array $pks
     * @return array
     */
    public function findAllByPk(array $pks): array
    {
        $db = Connection::getConnection();
        $holders = implode(', ', array_fill(0, count($pks), '?'));
        $query = "SELECT * FROM " . $this->table . "
                WHERE " . $this->primaryKey . " IN (" . $holders . ")";
        $stmt = $db->prepare($query);
        $stmt->execute($pks);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);

        return $stmt->fetchAll();
    }

    /**
     * @param int $pk
     * @param array $relations - Array con las clases de las relaciones que se quieren cargar.
     * @return static|null
     */
//    public function findByPk(int $pk): ?static // A partir de php 8+, podemos tipar el tipo de retorno como "static", indicando que tiene que ser la clase que ejecuta el método.
    public function findByPk(int $pk, array $relations = []): ?Model
    {
        // Pedimos la conexión a la clase encargada de manejarla.
        $db = Connection::getConnection();
        $query = "SELECT * FROM " . $this->table . "
                    WHERE " . $this->primaryKey . " = ?";
//        echo "Ejecutando el query: " . $query . " con el id: " . $pk . "<br>";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);
        $stmt->setFetchMode(PDO::FETCH_CLASS, static::class);
        $model = $stmt->fetch();

        if(!$model) {
            return null;
        }

        // Cargamos las relaciones, en caso de que se hayan pedido.
        $model->loadRelations($relations);

        return $model;
    }

    /**
     * Carga las relaciones en el modelo.
     *
     * @param array $relations - Array con las clases de las relaciones del modelo. Deben ser de las definidas en la propiedad $relations. Ej: [Categoria::class, Saraza::class]
     */
    public function loadRelations(array $relations = [])
    {
        // $relations = [Categoria::class, Saraza::class]]
        // En principio, solo vamos a levantar las relaciones de 'n-1'.
        // TODO: Implementar las otras relaciones.
        // Recorremos las relaciones, y buscamos la relación correspondiente a esta.
        foreach($relations as $relation) {
//            echo "Cargando la relación... " . $relation . "<br>";
            // Preguntamos si está definida esta relación
            if(isset($this->relations['n-1'][$relation])) {
                $relationData = $this->relations['n-1'][$relation];
//                echo "Relación encontrada.<br>";
//                echo "FK: " . $relationData['fk'] . "<br>";
//                echo "Propiedad destino: " . $relationData['prop'] . "<br>";
                // Instanciamos la clase relacionada.
                // Ej: $relation = \App\Models\Categoria
                /** @var Model $relationObj */
                $relationObj = new $relation; // Podemos usar el valor de la variable como nombre de clase. Ej: new \App\Models\Categoria
                // Llamamos al método "findByPk" del modelo relacionado para traerlo.
                // El valor de la PK va a ser el valor del atributo de la "FK" en _este_ (por ejemplo,
                // Producto) modelo.
                $fk = $this->{$relationData['fk']}; // Ej: $this->id_categoria
                $obj = $relationObj->findByPk($fk);

                // Guardamos el objeto en la propiedad que se definió para este modelo.
                $this->{$relationData['prop']} = $obj; // Ej: $this->categoria = $obj;
            }
        }
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

    /**
     * @param $pk
     * @throws \PDOException
     */
    public function delete($pk): void
    {
        $db = Connection::getConnection();
        $query = "DELETE FROM " . $this->table . "
                  WHERE " . $this->primaryKey . " = ?";
        $stmt = $db->prepare($query);
        $stmt->execute([$pk]);
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }
}
