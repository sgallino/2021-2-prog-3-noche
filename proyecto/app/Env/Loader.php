<?php
namespace App\Env;

use App\Utilities\Str;

/**
 * Administrador de las variables de entorno.
 */
class Loader
{
    /**
     * @var string
     */
    protected static $envFile;

    /**
     * @var array
     */
    protected static $variables = [];

    /**
     * @param string $path
     * @param string $filename
     */
    public static function cargar(string $path, string $filename = ".env")
    {
        self::$envFile = $filename;
        $path = Str::suffixIfMissing($path, DIRECTORY_SEPARATOR);

        // file() levanta el contenido de un archivo de texto como un array, separando cada línea
        // en una posición del array.
        $fileContent = file($path . self::$envFile);

        foreach($fileContent as $line) {
            // Quitamos los espacios al comienzo y el final.
            $line = trim($line);
            // Nos aseguramos de que la línea no sea un comentario o una línea vacía.
            if($line !== "" && strpos($line, '#') !== 0) {
                // Separamos el nombre de la variable de su valor a partir del primer "=".
                $equalPos = strpos($line, "=");
                $varName = substr($line, 0, $equalPos);
                $value = substr($line, $equalPos + 1);
                // Pusheamos ese valor a nuestro array de valores.
                self::$variables[$varName] = $value;
            }
        }
    }

    /**
     * El valor de entorno para la $variable indicada.
     * Si no existe, se retorna el $default.
     *
     * @param string $variable
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function getValue(string $variable, $default = null)
    {
        return self::$variables[$variable] ?? $default;
    }
}
