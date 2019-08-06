<?php
    require_once './entidades/clases/archivos/armarPDF.php';

    class ArchivosApi{

        public static function ExportarDatos($request, $response, $args){

            $header = ['ID', 'usuario', 'metodo', 'ruta', 'fecha'];
            $data = Personal::MostrarRegistros();
        

            //var_dump(get_object_vars( Personal::MostrarRegistros()));

            $archivo = PDF::CrearPDF($header,$data,33, 33,'Registros');
            
            if($archivo != Null)
                return $response;
            else
                return $response->withJson(400);
        }
    }
?>