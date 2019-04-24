<?php

require_once './entidades/clases/Token.php';

class MWAuth
{
 	public static function VerificarUsuario($request, $response, $next) {
         
		$objDelaRespuesta= new stdclass();
        $objDelaRespuesta->respuesta="";
        $objDelaRespuesta->esValido=true;
	   
		if($request->isGet())
		{
		// $response->getBody()->write('<p>NO necesita credenciales para los get </p>');
		 $response = $next($request, $response);
		}
		else
		{
			$arrayConToken = $request->getHeader('token');
			$token=$arrayConToken[0];			
			
			//var_dump($token);
			$objDelaRespuesta->esValido=true; 
			try 
			{				
				$objDelaRespuesta->esValido=Token::VerificarToken($token); 
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
					// el post sirve para todos los logeados			    
					$response = $next($request, $response);
				}
				else
				{
					$response = $next($request, $response);
					/*
					$payload=AutJWT::ObtenerData($token);
					//var_dump($payload);
					// DELETE,PUT y DELETE sirve para todos los logeados y admin
					if($payload->perfil=="Administrador")
					{
						$response = $next($request, $response);
					}		           	
					else
					{	
						$objDelaRespuesta->respuesta="Solo administradores";
					}
					*/
			    }		          
			}    
			else
			{
				//   $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
				$objDelaRespuesta->respuesta="Solo usuarios registrados";
				$objDelaRespuesta->elToken=$token;
			}  
        }
		if($objDelaRespuesta->respuesta!="")
		{
			$nueva=$response->withJson($objDelaRespuesta, 401);  
			return $nueva;
		}
		  
		 //$response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
         return $response;  
         
    }

    public static function Auth( $request, $response, $next){

        try{

            $token = $request->getHeader('token');
            $status = Token::VerificarToken($token[0]);
			
                if( $status ){

                    $payload =Token::ObtenerData($token[0]);

                    if(strtolower($payload[0]->{'estado'}) != 'suspendido' and strtolower($payload[0]->{'estado'}) != 'inactivo')
							
						return $next($request,$response);

                    else
                    {
                        if(strtolower($payload[0]->{'estado'}) == 'suspendido')
                            return $response->withJson(array('msg'=>'SU CUENTA SE ENCUENTRA SUSPENDIDA, COMUNIQUESE CON EL ADMINISTRADOR','type'=>'error'),401);
                        else
                            return $response->withJson(array('msg'=>'SU CUENTA SE ENCUENTRA INACTIVA, COMUNIQUESE CON EL ADMINISTRADOR', 'type'=>'error'),401);
                    }
                }
                
        }
        catch( Exception $e){
            return $response->withJson($e->getMessage(),401);
        }
	}
	
	public static function Admin( $request, $response, $next){

		try{
				$token = $request->getHeader('token');
				$status = Token::VerificarToken($token[0]);
				$payload = Token::ObtenerData($token[0]);

				if(strtolower($payload[0]->{'estado'}) != 'suspendido' and strtolower($payload[0]->{'estado'}) != 'inactivo'){
					if($payload[0]->{'puesto'} == 'Socio')
						return $next($request,$response);
					else
						return $response->withJson(array('msg'=>'ACCESO RESTRINGIDO','type'=>'error') , 401);
					}
				else
				{
					if(strtolower($payload[0]->{'estado'}) == 'suspendido')
						return $response->withJson(array('msg'=>'SU CUENTA SE ENCUENTRA SUSPENDIDA, COMUNIQUESE CON EL ADMINISTRADOR','type'=>'error'),401);
					else
						return $response->withJson(array('msg'=>'SU CUENTA SE ENCUENTRA INACTIVA, COMUNIQUESE CON EL ADMINISTRADOR','type'=>'error'),401);
				}

		}
		catch( Exception $e){
			return $response->withJson($e->getMessage(),401);
		}
	}
}