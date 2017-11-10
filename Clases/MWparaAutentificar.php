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
		$clave= $ArrayDeParametros['clave'];
		$mail= $ArrayDeParametros['mail'];

		$rta = Empleado::Ingreso($mail,$clave);

		if($rta == "Acceso permitido"){
			
			$datos = Empleado::TraerUno($mail,$clave);
			$datosToken = array('nombre' => $datos->nombre,'apellido' => $datos->apellido, 'turno' => $datos->turno, 'mail' => $datos->mail, 'ingreso' =>$datos->ingreso);
			$token= AutentificadorJWT::CrearToken($datosToken);

			$objDelaRespuesta->esValido=true; 
			try 
			{
				AutentificadorJWT::verificarToken($token); 
				$objDelaRespuesta->esValido=true;
			}
			catch (Exception $e) {      
				$objDelaRespuesta->excepcion=$e->getMessage();
				$objDelaRespuesta->esValido=false;
				$response->getBody()->write($objDelaRespuesta->excepcion);  
			}
			if($objDelaRespuesta->esValido)
			{						
				$response = $next($request, $response);		
				$tokenJson= array('respuesta' => 'Bienvenido al Estacionamiento!','miToken' => $token);
				$rta = JSON_encode($tokenJson);
				$response->getBody()->write($rta);
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
        try{
			AutentificadorJWT::VerificarToken($token);
			$newResponse = $next($request,$response);
		}
		catch (Exception $e){
          $textoError="error ".$e->getMessage();
          $error = array('tipo' => 'acceso','descripcion' => $textoError);
          $newResponse = $response->withJson( $error , 403); 
        }
        return $newResponse;
	}


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
