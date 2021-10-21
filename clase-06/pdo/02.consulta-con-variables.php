<?php
// Con una instancia de PDO lista, podemos proceder a ejecutar consultas.
// El flujo de trabajo con PDO es parecido a mysqli, pero con una salvedad en líneas generales.
// La idea es:
// 1. Armar la consulta a ejecutar.
// 2. _Preparar_ la consulta desde PDO, obteniendo una instancia de PDOStatement.
// 3. Ejecutar la consulta desde esa instancia de PDOStatement.
// 4. Utilizar los resultados.
// Esta idea de separar en dos pasos la "preparación" y su "ejecución" se conoce como "Prepared
// Statements".

// El objetivo de separar las etapas de preparación y de ejecución se ve cuando queremos usar
// valores provenientes del usuario, o "no confiables".

// Vamos ahora a realizar de nuevo una consulta a la tabla de películas, pero esta vez agregando
// dos parámetros de búsqueda, simulando datos ingresados en un formulario por un usuario.
require 'conexion.php';

// Simulamos los datos del usuario...
$genero = "Drama";  // $genero = $_GET['genero'];
$precio = 20;       // $precio = $_GET['precio'];

// Antes, nosotros pasábamos las variables directamente al query. Por ejemplo:
//$query = "SELECT * FROM peliculas
//        WHERE   genero = '$genero'
//        AND     precio <= $precio";

// El incluir esos valores "no confiables" en el query directamente hace a la consulta vulnerable a
// inyección SQL.
// Si alguna de esas variables modifica el string de la consulta para transformarla en otra consulta
// diferente, la base de datos no tiene darse cuenta.
// Es decir, la vulnerabilidad se presante ante el hecho de que me pueden modificar la consulta que
// pretendíamos ejecutar por una diferente sin que la base de datos se entere.
// Prepared Statements busca resolver esto haciendo que la base de datos sepa cuál es la consulta
// que queremos ejecutar _antes_ de que le pasemos los datos "no confiables".

// Esto lo hacemos reemplazando en el query los valores por "holders" o "tokens" que representen un
// valor, sin especificar cual.
// Hay 2 tipos de holders/tokens:
// - posicionales (positional/indexed, "secuenciales" como me gusta llamarlos).
// - denominados/nombrados (named, "asociativos" como me gusta llamarlos).

// Vamos a probar los holders posicionales.
// Estos holders se crean reemplazando cada valor por un token de un signo de pregunta: ?
$query = "SELECT * FROM peliculas
        WHERE   genero = ?
        AND     precio <= ?";

// Noten que ese query tiene la forma de lo que queremos ejecutar, sin aclarar específicamente los
// valores.
// Ese query es el que vamos a preparar.
$stmt = $db->prepare($query);

// Si ejecutamos ese query tal cual lo preparamos, vamos a tener un error.
// Después de todo, los "?" no son parte de la sintaxis válida de SQL.
// Los "?" estaban indicando que ahí va a haber un valor. Para poder ejecutar el query, le tenemos
// que indicar a la base cuáles son los valores que corresponden a esos tokens.
// La forma más común de hacerlo es pasando como parámetro al execute un array, donde las posiciones
// de los valores deben coincidir con las posiciones de los tokens que reemplazan.
$stmt->execute([
    $genero, // El primer valor (clave 0) va a corresponder al primer "?".
    $precio, // El segundo valor (clave 1) va a corresponder al segundo "?". Y así sucesivamente.
]);

// Alternativamente si queremos traer todos los datos como un array...
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
