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

// En esta ocasión, vamos a probar los holders/tokens "denominados".
// Estos se especifican reemplazando cada valor por un ":" (sin comillas) seguido de un nombre
// arbitrario que sirva como "identificador" (similar a las claves de un array asociativo).
// El nombre puede ser cualquier cosa que contenga caracteres válidos de nombres de variables.
// Es una práctica común ponerles de nombre el mismo nombre que el campo al que vayan a asociarse en la
// consulta.
$query = "SELECT * FROM peliculas
        WHERE   genero = :genero
        AND     precio <= :precio";

// Noten que ese query tiene la forma de lo que queremos ejecutar, sin aclarar específicamente los
// valores.
// Ese query es el que vamos a preparar.
$stmt = $db->prepare($query);

// Si ejecutamos ese query tal cual lo preparamos, vamos a tener un error.
// En este caso, los parámetros no se asocian según su ubicación en el array, sino que se asocian
// agregando como clave del array el nombre del token que reemplaza a cada valor.
$stmt->execute([
    'genero' => $genero,
    ':precio' => $precio,
]);

// Alternativamente si queremos traer todos los datos como un array...
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
