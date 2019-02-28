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
            return  array('msg'=>strtoupper($e->getMessage()), 'type'=>'error') ; 
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
            return  array('msg'=>strtoupper($e->getMessage()), 'type'=>'error') ; 
        }
    }

    public static function mayorImporte(){
        try{
        
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT total, mesa.codigo FROM (SELECT total, m.id, p.mesa
                                        FROM pedido p, mesa m where m.id = p.mesa) pedido, mesa where mesa.id = pedido.mesa
                                        GROUP BY total DESC LIMIT 5");

                if($consulta->execute()==true)
                    return $consulta->fetch();
                            
        }
        catch( PDOException $e){
            return  array('msg'=>strtoupper($e->getMessage()), 'type'=>'error') ; 
        }

    }

    public static function verMesas(){
        try{
                
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM mesa ");
                
                if($consulta->execute()==true)
                    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }
        catch( PDOException $e){
            return  array('msg'=>strtoupper($e->getMessage()), 'type'=>'error') ; 
        }
    }

  /*  public static function verMasUsada(){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM mesa ");
            
            if($consulta->execute()==true)
                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
        }
    }*/
}
?>