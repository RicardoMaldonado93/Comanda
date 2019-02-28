<?php

require_once './entidades/clases/mesa.php';
require_once './entidades/interfaces/imesas.php';

class MesasApi extends Mesa implements IMesas{
    
    public static function MostrarMayorImporte($request, $response, $args){

        $importes = Mesa::mayorImporte();

        if($importes != null)

            return $response->withJson($importes, 200);
        
        else
            return $response->withJson($importes, 400);

    }

    public static function MostrarMesas($request, $response, $args){

        $lista = Mesa::verMesas();

        if($lista != NULL)
            return $response->withJson($lista,200);
        else
            return $response->withJson($lista,400);
    }
}
?>