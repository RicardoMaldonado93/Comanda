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

    public static function cantidadElementos($codigo, $tabla){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM ". $tabla ." WHERE codigo= :id");
            $verificar->bindValue(':id', $codigo, PDO::PARAM_INT);
            $verificar->execute();
            
            return $verificar->fetchAll(PDO::FETCH_CLASS, 'validar');
        }
        catch(PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');  
        }
    }

    public static function ExisteCodigo($codigo){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(codigo) FROM pedido WHERE codigo= :id");
            $verificar->bindValue(':id', $codigo, PDO::PARAM_STR);
            $verificar->execute();

            if(  intval($verificar->fetchColumn()) != 0)
                return true;
            else
                return false;
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

    public static function verificar( $cel, $tab, $param){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

        $verificar= $objetoAccesoDato->RetornarConsulta('SELECT '. $cel . ' ' .'FROM' .' '  . $tab . ' '.  'WHERE' .' ' . $cel . '=:param');
        $verificar->bindValue(':param', $param, PDO::PARAM_STR);
        $verificar->execute();
        
        return $verificar->fetchAll();
    }

    public static function ExistePedido($codigo){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM pedido WHERE codigo = :id");
            $verificar->bindValue(':id', $codigo, PDO::PARAM_STR);
            $verificar->execute();
           // $band;
            
  
            if( intval($verificar->fetchColumn()) == 1){
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.* FROM pedido p Where p.codigo=:cod");
                    $consulta->bindValue(':cod', $codigo, PDO::PARAM_STR);
                    
                    if($consulta->execute()==true)
                        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Validar'); #si encuentra el registro devuelve los datos del registro
            }
                
            else{
                /*if($verificar->fetchColumn() == 0 ){
                    $v= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM pedido");
                    $v->execute();
                    if($v->fetchColumn()!=0)
                        $band = 0;
                    else
                        $band = -1;*/
                       return null;
                }
               
            //}
            //return $band;
        }
        catch(PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');  
        }
    }
    

    public static function Puntuacion($valor){
            if ( $valor != NULL)
                if( $valor >=1 && $valor <=10)
                    return true;
                else
                    throw new PDOException('el valor ingresado debe estar comprendido entre 1 y 10');
            else
                throw new PDOException('ningun campo puede quedar vacio');
    }
}
?>