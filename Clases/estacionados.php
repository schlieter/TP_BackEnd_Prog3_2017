<?php
 header('Access-Control-Allow-Origin: *');

include_once "./Empleado.php";

$empleados = Empleado::TodosLosEmpleados();
foreach($empleados as $var){
    echo($var->ToString()."\n");
}
