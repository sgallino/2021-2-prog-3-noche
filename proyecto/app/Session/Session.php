<?php

namespace App\Session;

/**
 * Maneja la sesión y sus datos.
 */
class Session
{
    /**
     * Inicia la sesión.
     */
    public static function start()
    {
        session_start();
    }

    /**
     * Almacena un valor en la sesión.
     *
     * @param string $key
     * @param $value
     */
    public static function set(string $key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Retorna el valor correspondiente a la $key, o null de no existir.
     *
     * @param string $key
     * @return mixed
     */
    public static function get(string $key)
    {
//        if(!self::has($key)) {
//            return null;
//        }
//
//        return $_SESSION[$key];

        return self::has($key) ?
            $_SESSION[$key] :
            null;
    }

    /**
     * Retorna si existe un valor para la clave.
     *
     * @param string $key
     * @return mixed
     */
    public static function has(string $key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Elimina el valor de la clave.
     *
     * @param string $key
     */
    public static function delete(string $key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Obtiene el valor asociado a la $key, si existe, y la elimina.
     * De lo contrario, retorna el $default indicado.
     *
     * @param string $key
     * @param null|mixed $default
     */
    public static function flash(string $key, $default = null)
    {
        if(!self::has($key)) {
            return $default;
        }

        $value = self::get($key);
        self::delete($key);
        return $value;
    }
}
