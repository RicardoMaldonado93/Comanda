<?php

interface IPersonal{

    public static function Agregar($request, $response, $args);
    public static function Modificar($request, $response, $args);
    public static function Eliminar($request, $response, $args);
    public static function MostrarTodos($request, $response, $args);
    public static function MostrarUno($request, $response, $args);

}
?>