<?php

require_once './entidades/enums/estadoMesa.php';
require_once './entidades/clases/validaciones/validacion.php';

class Mesa {


    public static function ocuparMesa($nroMesa){

        try{
           
            $u = Validar::Existe($nroMesa,'mesa');
  
            if($u == 1 ){
                    
  

                    $v = Validar::Ver($nroMesa, 4,'mesa','estado');

                    if($v == true ){
                    
                        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado =:est WHERE id=:nro");
                        $consulta->bindValue(':nro', $nroMesa, PDO::PARAM_INT);
                        $consulta->bindValue(':est', EMesa::ClientesEsperando, PDO::PARAM_INT);
                        
                        if($consulta->execute()==true)
                            return 1;
                        
                        else
                            throw new PDOException("ERROR AL OCUPAR MESA");
                    }

                    else
                    {
                      throw new PDOException("LA MESA NO SE ENCUENTRA CERRADA",400);   
                    }
            }

            else
            {
                if($u == -1)
                    throw new PDOException("NO HAY MESAS REGISTRADAS",400);
                else 
                    throw new PDOException("NO EXISTE MESA",400);
            }
            
        }
        catch( PDOException $e){
            return  strtoupper($e->getMessage()) ; 
        }
    }

    public static function estadoMesa($nroMesa, $estado){

        try{
           
            $u = Validar::Existe($nroMesa,'mesa');
  
            if($u == 1 ){
                      
                        
                        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                        $consulta = $objetoAccesoDato->RetornarConsulta("UPDATE mesa SET estado =:est WHERE id=:nro");
                        $consulta->bindValue(':nro', $nroMesa, PDO::PARAM_INT);
                        $consulta->bindValue(':est', $estado, PDO::PARAM_INT);
                        
                        if($consulta->execute()==true)
                            return 1;
                        
                        else
                            throw new PDOException("ERROR AL CAMBIAR ESTADO MESA");
                    

            }

            else
            {
                if($u == -1)
                    throw new PDOException("NO HAY MESAS REGISTRADAS",400);
                else 
                    throw new PDOException("NO EXISTE MESA",400);
            }
            
        }
        catch( PDOException $e){
            return  strtoupper($e->getMessage()) ; 
        }
    }
}
?>