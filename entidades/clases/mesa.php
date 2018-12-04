<?php

require_once './entidades/enums/estadoMesa.php';

class Mesa {


    public static function ocuparMesa($nroMesa){

        try{

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado VALUES (:est) WHERE ID=:nro");
            $consulta->bindValue(':nro', $nroMesa, PDO::PARAM_INT);
            $consulta->bindValue(':est', EMesa::ClientesEsperando, PDO::PARAM_INT);
            
            if($consulta->execute()==true)
                return "---------> SE AGREGO CORRECTAMENTE EL EMPLEADO <---------";
            
            else
               throw new PDOException("ERROR AL AGREGAR EL EMPLEADO");
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . $consulta->errorCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }
}
?>