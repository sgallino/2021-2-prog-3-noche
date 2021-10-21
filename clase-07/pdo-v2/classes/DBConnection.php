<?php

// Esta clase representa una conexión a PDO en modo "Singleton".
// "Singleton" es un patrón de diseño que indica que de una determinada clase solo puede existir un sola instancia por vez.
class DBConnection
{
    // Los métodos y propiedades "static" son aquellos que pertenecen a la clase y no a las instancias de la clase.
    protected static $host = "127.0.0.1";
    protected static $user = "root";
    protected static $pass = "";
    protected static $base = "cwm_2020_2_n";

    /** @var \PDO - Instancia singleton de PDO. */
    protected static $db;

    /**
     * Retorna la instancia "singleton" de PDO de la aplicación.
     *
     * @return \PDO
     */
    public static function getConnection(): \PDO
    {
        // Primero, preguntamos si tenemos una conexión.
        // De no tenerla, la creamos.
        // "self" es una keyword que hace referencia a la clase en la que estamos.
        if(self::$db === null) {
            try {
                self::$db = new \PDO("mysql:host=" . self::$host . ";dbname=" . self::$base . ";charset=utf8mb4", self::$user, self::$pass);
                echo "Conexión a la base de datos inicializada...<br>";
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
