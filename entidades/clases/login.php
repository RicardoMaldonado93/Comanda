<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/clases/validaciones/validacion.php';

class Login {

    public static function loguear($usuario, $pass){
        try{
              
            if( Validar::verificar('usuario','personal',$usuario) != NULL)
                if( Validar::verificar('pass','personal',$pass) != NULL)
                    {
                        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                        $consulta= $objetoAccesoDato->RetornarConsulta("SELECT * FROM personal where usuario = :usuario AND pass =:pass");
                        $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
                        $consulta->bindValue(':pass',$pass, PDO::PARAM_STR);
                        $consulta->execute();
                        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Login');
                    }
                else
                throw new PDOException("password invalido!");
            else
                throw new PDOException("usuario no existente!");
 
        
            }
        catch (PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error'); 
        }
    }
    

}
?>