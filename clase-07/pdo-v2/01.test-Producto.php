<?php
require_once 'classes/DBConnection.php';
require_once 'classes/Producto.php';
//require_once 'conexion.php';

$id = 1; // $_GET['id'];

$prod = new Producto();

// Supongamos que tenemos que hacer algo típico que requiere múltiples llamadas a la base de datos.
// Por ejemplo, tener que buscar un producto específico por su PK para poder luego editarlo.
//$prod->findByPk($db, $id); // Esto evita ejecutar múltiples veces la conexión, pero es algo tosco de escribir y usar.
//$prod->update($db, []);
$prod->findByPk($id);
$prod->update([]);
