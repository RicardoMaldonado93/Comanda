<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/clases/validaciones/validacion.php';
require_once './entidades/enums/estadoEmpleado.php';


class Personal  {

    public static function AgregarEmpleado($nom, $ape, $puesto){

        try{

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO personal(nombre,apellido,puesto, estado) VALUES ( :nom, :ape, :pue, :est)");
            $consulta->bindValue(':nom', $nom, PDO::PARAM_STR);
            $consulta->bindValue(':ape', $ape, PDO::PARAM_STR);
            $consulta->bindValue(':pue', $puesto, PDO::PARAM_STR);
            $consulta->bindValue(':est', Estado::Activo, PDO::PARAM_STR);

            if($consulta->execute()==true)
                return "---------> SE AGREGO CORRECTAMENTE EL EMPLEADO <---------";
            
            else
               throw new PDOException("ERROR AL AGREGAR EL EMPLEADO");
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . $consulta->errorCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function ModificarEmpleado($id, $nom, $ape, $puesto, $estado){

        try{

                $v = Validar::Existe($codigo,'personal');

                if($v== 1){
                   
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $consulta =$objetoAccesoDato->RetornarConsulta("update personal set nombre=:nom, apellido=:ape, puesto=:pue, estado=:est WHERE ID LIKE :id");
                    $consulta->bindValue(':id',$id, PDO::PARAM_INT);
                    $consulta->bindValue(':nom',$nom, PDO::PARAM_STR);
                    $consulta->bindValue(':ape', $ape, PDO::PARAM_STR);
                    $consulta->bindValue(':pue', $puesto, PDO::PARAM_STR);
                    $consulta->bindValue(':est', $estado, PDO::PARAM_STR);
                    if($consulta->execute() == true)
                        return " ---------> SE MODIFICO CORRECTAMENTE EL REGISTRO <---------<br>";
                    else
                        throw new PDOException ("ERROR AL MODIFICAR EL REGISTRO");    
                }

                 else
                {

                    if($v == -1)
                        throw new PDOException("NINGUN REGISTRO A BORRAR",4405);
                    else 
                        throw new PDOException("NO EXISTE REGISTRO",4404);
                

                }
        }

     catch( PDOException $e){
    
        return "*********** ERROR ***********<br>" . $e->getCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function EliminarEmpleado($id){

        try{
                $v = Validar::Existe($codigo,'personal');
        
                if( $v === 1){
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                    $consulta= $objetoAccesoDato->RetornarConsulta("DELETE FROM personal WHERE ID = :id");
                    $consulta->bindValue(':id',$id,PDO::PARAM_INT);
    
                    if($consulta->execute() == true)
                        return " ---------> SE BORRO REGISTRO CORRECTAMENTE <---------<br>";

                    else
                        throw new PDOException("ERROR AL ELIMINAR EL REGISTRO");
                }

                else
                {

                    if($v == -1)
                        throw new PDOException("NINGUN REGISTRO A BORRAR",4405);
                    else 
                        throw new PDOException("NO EXISTE REGISTRO",4404);
                

                }
        }
        catch(PDOException $e){
            return "*********** ERROR ***********<br>" . $e->getCode() .' : '. strtoupper($e->getMessage()) . "<br>******************************";  
        }
    }

    public static function Mostrar(){
        try{

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM personal");
            
            
            if($consulta->execute()==true)
                return $consulta->fetchAll(PDO::FETCH_CLASS, 'Personal');
            else
                throw new PDOException("ERROR AL MOSTRAR EMPLEADOS");
        }
        catch( PDOException $e){

            return "*********** ERROR ***********<br>" . $consulta->errorCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function MostrarX($id){
        try{

            $v = Validar::Existe($id,'personal');

            if($v== 1){
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT * FROM personal Where ID=:id");
                $consulta->bindValue(':id', $id, PDO::PARAM_STR);
                
                if($consulta->execute()==true)
                    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Personal');
                else
                    throw new PDOException("ERROR AL MOSTRAR EMPLEADO");
            }
            else
            {

                if($v == -1)
                    throw new PDOException("NINGUN REGISTRO A MOSTRAR",4405);
                else 
                    throw new PDOException("NO EXISTE REGISTRO",4404);
            

            }
        }
        catch( PDOException $e){

            return "*********** ERROR ***********<br>" . $e->getCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function ModificarEstado($id, $estado){

        try{

                $v = Validar::Existe($codigo,'personal');

                if($v== 1){
                   
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $consulta =$objetoAccesoDato->RetornarConsulta("update personal set estado=:est WHERE ID LIKE :id");
                    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                    $consulta->bindValue(':est', $estado, PDO::PARAM_STR);

                    if($consulta->execute() == true)
                        return " ---------> SE MODIFICO CORRECTAMENTE EL REGISTRO <---------<br>";
                    else
                        throw new PDOException ("ERROR AL MODIFICAR EL REGISTRO");    
                }

                 else
                {

                    if($v == -1)
                        throw new PDOException("NINGUN REGISTRO A BORRAR",4405);
                    else 
                        throw new PDOException("NO EXISTE REGISTRO",4404);
                

                }
        }

     catch( PDOException $e){
    
        return "*********** ERROR ***********<br>" . $e->getCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function ModificarPuesto($id, $puesto){

        try{

                $v = Validar::Existe($codigo,'personal');

                if($v== 1){
                   
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $consulta =$objetoAccesoDato->RetornarConsulta("update personal set puesto=:pue WHERE ID LIKE :id");
                    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                    $consulta->bindValue(':pue', $puesto, PDO::PARAM_STR);
                    
                    if($consulta->execute() == true)
                        return " ---------> SE MODIFICO CORRECTAMENTE EL REGISTRO <---------<br>";
                    else
                        throw new PDOException ("ERROR AL MODIFICAR EL REGISTRO");    
                }

                 else
                {

                    if($v == -1)
                        throw new PDOException("NINGUN REGISTRO A BORRAR",4405);
                    else 
                        throw new PDOException("NO EXISTE REGISTRO",4404);
                

                }
        }

     catch( PDOException $e){
    
        return "*********** ERROR ***********<br>" . $e->getCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function SuspenderEmpleado($id){

        try{

                $v = Validar::Existe($codigo,'personal');

                if($v== 1){
                   
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $consulta =$objetoAccesoDato->RetornarConsulta("update personal set estado=:est WHERE ID LIKE :id");
                    $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                    $consulta->bindValue(':est', Estado::Suspendido, PDO::PARAM_STR);
                    
                    if($consulta->execute() == true)
                        return " ---------> SE HA SUSPENDIDO AL EMPLEADO <---------<br>";
                    else
                        throw new PDOException ("ERROR AL SUSPENDER");    
                }

                 else
                {

                    if($v == -1)
                        throw new PDOException("NINGUN REGISTRO A SUSPENDER",4405);
                    else 
                        throw new PDOException("NO EXISTE REGISTRO A SUSPENDER",4404);
                

                }
        }

     catch( PDOException $e){
    
        return "*********** ERROR ***********<br>" . $e->getCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

}
?>