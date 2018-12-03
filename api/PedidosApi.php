<?php

require_once './entidades/clases/pedidos.php';
require_once './entidades/interfaces/IPedidos.php';

class PedidosApi extends Pedidos implements IPedidos{

    public static function TomarPedido( $request, $response, $args){

        //ID, cliente, mesa , mozo, pedido, cantidad, estado, total, fechaInicio

        $datos = $request->getParsedBody();
        $pedido = $datos['pedido'];
        $mozo = $datos['mozo'];
        $mesa =$datos['mesa'];
        $cant = $datos['cantidad'];
        $cliente = $datos['cliente'];
        
        $orden = Pedidos::cargarPedido($mesa, $mozo, $pedido, $cant, $cliente);

        if($orden != NULL) 
            return $response->withJson($orden, 200);
        else
            return $response->withStatus(400);

    }
}
?>