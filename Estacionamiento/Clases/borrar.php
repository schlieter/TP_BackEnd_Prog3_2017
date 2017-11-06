<?php

header('Access-Control-Allow-Origin: *');

include_once "./VehiculoDB.php";


    $patente = $_POST["patente"];
  	
    
    $autoNuevo =  VehiculoDB::TraerUnVehiculo($patente);
    echo($autoNuevo);
    /*
    $autoNuevo->BorrarVehiculo();
    $autoNuevo->InsertarVehiculoHistorial();
*/

    echo("ok");