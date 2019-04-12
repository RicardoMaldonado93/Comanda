<?php

interface IMesas {
    
    public static function MostrarMayorImporte($request, $response,$args);
    public static function MostrarMenorImporte($request, $response, $args);
    public static function MostrarMayorFacturacion($request, $response, $args);
    public static function MostrarMenorFacturacion($request, $response, $args);
    public static function MostrarMasUsada($request, $response, $args);
    public static function MostrarMenosUsada($request, $response, $args);
    public static function MostrarMayorCalificacion($request, $response, $args);
    public static function MostrarMenorCalificacion($request, $response, $args);
    public static function MostrarMesas($request, $response, $args);
}
?>