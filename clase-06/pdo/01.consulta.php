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
require 'conexion.php';

$query = "SELECT * FROM peliculas";

// Preparamos el query usando el método "prepare" de PDO, que nos retorna un PDOStatement.
$stmt = $db->prepare($query);

//echo "<pre>";
//print_r($stmt);
//echo "</pre>";

// Ejecutamos el query usando el método "execute".
// A diferencia de mysqli_query, no recibimos el resultset como retorno del execute. Sino que el
// resultset queda almacenado dentro de la instancia de PDOStatement.
$stmt->execute();

//$salida = [];
// Ahora podemos recorrer los resultados usando el método "fetch" de PDOStatement.
// Este método retorna una fila del resultset por ejecución, en orden.

// Forma común con un while
//while($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
//    echo "<pre>";
//    print_r($fila);
//    echo "</pre>";
////    $salida[] = $fila;
//}
//header("Content-Type: application/json");
//echo json_encode($salida);

// Alternativamente si queremos traer todos los datos como un array...
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
