<?php  header('Access-Control-Allow-Origin: *');


include_once "./estacionamiento.php";

	$email = $_POST["email"];
    $clave = $_POST["clave"];



	$mensaje = estacionamiento::Ingreso($email,$clave);
	echo($mensaje);

