<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/enums/tipoEmpleado.php';

class Personal {

    public static function AgregarEmpleado($nom, $ape, $puesto){

        try{

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO personal(nombre,apellido,puesto) VALUES ( :nom, :ape, :pue )");
            $consulta->bindValue(':nom', $nom, PDO::PARAM_STR);
            $consulta->bindValue(':ape', $ape, PDO::PARAM_STR);
            $consulta->bindValue(':pue', $puesto, PDO::PARAM_STR);
            

            if($consulta->execute()==true)
                return "---------> SE AGREGO CORRECTAMENTE EL EMPLEADO <---------";
            
            else
               throw new PDOException("ERROR AL AGREGAR EL EMPLEADO");
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . $consulta->errorCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function ModificarEmpleado($id, $nom, $ape, $puesto){

        try{

            if(self::Verificar == true){
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("
                    update personal 
                    set nombre=:nom,
                        apellido=:ape,
                        puesto=:pue
                    WHERE  ID LIKE :id");
                $consulta->bindValue(':ID',$id, PDO::PARAM_INT);
                $consulta->bindValue(':nom',$nom, PDO::PARAM_STR);
                $consulta->bindValue(':ape', $ape, PDO::PARAM_STR);
                $consulta->bindValue(':pue', $puesto, PDO::PARAM_STR);
        
                if($consulta->execute() == true)
                    return " ---------> SE MODIFICO CORRECTAMENTE EL REGISTRO <---------<br>";
                else
                    throw new PDOException ("ERROR AL MODIFICAR EL REGISTRO");    
            }

            else
            throw new PDOException("NO EXISTE REGISTRO",4404);
        }

     catch( PDOException $e){
        return "*********** ERROR ***********<br>" . $e->getCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function EliminarEmpleado($id){

        try{
        
            if( self::Verificar($id) == true){
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
                $consulta= $objetoAccesoDato->RetornarConsulta("DELETE FROM personal WHERE ID = :id");
                $consulta->bindValue(':id',$id,PDO::PARAM_INT);

                if($consulta->execute() == true)
                    return " ---------> SE BORRO REGISTRO CORRECTAMENTE <---------<br>";

                else
                    throw new PDOException("ERROR AL ELIMINAR EL REGISTRO");
            }

            else
                throw new PDOException("NO EXISTE REGISTRO",4404);

            if(self::Verificar($id) == -1)
                throw new PDOException("NO HAY REGISTROS DISPONIBLES",4405);
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

    private static function Verificar($id){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT ID FROM personal WHERE ID = :id");
            $verificar->bindValue(':id', $id, PDO::PARAM_INT);
            $verificar->execute();

            if($verificar->fetchColumn()!= false)
                return true;
             else
                if($verificar->fetchColumn()== false){
                    $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM personal ");
                        var_dump($verificar->execute());
                    }
              
            

        }
        catch(PDOException $e){
            return "*********** ERROR ***********<br>" . $verificar->errorCode() . ':'. strtoupper($e->getMessage()) . "<br>******************************";  
        }
    }
}
?>