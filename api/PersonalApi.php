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

        $datos=$request->getParams();
        $empleado = Personal::ModificarEmpleado($datos['id'],$datos['nombre'],$datos['apellido'],$datos['puesto']);
        
        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);

    }

    public static function Eliminar($request, $response, $args){
        
        $datos=$request->getParams();
        $empleado = Personal::EliminarEmpleado($datos['id']);
        
        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);
    }

    public static function CambiarPuesto( $request, $response, $args){

        $datos = $response->getParsedBody();
        $empleado = Personal::ModificarPuesto($datos['id'],$datos['puesto']);

        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);
    }

    public static function CambiarEstado( $request, $response, $args){

        $datos = $response->getParsedBody();
        $empleado = Personal::ModificarEstado($datos['id'],$datos['estado']);

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

    public static function MostrarUno($request, $response, $args){

        $datos=$request->getParams();
        $empleado = Personal::MostrarX($datos['id']);
        
        if($empleado != NULL) 
            return $response->withJson($empleado, 200);
        else
            return $response->withStatus(400);
    }

}
?>