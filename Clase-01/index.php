<?php

require_once 'clases/Persona.php';

$juan = new Persona('Juan', 'Perez', 33);
$maria = new Persona('Maria', 'Garcia', 25);

//$juan->nombre = 'Juan';
//$juan->setNombre('Juan');
//$juan->apellido = 'Perez';
$juan->setApellido('PeRez GarCIa');
$juan->setEdad(33);

echo $juan->getApellido();
//echo $juan->getEdad();
//echo $juan->nombreCompleto();
//echo $juan->nombre . ' ' . $juan->apellido;
//echo "$juan->nombre $juan->apellido";
//var_dump($juan);

//echo trim('   Hola mundo   ');

