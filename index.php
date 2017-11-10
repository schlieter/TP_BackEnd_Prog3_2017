<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './Clases/AccesoDatos.php';
require_once './Clases/Vehiculo.php';
include_once './Clases/Empleado.php';
include_once './Clases/VehiculoDB.php';
include_once './Clases/EmpleadoDB.php';
include_once './Clases/MWparaAutentificar.php';
include_once './Clases/AutentificadorJWT.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);



/*VERIFICO Y LE DEVUELVO EL TOKEN*/
$app->post('/ingreso', function (Request $request, Response $response) {   
    return $response;
})->add(\MWparaAutentificar::class.':VerificarUsuario');



/* GRUPO EMPLEADO, solo tiene acceso el admin*/
$app->group('/empleado', function () {

    $this->get('[/]', function ($request, $response, $args) {
        $retorno = "";
        $empleados = Empleado::TodosLosEmpleados();

        foreach($empleados as $var){
            $retorno.=$var->ToString()."\n";/////////////////////////////////////////////////////////////////
            //echo($var->ToString()."\n");
        }
        //echo($retorno);
        $response->getBody()->write($retorno);
    });
    $this->post('/nuevo', function (Request $request, Response $response){
        $ArrayDeParametros = $request->getParsedBody();
        $empleado = new Empleado();

        $empleado->nombre= $ArrayDeParametros['nombre'];
        $empleado->apellido= $ArrayDeParametros['apellido'];
        $empleado->turno= $ArrayDeParametros['turno'];
        $empleado->mail= $ArrayDeParametros['mail'];
        $empleado->clave = $ArrayDeParametros["clave"];
        $empleado->ingreso = date("Y/m/d H:i:s");
        
        $rta = $empleado->Guardar();

        $response->getBody()->write($rta);
        return $response;
    });
    $this->put('/modificar',function(Request $request, Response $response){
        $ArrayDeParametros = $request->getParsedBody();

        $empleado = new Empleado();
        $empleado->nombre = $ArrayDeParametros['nombre'];
        $empleado->apellido = $ArrayDeParametros['apellido'];
        $empleado->mail = $ArrayDeParametros['mail'];
        $empleado->turno = $ArrayDeParametros['turno'];
        $empleado->clave = $ArrayDeParametros["clave"];
        $rta = $empleado->Modificar();

        $response->getBody()->write($rta);
        return $response;

    });
    $this->delete('/borrar',function(Request $request, Response $response){
        $ArrayDeParametros = $request->getParsedBody();
        $mail= $ArrayDeParametros['mail'];
        return Empleado::Borrar($mail);
    });

})->add(\MWparaAutentificar::class . ':VerificarTokenParaUsuarioAdmin');


/*GRUPO VEHICULO, tienen accesos todos los usuarios regristrados*/
$app->group('/vehiculo', function () {

    $this->get('[/]', function (Request $request, Response $response){
        $retorno = "";
        $estacionados = Vehiculo::TodosLosEstacionados();
        foreach($estacionados as $var){
            $retorno.=$var->ToString()."\n";
        }
        $response->getBody()->write($retorno);
    });
    $this->get('/historial', function (Request $request, Response $response){
        $retorno = "";
        $historial = Vehiculo::Historial();
        foreach($historial as $var){
            $retorno.=$var->ToString()."\n";
        }
        $response->getBody()->write($retorno);
    });

    $this->put('/modificar',function(Request $request, Response $response){
        $ArrayDeParametros = $request->getParsedBody();

        $autoModificar = new Vehiculo();
        $autoModificar->patente = $ArrayDeParametros['patente'];
        $autoModificar->empleadoIngreso = $ArrayDeParametros['mail'];
        $autoModificar->color = $ArrayDeParametros['color'];
        $autoModificar->marca = $ArrayDeParametros['marca'];
        $rta = $autoModificar->Modificar();

        $response->getBody()->write($rta);
        return $response;

    });

    $this->delete('/borrar',function(Request $request, Response $response){
        $ArrayDeParametros = $request->getParsedBody();
        $patente= $ArrayDeParametros['patente'];
        $mail= $ArrayDeParametros['mail'];
        $retorno = Vehiculo::Borrar($patente,$mail);
        $response->getBody()->write($retorno);
        return $response;
    });

    $this->post('/nuevo', function (Request $request, Response $response){
        $ArrayDeParametros = $request->getParsedBody();

        $auto = new Vehiculo();
        $auto->marca= $ArrayDeParametros['marca'];
        $auto->color= $ArrayDeParametros['color'];
        $auto->patente= $ArrayDeParametros['patente'];
        $auto->empleadoIngreso = $ArrayDeParametros['mail'];
        $auto->ingreso = date("Y/m/d H:i:s");
        $rta = $auto->Guardar();

        $response->getBody()->write($rta);
        
        return $response;
        /*$misCds = cd::TraerTodoLosCds();
        
        foreach($misCds as $var){
            if($var->titulo == $micd->titulo && $var->cantante == $micd->cantante){
                $id = $var->id;
                var_dump($id);
                break;
            }
        }

        $archivos = $request->getUploadedFiles();
        $destino="./fotos/";
        //var_dump($archivos);
        //var_dump($archivos['foto']);

        $nombreAnterior=$archivos['foto']->getClientFilename();
        $extension= explode(".", $nombreAnterior)  ;
        //var_dump($nombreAnterior);
        $extension=array_reverse($extension);

        $archivos['foto']->moveTo($destino.$id.$titulo.".".$extension[0]);
        $response->getBody()->write("cd");

        return $response;*/
    });

})->add(\MWparaAutentificar::class . ':VerificarToken');


$app->run();