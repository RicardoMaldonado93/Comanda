<?php
    require_once './entidades/clases/archivos/archivos.php';
    require_once './entidades/clases/archivos/FPDF/fpdf.php';

    class ArchivosApi{

        public static function ExportarDatos($request, $response, $args){

            $header = ['ID', 'usuario', 'nombre', 'apellido', 'puesto','estado'];
            $data = Personal::Mostrar();
        

            //var_dump(get_object_vars( Personal::MostrarRegistros()));

            $archivo =  PDF::LoadData($header, $data);
            
            if($archivo != Null){
                return $response;
		
		return $response;
            }
            else
                return $response->withJson(400);
        }
    }
?>