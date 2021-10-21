<?php

declare(strict_types=1);

require_once 'clases/Producto.php';

$producto = new Producto('Producto Nro 1');
$producto2 = new Producto('Producto Nro 2');
//$producto->nombre = 'Producto Nro 1';
//echo $producto->nombre;
$producto->setNombre('Producto Nro 001');
echo $producto->getNombre();

//var_dump($producto, $producto2);
