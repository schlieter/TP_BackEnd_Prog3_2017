<?php

header('Access-Control-Allow-Origin: *');

include_once "./Vehiculo.php";


    $patente = $_POST["patente"];
    $empleado = $_POST["email"];

    Vehiculo::borrar($patente,$empleado);


