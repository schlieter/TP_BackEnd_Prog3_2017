<?php
header('Access-Control-Allow-Origin: *');

include_once "./Empleado.php";

    $empleado = new Empleado();
    $empleado->nombre = $_POST["nombre"];
    $empleado->apellido = $_POST["apellido"];
    $empleado->mail = $_POST["mail"];
    $empleado->turno = $_POST["turno"];
    $empleado->clave = $_POST["clave"];
    $usuario = $_POST["usuario"];
    
    
    $empleado->Modificar($usuario);