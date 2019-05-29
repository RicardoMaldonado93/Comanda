<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/clases/validaciones/validacion.php';

class Login {

    public static function loguear($usuario, $pass){
        try{
              
            if( Validar::verificar('usuario','personal',$usuario)){ #verificar la funcionalidad
                        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                        $con= $objetoAccesoDato->RetornarConsulta("SELECT pass FROM personal where usuario = :usuario");
                        $con->bindValue(':usuario', $usuario, PDO::PARAM_STR);
                        $con->execute();
                        $hash = $con->fetch(PDO::FETCH_ASSOC);
                        
                        if(password_verify($pass,$hash['pass'])){
                            $consulta= $objetoAccesoDato->RetornarConsulta("SELECT usuario, nombre, apellido, puesto,estado FROM personal where usuario = :usuario");
                            $consulta->bindValue(':usuario', $usuario, PDO::PARAM_STR);
                            $consulta->execute();
                        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Login');
                    }
                    else
                        throw new PDOException("password invalido!");
                }
            else
                throw new PDOException("usuario no existe!");        
            }
        catch (PDOException $e){

            if($e->getMessage()=='password invalido!')
                return array('msg'=>strtoupper($e->getMessage()), 'type'=>'errorPassword');

            if($e->getMessage()=='usuario no existe!')
                return array('msg'=>strtoupper($e->getMessage()), 'type'=>'errorUsuario');

            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error'); 
        }
    }
    

}
?>