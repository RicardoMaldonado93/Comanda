<?php

require_once "./entidades/clases/conexion/AccesoDatos.php";

class Menu {

    public static function mostrar(){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT nombre, precio FROM menu WHERE sector =2");//cocina
            $consulta2 = $objetoAccesoDato->RetornarConsulta("SELECT nombre, precio FROM menu WHERE sector =3");//bartender
            $consulta3 = $objetoAccesoDato->RetornarConsulta("SELECT nombre, precio FROM menu WHERE sector =1");//cerveceria
            $consulta4 = $objetoAccesoDato->RetornarConsulta("SELECT nombre, precio FROM menu WHERE sector =4");//candybar
          
            $consulta->execute();
            $consulta2->execute();
            $consulta3->execute();
            $consulta4->execute();

            $c = array("COMIDAS"=>$consulta->fetchAll(PDO::FETCH_CLASS, 'Menu'));
            $b = array("BEBIDAS"=>$consulta2->fetchAll(PDO::FETCH_CLASS, 'Menu'));
            $ce = array("CERVEZAS"=>$consulta3->fetchAll(PDO::FETCH_CLASS, 'Menu'));
            $p = array("POSTRES"=>$consulta4->fetchAll(PDO::FETCH_CLASS, 'Menu'));

            return $c + $b + $ce + $p;
            
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()); 
        }
    }

}
?>