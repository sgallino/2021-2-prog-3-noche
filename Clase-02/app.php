<?php

//require_once 'clases/Moto.php';
require_once 'clases/Auto.php';
require_once 'clases/Bici.php';

$bici = new Bici(2);

$camion = new Camion(12, 5000);
echo $camion->detalle();
// El camion tiene 12 ruedas y puede cargas 5 tonelas

//$moto = new Moto(2);
//echo $moto->detalle();

$auto = new Auto(4, 3); // parametros ruedas y puertas
echo $auto->detalle();
// El Auto tiene 4 ruedas y 5 puertas

//var_dump($moto);