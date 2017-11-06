<?php
 header('Access-Control-Allow-Origin: *');

include_once "./Vehiculo.php";

$estacionados = Vehiculo::TodosLosEstacionados();
foreach($estacionados as $var){
    echo($var->patente."\n");
}
