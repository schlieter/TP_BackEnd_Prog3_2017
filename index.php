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

/*

¡La primera línea es la más importante! A su vez en el modo de 
desarrollo para obtener información sobre los errores
 (sin él, Slim por lo menos registrar los errores por lo que si está utilizando
  el construido en PHP webserver, entonces usted verá en la salida de la consola
  que es útil).

  La segunda línea permite al servidor web establecer el encabezado Content-Length, 
  lo que hace que Slim se comporte de manera más predecible.
*/

$app = new \Slim\App(["settings" => $config]);

//require_once "saludo.php";




$app->post('/ingreso', function (Request $request, Response $response) {   
    $response->getBody()->write("Bienvenido! ,al Estacionamiento");
    return $response;

})->add(\MWparaAutentificar::class . ':VerificarUsuario');




$app->get('/datos/', function (Request $request, Response $response) {     
    $datos = array('nombre' => 'rogelio','apellido' => 'agua', 'edad' => 40);
    $newResponse = $response->withJson($datos, 200);  
    return $newResponse;
});

$app->post('/datos/', function (Request $request, Response $response) {    
    $ArrayDeParametros = $request->getParsedBody();
   // var_dump($ArrayDeParametros);
    $objeto= new stdclass();
    $objeto->nombre=$ArrayDeParametros['nombre'];
    $objeto->apellido=$ArrayDeParametros['apellido'];
    $objeto->edad=$ArrayDeParametros['edad'];
    $newResponse = $response->withJson($objeto, 200);  
    return $newResponse;

});

/* atender todos los verbos de HTTP*/
$app->any('/cualquiera/[{id}]', function ($request, $response, $args) {
    
    var_dump($request->getMethod());
    $id=$args['id'];
    $response->getBody()->write("cualquier verbo de ajax parametro: $id ");
    return $response;
});



/* atender algunos los verbos de HTTP*/
$app->map(['GET', 'POST'], '/mapeado', function ($request, $response, $args) {

      var_dump($request->getMethod());
     $response->getBody()->write("Solo POST y GET");
});


/* agrupacion de ruta*/
$app->group('/saludo', function () {

    $this->get('/{nombre}', function ($request, $response, $args) {
        $nombre=$args['nombre'];
        $response->getBody()->write("HOLA, Bienvenido <h1>$nombre</h1> a la apirest de 'CDs'");
    });

     $this->get('/', function ($request, $response, $args) {
        $response->getBody()->write("HOLA, Bienvenido a la apirest de 'CDs'... ingresá tu nombre");
    });
 
     $this->post('/', function ($request, $response, $args) {      
        $response->getBody()->write("HOLA, Bienvenido a la apirest por post");
    });
     
});


/* agrupacion de ruta y mapeado*/
$app->group('/empleado', function () {

    /*$this->map(['POST', 'DELETE'], '', function ($request, $response, $args) {
        $response->getBody()->write("Borro el usuario por p");
    });*/

    $this->get('[/]', function ($request, $response, $args) {
        $empleados = Empleado::TodosLosEmpleados();
        foreach($empleados as $var){
            echo($var->ToString()."\n");
        }    
    });
    $this->delete('/borrar',function(Request $request, Response $response){///OK
        $ArrayDeParametros = $request->getParsedBody();
        $mail= $ArrayDeParametros['mail'];
        return Empleado::Borrar($mail);
       
    
    });
})->add(\MWparaAutentificar::class . ':VerificarTokenParaUsuarioAdmin');// SOLO INGRESA A ESTA SESION EL ADMIN


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/*LLAMADA A METODOS DE INSTANCIA DE UNA CLASE*/
$app->group('/vehiculo', function () {

$this->get('[/]', function (Request $request, Response $response){
    $estacionados = Vehiculo::TodosLosEstacionados();
    foreach($estacionados as $var){
        echo($var->ToString()."\n");
    }
});

$this->delete('/',function(Request $request, Response $response){///OK
    $ArrayDeParametros = $request->getParsedBody();
    $patente= $ArrayDeParametros['patente'];
    $mail= $ArrayDeParametros['mail'];
    return Vehiculo::Borrar($patente,$mail);
   

});

    

$this->put('/', \cd::class . ':ModificarUno');
//se puede tener funciones definidas
/*SUBIDA DE ARCHIVO*/
$this->post('/nuevo', function (Request $request, Response $response){
  
    
    $ArrayDeParametros = $request->getParsedBody();
    //var_dump($ArrayDeParametros);
    $marca= $ArrayDeParametros['marca'];
    $color= $ArrayDeParametros['color'];
    $patente= $ArrayDeParametros['patente'];
    $mail= $ArrayDeParametros['mail'];


    $auto = new Vehiculo();
    $auto->marca= $marca;
    $auto->color= $color;
    $auto->patente= $patente;
    $auto->empleadoIngreso = $mail;
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