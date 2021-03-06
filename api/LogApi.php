<?php

require_once './entidades/interfaces/ilog.php';
require_once './entidades/clases/log.php';

class LogApi extends Log implements ILog{

    public static function Registro($request, $response, $next){
       
        try{
             
            if($request->getHeader('token') != NULL){
                $token = $request->getHeader('token');
                $status = Token::VerificarToken($token[0]);

                if( $status ){
            
                     $payload =Token::ObtenerData($token[0]);
                     $usr = $payload[0]->{'usuario'};
                     $met = $request->getMethod();
                     $ruta = $request->getUri()->getPath();
                     $datos=$request->getParsedBody();
                    #Log::Registrar($datos['usuario'], $met, $ruta);
                     Log::Registrar($usr, $met, $ruta);

                }
            
            return $next($request,$response);
            }
            else
                throw new Exception('ERROR AL INTENTAR CARGAR TOKEN');
                
        }

        catch(Exception $e){

            return $response->withJson($e->getMessage(),400);

        }
    }
}
?>