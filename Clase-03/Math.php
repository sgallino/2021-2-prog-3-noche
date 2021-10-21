<?php

class Math
{
    public static $pi = 3.1416;
    public static $key = "dksljdsa/YHS";
    public $num = 123;

    public static function saludar() {
        return 'Hola ' . self::$key;
    }

    public function mensaje() {
        return 'Mensaje';
    }
}

$math = new Math();
echo $math->num;
echo "\n";
echo $math->mensaje();
echo "\n";

echo Math::$pi;
echo "\n";
echo Math::saludar();