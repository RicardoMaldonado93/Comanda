<?php

require_once './entidades/clases/pedidos.php';
require_once './entidades/interfaces/IPedidos.php';

class PedidosApi extends Pedidos implements IPedidos{
    
    public static function TomarPedido( $request, $response, $args){

        $datos = $request->getParsedBody();
        $pedido = json_decode($datos['pedido']);
        $mozo = $datos['mozo'];
        $mesa =$datos['mesa'];
        $cliente = $datos['cliente'];

        $orden = Pedidos::cargarPedido($mesa, $mozo, $pedido, $cliente);

        if($orden != NULL) 
            return $response->withJson($orden, 200);
        else
            return $response->withStatus(400);

     }

    
     public static function Preparar( $request, $response, $args){

        $datos = $request->getParams();
        $pedido = Pedidos::prepararPedido($datos['id'],$datos['codigo'],$datos['demora']);

        if($pedido != NULL) 
            return $response->withJson($pedido, 200);
        else
            return $response->withStatus(400);
     }

    public static function Cancelar( $request, $response, $args){

        $datos = $request->getParams();
        $pedido = Pedidos::cancelarPedido($datos['codigo'], $datos['sector']);
        
        if($pedido != NULL) 
            return $response->withJson($pedido, 200);
        else
            return $response->withStatus(400);

     }

    public static function ListoParaServir( $request, $response, $args ){
        
        $datos = $request->getParams();
        $pedido = Pedidos::pedidoListo($datos['id'], $datos['codigo'], $datos['sector']);

        if($pedido != NULL) 
            return $response->withJson($pedido, 200);
        else
            return $response->withStatus(400);
     }

    public static function Entregar( $request, $response, $args){
        
        $datos = $request->getParams();
        $pedido = Pedidos::entregarPedido($datos['codigo']);

        if($pedido != NULL) 
            return $response->withJson($pedido, 200);
        else
            return $response->withStatus(400);

     }

    public static function MostrarPedido( $request, $response, $args){

        $pedido = Pedidos::traerPedido($args['id']);

        if($pedido != NULL) 
            return $response->withJson($pedido, 200);
        else
            return $response->withStatus(400);
     }

    public static function MostrarPedidos( $request, $response, $args){

        $pedido = Pedidos::traerPedidos();

        if($pedido != NULL) 
            return $response->withJson($pedido, 200);
        else
            return $response->withStatus(400);
     }

    public static function MostrarEstado( $request, $response, $args){

       try{ 
            switch($args['es']){
                case 'pendientes': {$pedido = Pedidos::traerEstado(1); break;}
                case 'enPreparacion': {$pedido = Pedidos::traerEstado(2); break;}
                case 'listos': {$pedido = Pedidos::traerEstado(3); break;}
                case 'cancelados': {$pedido = Pedidos::traerEstado(4); break;}

                default : { throw new Exception("ERROR DE RUTA!<br><br>rutas disponibles:<br> *pendientes<br>*enPreparacion<br>*listos<br>*cancelados ", 404); }
            }
            if($pedido != NULL) 
                return $response->withJson($pedido, 200);
            else
                return $response->withStatus(400);
        }
        catch(Exception $e){
            return $response->withJson($e->getMessage(), 404);
        }
     }

    public static function MostrarSector( $request, $response, $args){

        try{ 
             switch($args['se']){
                 case 'cocina': {$pedido = Pedidos::traerSector(2); break;}
                 case 'bartender': {$pedido = Pedidos::traerSector(3); break;}
                 case 'cerveceria': {$pedido = Pedidos::traerSector(1); break;}
                 case 'candybar': {$pedido = Pedidos::traerSector(4); break;}
 
                 default : { throw new Exception("ERROR DE RUTA!<br><br>rutas disponibles:<br> *cocina<br>*bartender<br>*cerveceria<br>*candybar ", 404); }
             }
             if($pedido != NULL) 
                 return $response->withJson($pedido, 200);
             else
                 return $response->withStatus(400);
         }
         catch(Exception $e){
             return $response->withJson($e->getMessage(), 404);
         }
     }

    public static function VerMiPedido( $request, $response, $args){
        
        try{
                $datos = $request->getParams();
                $pedido = Pedidos::verEstadoPedido($datos['codigo'],$datos['mesa']);

                if($pedido != NULL) 
                    return $response->withJson($pedido, 200);
                else
                    return $response->withStatus(400);
                    
        }
        catch(Exception $e){
            return $response->withJson($e->getMessage(), 200);
        }

    }
}

?>