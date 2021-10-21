<?php
/*
 * Creamos una conexión a MySQL con PDO.
 *
 * Vamos a necesitar primero definir los datos de la conexión, empezando por los mismos de siempre:
 * - host
 * - user
 * - pass
 * - base
 *
 * En PDO vamos a necesitar un dato más que preparar. Se va a preparar a partir de algunos de esos
 * datos.
 * Este valor que hay que preparar es el "dsn" (Driver Source Name).
 *
 * El "dsn" es un string de conexión que indica con qué base de datos nos queremos conectar.
 * El formato para MySQL/MariaDB es:
 *
 * "mysql:host=<acá-el-host>;dbname=<acá-la-base>;charset=utf8mb4"
 */

$DB_HOST = "localhost";
$DB_USER = "root";
$DB_PASS = "";
$DB_BASE = "cwm_2020_2_n";
$DB_DSN = "mysql:host={$DB_HOST};dbname={$DB_BASE};charset=utf8mb4";

// Para abrir la conexión, debemos instanciar la clase nativa de php "PDO", que en su constructor va
// a recibir 3 parámetros: DSN, user, pass.

// Si la conexión falla, PDO nos retorna una Exception.
// Por lo tanto, lo capturamos con un try...catch.
try {
    $db = new PDO($DB_DSN, $DB_USER, $DB_PASS);
    echo "Conexión a la base de datos inicializada...<br>";
} catch(PDOException $e) {
    echo "Ocurrió un problema al conectar con la base de datos :(";
    exit;
    // Redireccionar a o mostrar una pantalla de mantenimiento.
}
