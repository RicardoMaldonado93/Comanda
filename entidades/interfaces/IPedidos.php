<?php

interface IPedidos{

    public static function TomarPedido($request,$response,$args);
    public static function Cancelar( $request, $response, $args);
    public static function ListoParaServir( $request, $response, $args);
    public static function Entregar( $request, $response, $args);
    public static function AgregarAPedido( $request, $response, $args);
    public static function MostrarPedido( $request, $response, $args);
    public static function MostrarPedidos( $request, $response, $args);
    public static function MostrarEstado( $request, $response, $args);
    public static function MostrarSector( $request, $response, $args);

}
?>