<?php

require_once "./entidades/clases/conexion/AccesoDatos.php";

class Menu {

    public static function mostrar(){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT c.comida, c.precio FROM comidas c");
            $consulta2 = $objetoAccesoDato->RetornarConsulta("SELECT b.bebida, b.precio FROM bebidas b");
            $consulta3 = $objetoAccesoDato->RetornarConsulta("SELECT p.postre, p.precio FROM postres p");
            $consulta->execute();
            $consulta2->execute();
            $consulta3->execute();

            $c = array("COMIDAS"=>$consulta->fetchAll(PDO::FETCH_CLASS, 'Menu'));
            $b = array("BEBIDAS"=>$consulta2->fetchAll(PDO::FETCH_CLASS, 'Menu'));
            $p = array("POSTRES"=>$consulta3->fetchAll(PDO::FETCH_CLASS, 'Menu'));

            return $c + $b + $p;
            
        }
        catch( PDOException $e){
            echo "*********** ERROR ***********<br>" . strtoupper($e->getMessage()); 
        }
    }

}
?>