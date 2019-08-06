<?php
    require_once './entidades/clases/archivos/armarPDF.php';

    class ArchivosApi{

        public static function ExportarLog($request, $response, $args){

            $header = ['ID', 'usuario', 'metodo', 'ruta', 'fecha'];
            $data = Personal::MostrarLogs();
            $archivo = PDF::CrearPDF($header,$data,33, 33,'Registros_Log');
            
            if($archivo != Null)
                return $response;
            else
                return $response->withJson(400);
        }
        public static function ExportarRegistros($request, $response, $args){

            $header = ['ID', 'usuario', 'nombre', 'apellido', 'puesto', 'estado'];
            $data = Personal::Mostrar();
            $archivo = PDF::CrearPDF($header,$data,33, 33,'Registros_Empleados');
            
            if($archivo != Null)
                return $response;
            else
                return $response->withJson(400);
        }
        public static function ExportarMenu($request, $response, $args){

            $header = ['ID', 'usuario', 'metodo', 'ruta', 'fecha'];
            $data = Personal::MostrarRegistros();
            $archivo = PDF::CrearPDF($header,$data,33, 33,'Registros');
            
            if($archivo != Null)
                return $response;
            else
                return $response->withJson(400);
        }
        public static function ExportarPedidos($request, $response, $args){

            $header = ['ID', 'usuario', 'metodo', 'ruta', 'fecha'];
            $data = Personal::MostrarRegistros();
            $archivo = PDF::CrearPDF($header,$data,33, 33,'Registros');
            
            if($archivo != Null)
                return $response;
            else
                return $response->withJson(400);
        }
        public static function ExportarMesas($request, $response, $args){

            $header = ['ID', 'usuario', 'metodo', 'ruta', 'fecha'];
            $data = Personal::MostrarRegistros();
            $archivo = PDF::CrearPDF($header,$data,33, 33,'Registros');
            
            if($archivo != Null)
                return $response;
            else
                return $response->withJson(400);
        }

    }
?>