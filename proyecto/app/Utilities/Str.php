<?php

namespace App\Utilities;

class Str
{
    /**
     * @param string $string
     * @param string $prefix
     * @return string
     */
    public static function prefixIfMissing(string $string, string $prefix): string
    {
        if(strpos($string, $prefix) !== 0) {
            $string = $prefix . $string;
        }
        return $string;
    }

    /**
     * @param string $string
     * @param string $suffix
     * @return string
     */
    public static function suffixIfMissing(string $string, string $suffix): string
    {
        // Buscamos desde el final la primer ocurrencia de este string, y no aseguramos de que la
        // posición coincida con la posición que debería tener el sufijo de estar presente.
        $position = strlen($string) - strlen($suffix);
        if(strrpos($string, $suffix) !== $position) {
            $string .= $suffix;
        }
        return $string;
    }

    /**
     * Genera un slug del string, amigable para URLs.
     *
     * "slug" es el término que usamos para strings que son todos en minúsculas, y que eliminan
     * cualquier caracter inválido para las URLs, y reemplazan espacios por "-".
     *
     * @param string $string
     * @return string
     */
    public static function sluggify(string $string): string
    {
        $search = ['ñ', 'á', 'é', 'í', 'ó', 'ú', ' ', '*', '%', '$', '#', '!', '?'];
        $replace = ['ni', 'a', 'e', 'i', 'o', 'u', '-', '', '', '', '', '', ''];
        return str_replace($search, $replace, $string);
    }
}
