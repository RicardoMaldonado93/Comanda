<?php

require_once './entidades/interfaces/Ipersonal.php';
require_once './entidades/clases/personal/personal.php';

class PersonalApi extends Personal implements IPersonal{

    public static function Agregar($request, $response, $args){
        
        $datos= $request->getParsedBody();
        $empleado = Personal::AgregarEmpleado($datos['nombre'], $datos['apellido'],$datos['puesto']);

        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);

    }
    public static function Modificar($request, $response, $args){

    }
    public static function Eliminar($request, $response, $args){
        
        $empleado = Personal::EliminarEmpleado($args['id']);
        
        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);
    }

    public static function MostrarTodos($request, $response, $args){

        $empleado = Personal::Mostrar();

        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);
    }
    public static function MostrarUno($request, $response, $args){}

}
?>