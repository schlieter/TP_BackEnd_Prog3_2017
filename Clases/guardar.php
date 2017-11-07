<?php
 header('Access-Control-Allow-Origin: *');

include_once "./Empleado.php";

$empleado = new Empleado();

    $empleado->nombre = $_POST["nombre"];
    $empleado->apellido = $_POST["apellido"];
    $empleado->mail = $_POST["mail"];
    $empleado->clave = $_POST["clave"];
    $empleado->turno = $_POST["turno"];    
    $empleado->ingreso = date("Y/m/d H:i:s");

    $rta = $empleado->Guardar();
    echo($rta);

    



