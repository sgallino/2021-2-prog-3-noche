<?php
namespace App\Database;

// Esta clase representa una conexión a PDO en modo "Singleton".
// "Singleton" es un patrón de diseño que indica que de una determinada clase solo puede existir un sola instancia por vez.
use App\Env\Loader;
use PDO;
use PDOException;

class Connection
{
    /** @var PDO - Instancia singleton de PDO. */
    protected static $db;

    /**
     * Retorna la instancia "singleton" de PDO de la aplicación.
     *
     * @return PDO
     */
    public static function getConnection(): PDO
    {
        // Primero, preguntamos si tenemos una conexión.
        // De no tenerla, la creamos.
        // "self" es una keyword que hace referencia a la clase en la que estamos.
        if(self::$db === null) {
            try {
                // Leemos los datos de la conexión de las variables de entorno.
                $host = Loader::getValue('DB_HOST');
                $base = Loader::getValue('DB_NAME');
                $user = Loader::getValue('DB_USER');
                $pass = Loader::getValue('DB_PASS');
                self::$db = new PDO("mysql:host=" . $host . ";dbname=" . $base . ";charset=utf8mb4", $user, $pass);
//                echo "Conexión a la base de datos inicializada...<br>";
                // A partir de php 8+, cuando una consulta SQL falla por defecto lanza una PDOException.
                // En las versiones anteriores, por defecto php no imprimía nada, sino que "fallaba
                // silenciosamente".
                // Con esta instrucción, configuramos para que PDO siempre tire esta PDOException,
                // indistintamente de la versión de php.
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Ocurrió un problema al conectar con la base de datos :(";
                exit;
                // Redireccionar a o mostrar una pantalla de mantenimiento.
            }
        }

        // Finalmente, retornamos la conexión.
        return self::$db;
    }
}
