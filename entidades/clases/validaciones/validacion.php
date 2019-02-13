<?php

class Validar{

    public static function Existe($codigo, $tabla){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM ". $tabla ." WHERE id = :id");
            $verificar->bindValue(':id', $codigo, PDO::PARAM_INT);
            $verificar->execute();
            $band;
            
  
            if( intval($verificar->fetchColumn()) == 1)
                $band = 1;
            else{
                if($verificar->fetchColumn() == 0 ){
                    $v= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM ". $tabla);
                    $v->execute();
                    if($v->fetchColumn()!=0)
                        $band = 0;
                    else
                        $band = -1;
                       
                }
               
            }   
            return $band;
        }
        catch(PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');  
        }
    }

    public static function Ver($id, $param, $tabla, $columna){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM ". $tabla ." WHERE " . $columna . "= :par AND id=:id");
            $verificar->bindValue(':id', $id, PDO::PARAM_INT);
            $verificar->bindValue(':par', $param, PDO::PARAM_INT);
            $verificar->execute();

            if(intval($verificar->fetchColumn()) == 1)
                return true;
            else
                return false;
        }
        catch(PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');  
        }
    }

    public static function ExistePedido($codigo){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM pedido WHERE codigo = :id");
            $verificar->bindValue(':id', $codigo, PDO::PARAM_STR);
            $verificar->execute();
            $band;
            
  
            if( intval($verificar->fetchColumn()) == 1)
                $band = 1;
            else{
                if($verificar->fetchColumn() == 0 ){
                    $v= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM pedido");
                    $v->execute();
                    if($v->fetchColumn()!=0)
                        $band = 0;
                    else
                        $band = -1;
                       
                }
               
            }
            return $band;
        }
        catch(PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');  
        }
    }
}
?>