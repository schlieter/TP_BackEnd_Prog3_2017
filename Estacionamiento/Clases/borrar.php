<?php

header('Access-Control-Allow-Origin: *');

include_once "./Empleado.php";


    $usuario = $_POST["usuario"];
    $mail = $_POST["mail"];

    $rta = Empleado::borrar($mail,$usuario);
    echo($rta);


