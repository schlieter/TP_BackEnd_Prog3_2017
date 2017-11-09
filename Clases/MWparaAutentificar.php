<?php
//require_once "./Empleado.php";
require_once "AutentificadorJWT.php";
class MWparaAutentificar
{
 /**
   * @api {any} /MWparaAutenticar/  Verificar Usuario
   * @apiVersion 0.1.0
   * @apiName VerificarUsuario
   * @apiGroup MIDDLEWARE
   * @apiDescription  Por medio de este MiddleWare verifico las credeciales antes de ingresar al correspondiente metodo 
   *
   * @apiParam {ServerRequestInterface} request  El objeto REQUEST.
 * @apiParam {ResponseInterface} response El objeto RESPONSE.
 * @apiParam {Callable} next  The next middleware callable.
   *
   * @apiExample Como usarlo:
   *    ->add(\MWparaAutenticar::class . ':VerificarUsuario')
   */
	public function VerificarUsuario($request, $response, $next) {
		
		$objDelaRespuesta= new stdclass();
		$objDelaRespuesta->respuesta="";

		$ArrayDeParametros = $request->getParsedBody();
		//var_dump($ArrayDeParametros);

		$clave= $ArrayDeParametros['clave'];
		$mail= $ArrayDeParametros['mail'];

		$rta = Empleado::Ingreso($mail,$clave);
		if($rta == "Acceso permitido"){
			
			$datos = Empleado::TraerUno($mail,$clave);
			
			
;
			
			
			//$response->getBody()->write('<p>verifico credenciales</p>');

			//perfil=Profesor (GET, POST)
			//$datos = array('usuario' => 'rogelio@agua.com','perfil' => 'profe', 'alias' => "PinkBoy");
			
			//perfil=Administrador(todos)
			$datosToken = array('nombre' => $datos->nombre,'apellido' => $datos->apellido, 'turno' => $datos->turno, 'mail' => $datos->mail, 'ingreso' =>$datos->ingreso);

			$token= AutentificadorJWT::CrearToken($datosToken);

			//token vencido
			//$token="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0OTc1Njc5NjUsImV4cCI6MTQ5NzU2NDM2NSwiYXVkIjoiNGQ5ODU5ZGU4MjY4N2Y0YzEyMDg5NzY5MzQ2OGFhNzkyYTYxNTMwYSIsImRhdGEiOnsidXN1YXJpbyI6InJvZ2VsaW9AYWd1YS5jb20iLCJwZXJmaWwiOiJBZG1pbmlzdHJhZG9yIiwiYWxpYXMiOiJQaW5rQm95In0sImFwcCI6IkFQSSBSRVNUIENEIDIwMTcifQ.GSpkrzIp2UbJWNfC1brUF_O4h8PyqykmW18vte1bhMw";

			//token error
			//$token="octavioAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE0OTc1Njc5NjUsImV4cCI6MTQ5NzU2NDM2NSwiYXVkIjoiNGQ5ODU5ZGU4MjY4N2Y0YzEyMDg5NzY5MzQ2OGFhNzkyYTYxNTMwYSIsImRhdGEiOnsidXN1YXJpbyI6InJvZ2VsaW9AYWd1YS5jb20iLCJwZXJmaWwiOiJBZG1pbmlzdHJhZG9yIiwiYWxpYXMiOiJQaW5rQm95In0sImFwcCI6IkFQSSBSRVNUIENEIDIwMTcifQ.GSpkrzIp2UbJWNfC1brUF_O4h8PyqykmW18vte1bhMw";
	
			//tomo el token del header
			/*
				$arrayConToken = $request->getHeader('token');
				$token=$arrayConToken[0];			
			*/
			//var_dump($token);
			$objDelaRespuesta->esValido=true; 
			try 
			{
				//$token="";
				AutentificadorJWT::verificarToken($token);
				$objDelaRespuesta->esValido=true;      
			}
			catch (Exception $e) {      
				//guardar en un log
				$objDelaRespuesta->excepcion=$e->getMessage();
				$objDelaRespuesta->esValido=false;     
			}

			if($objDelaRespuesta->esValido)
			{						
				if($request->isPost())
				{
					$response = $next($request, $response);
					$response->getBody()->write($token);  
				}
				else
				{
					$objDelaRespuesta->respuesta="Solo por POST";
				}
			}    
			else////hace falta si ya valido que este registrado en el primer if?????
			{
				$objDelaRespuesta->respuesta="Solo usuarios registrados";
				$objDelaRespuesta->elToken=$token;

			}  
		}		  
		if($objDelaRespuesta->respuesta=="" && $rta == "Acceso denegado")
		{	$objDelaRespuesta->respuesta = "El usuario o la clave no es valido";
			$nueva=$response->withJson($objDelaRespuesta, 401);
			$response->getBody()->write($nueva);  
			return $nueva;
		}
		 return $response;   
	}

	public static function VerificarToken($request, $response, $next){

		$arrayConToken = $request->getHeader('miToken');
        $token=$arrayConToken[0];
    
        try {
    
          AutentificadorJWT::VerificarToken($token);
        
          $response->getBody()->write(" PHP :Su token es ".$token);  
		  //$respuesta=usuario::Traertodos();   
		  //$newResponse = $next($request,$newresponse); 
		  //$newResponse = $response->withJson($respuesta);
		  $newResponse = $next($request,$response);
    
        } catch (Exception $e) {
    
          $textoError="error ".$e->getMessage();
          $error = array('tipo' => 'acceso','descripcion' => $textoError);
          $newResponse = $response->withJson( $error , 403); 
    
        }
        return $newResponse;
	}//
	public static function VerificarTokenParaUsuarioAdmin($request, $response, $next){
		$arrayConToken = $request->getHeader('miToken');
		$token=$arrayConToken[0];
		try{
			$datos = AutentificadorJWT::ObtenerData($token);
			if($datos->mail == "admin@utn.com.ar"){
				$newResponse = $next($request,$response);
			}
			else{
				$error = array('tipo' => 'acceso','descripcion' => 'no contiene permiso de administrador');			
				$newResponse = $response->withJson( $error , 403);
			}
		}
		catch(Exception $e){
			$textoError="error ".$e->getMessage();
			$error = array('tipo' => 'acceso','descripcion' => $textoError);			
			$newResponse = $response->withJson( $error , 403);
		}
		return $newResponse;
	}
}

//--------------------------APLICAR EXCEPCIONES AL METOODO DE ARRIBA