<?php
header('Access-Control-Allow-Origin: *');

include_once "./Vehiculo.php";

    $autoModificar = new Vehiculo();
    $autoModificar->patente = $_POST["patente"];
    $autoModificar->empleadoIngreso = $_POST["email"];
    $autoModificar->color = $_POST["color"];
    $autoModificar->marca = $_POST["marca"];
    $autoModificar->Modificar();