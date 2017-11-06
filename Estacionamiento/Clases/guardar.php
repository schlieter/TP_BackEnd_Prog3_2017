<?php
 header('Access-Control-Allow-Origin: *');

include_once "./Vehiculo.php";

$autoNuevo = new Vehiculo();

    $autoNuevo->patente = $_POST["patente"];
    $autoNuevo->empleadoIngreso = $_POST["email"];
    $autoNuevo->color = $_POST["color"];
    $autoNuevo->marca = $_POST["marca"];
    $autoNuevo->ingreso = date("Y/m/d H:i:s");

    $rta = $autoNuevo->Guardar();
    echo($rta);

    



