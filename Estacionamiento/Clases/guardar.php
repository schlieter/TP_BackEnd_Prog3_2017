<?php
 header('Access-Control-Allow-Origin: *');

include_once "./VehiculoDB.php";


    $patente = $_POST["patente"];
    $email = $_POST["email"];
    $color = $_POST["color"];
    $marca = $_POST["marca"];

    $autoNuevo = new VehiculoDB($patente,$marca,$color,$email);

    $autoNuevo->InsertarVehiculo();

    echo("ok");
    



