<?php

require_once './entidades/clases/cliente.php';
require_once './entidades/interfaces/icliente.php';

class ClienteApi extends Cliente implements ICliente{

    public static function VerMiPedido( $request, $response, $args){
            
        try{
                $datos = $request->getParams();
                $pedido = Cliente::verEstadoPedido($datos['codigo'],$datos['mesa']);

                if($pedido != NULL) 
                    return $response->withJson($pedido, 200);
                else
                    return $response->withStatus(400);
                    
        }
        catch(Exception $e){
            return $response->withJson($e->getMessage(), 400);
        }

    }

    public static function MostrarEncuesta($request, $response, $args){
        try{
            $datos = $request->getParsedBody();
            $encuesta = Cliente::Encuesta($datos['codigo'],$datos['calMesa'], $datos['calResto'], $datos['calMozo'], $datos['calCocinero'], $datos['opinion']);

            if( $encuesta != NULL)
                return $response->withJson($encuesta, 200);
            else 
                return $response->withJson($encuesta, 400);
        }
        catch(Exception $e){
            return $response->withJson($e->getMessage(),400);
        }
    }
}
?>