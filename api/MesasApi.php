<?php

require_once './entidades/clases/mesa.php';
require_once './entidades/interfaces/imesas.php';

class MesasApi extends Mesa implements IMesas{
    
    public static function MostrarMayorImporte($request, $response, $args){

        $importes = Mesa::Importe('DESC');

        if($importes != null)

            return $response->withJson($importes, 200);
        
        else
            return $response->withJson($importes, 400);

    }

    public static function MostrarMenorImporte($request, $response, $args){

        $importes = Mesa::Importe('ASC');

        if($importes != null)

            return $response->withJson($importes, 200);
        
        else
            return $response->withJson($importes, 400);

    }

    public static function MostrarMayorFacturacion($request, $response, $args){
        
        $facturacion = Mesa::Facturacion('DESC');

        if($facturacion != NULL)

            return $response->withJson($facturacion, 200);

        else

            return $response->withJson($facturacion, 400);
    }

    public static function MostrarMenorFacturacion($request, $response, $args){
        
        $facturacion = Mesa::Facturacion('ASC');

        if($facturacion != NULL)

            return $response->withJson($facturacion, 200);

        else

            return $response->withJson($facturacion, 400);
    }

    public static function MostrarMasUsada($request, $response, $args){

        $mesa = Mesa::Uso('DESC');

        if($mesa != NULL)
            return $response->withJson($mesa, 200);
        else
            return $reponse->withJson($mesa, 400);
    }

    public static function MostrarMenosUsada($request, $response, $args){

        $mesa = Mesa::Uso('ASC');

        if($mesa != NULL)
            return $response->withJson($mesa, 200);
        else
            return $reponse->withJson($mesa, 400);
    }

    public static function MostrarMayorCalificacion($request, $response, $args){
        $mayorCalificacion = Mesa::Calificacion('DESC');

        if($mayorCalificacion != NULL)
            return $response->withJson($mayorCalificacion, 200);
        else
            return $response->withJson($mayorCalificacion,400);
    }

    public static function MostrarMenorCalificacion($request, $response, $args){
        $menorCalificacion = Mesa::Calificacion('ASC');

        if($menorCalificacion != NULL)
            return $response->withJson($menorCalificacion, 200);
        else
            return $response->withJson($menorCalificacion,400);
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