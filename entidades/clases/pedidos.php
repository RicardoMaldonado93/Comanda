<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/enums/estadoPedido.php';

class Pedidos {

    public static function cargarPedido($mesa, $mozo, $pedido, $cantidad, $cliente){
        
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        try{

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedido(ID, cliente, mesa , mozo, pedido, cantidad, estado, total, horaInicio, fecha) VALUES ( :id, :nom, :me, :mo, :ped, :cant, :est, :tot, :hi, :fe)");

            $Ctotal = $objetoAccesoDato->RetornarConsulta("SELECT precio * " . $cantidad . " as Total FROM menu  WHERE ID=:id ");
            $Ctotal->bindValue(':id', $pedido, PDO::PARAM_INT);
            $Ctotal->execute();
            $total =  $Ctotal->fetch();
            $codigo = self::generarCodigo();

            $consulta->bindValue(':id', $codigo , PDO::PARAM_STR);
            $consulta->bindValue(':nom', $cliente, PDO::PARAM_STR);
            $consulta->bindValue(':me', $mesa, PDO::PARAM_INT);
            $consulta->bindValue(':mo', $mozo, PDO::PARAM_INT);
            $consulta->bindValue(':ped', $pedido , PDO::PARAM_INT);
            $consulta->bindValue(':cant', $cantidad, PDO::PARAM_INT);
            $consulta->bindValue(':est', EPedido::Pendiente , PDO::PARAM_INT);
            $consulta->bindValue(':tot', $total[0] , PDO::PARAM_STR);
            $consulta->bindValue(':hi', date("H:i:s"), PDO::PARAM_STR);
            $consulta->bindValue(':fe', date("Y-m-d"), PDO::PARAM_STR);
   
            if($consulta->execute()==true)
                return "---------> SE REALIZO EL PEDIDO <---------<br>" . "CODIGO: " . $codigo ;
            
            else
               throw new PDOException("ERROR AL REALIZAR PEDIDO",404);
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . $e->getCode() . ': '. strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function prepararPedido($codigo,$demora){
        try{

            $v = self::Verificar($codigo);

            if($v== 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                
                $CalHora = $objetoAccesoDato->RetornarConsulta("SELECT horaInicio + INTERVAL :demora MINUTE as Fin FROM pedido  WHERE ID =:id"); 
                $CalHora->bindValue(':id', $codigo, PDO::PARAM_STR);
                $CalHora->bindValue(':demora',$demora, PDO::PARAM_INT);
                $CalHora->execute();
                $hora = $CalHora->fetch();

                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedido SET horaFin=:fin,  demora= :de, estado=:es WHERE ID LIKE :id");
                $consulta->bindValue(':id',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':de', $demora, PDO::PARAM_INT);
                $consulta->bindValue(':es', EPedido::EnPreparacion, PDO::PARAM_INT);
                $consulta->bindValue(':fin', $hora[0], PDO::PARAM_STR);

                if($consulta->execute() == true)
                    return " ---------> EL PEDIDO SE ENCUENTRA EN PREPARACION <---------s";
                else
                    throw new PDOException ("ERROR AL PREPARAR EL PEDIDO");    
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

            return "*********** ERROR ***********<br>" .  strtoupper($e->getMessage()) . "<br>******************************"; 
            }
    }

    public static function cancelarPedido($codigo, $sector){
        try{

            $v = self::Verificar($codigo);

            if($v== 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estado=:es WHERE ID LIKE :id");
                $consulta->bindValue(':id',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':es', EPedido::Cancelado, PDO::PARAM_INT);

                if($consulta->execute() == true)
                    return " ---------> EN ". strtoupper($sector) . ": SE CANCELO EL PEDIDO <---------";
                else
                    throw new PDOException ("ERROR AL CANCELAR EL PEDIDO");    
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

            return "*********** ERROR ***********<br>" .  strtoupper($e->getMessage()) . "<br>******************************"; 
            }
    }

    public static function servirPedido($codigo, $sector){
        try{

            $v = self::Verificar($codigo);

            if($v== 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estado=:es WHERE ID LIKE :id");
                $consulta->bindValue(':id',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':es', EPedido::ListoParaServir, PDO::PARAM_INT);

                if($consulta->execute() == true)
                    return " ---------> EN ". strtoupper($sector) . ": LISTO PARA SERVIR. <---------";
                else
                    throw new PDOException ("ERROR AL SERVIR EL PEDIDO");    
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

            return "*********** ERROR ***********<br>" .  strtoupper($e->getMessage()) . "<br>******************************"; 
            }
    }

    public static function traerPedido($codigo){
        try{

            $v = self::Verificar($codigo);

            if($v== 1){
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m Where p.id=:id and m.id = p.pedido");
                $consulta->bindValue(':id', $codigo, PDO::PARAM_STR);
                
                if($consulta->execute()==true)
                    return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos');
                else
                    throw new PDOException("ERROR AL MOSTRAR PEDIDO");
            }
            else
            {

                if($v == -1)
                    throw new PDOException("NINGUN PEDIDO A MOSTRAR",4405);
                else 
                    throw new PDOException("NO EXISTE PEDIDO",4404);
            

            }
        }
        catch( PDOException $e){

            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function traerPedidos(){

        try{

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND p.estado = 1");
            $consulta2 = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND p.estado = 2");
            $consulta3 = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND p.estado = 3");
            $consulta4 = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND p.estado = 4");
            
            if($consulta->execute()==true && $consulta2->execute()==true && $consulta3->execute()==true && $consulta4->execute()==true){
                $pe = array( "PENDIENTES"=>$consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $pre = array( "EN PREPARACION"=> $consulta2->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $li = array( "PARA SERVIR"=> $consulta3->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $ca = array( "CANCELADOS"=>$consulta4->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));

                return $pe + $pre + $li + $ca;
            }
            else
                throw new PDOException("ERROR AL MOSTRAR PEDIDOS");
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }
    
    public static function traerEstado($estado){

        try{
                $es; $cabecera;
                switch(strtoupper($estado)){

                    case '1': { $es = 1; $cabecera = "PENDIENTES"; break;}
                    case '2': { $es = 2; $cabecera = "EN PREPARACION"; break;}
                    case '3': { $es = 3; $cabecera = "LISTOS PARA SERVIR"; break;}
                    case '4': { $es = 4; $cabecera = "CANCELADOS"; break;}

                }

                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND p.estado = :es");
                $consulta->bindValue(':es', $es, PDO::PARAM_INT);
            
                if($consulta->execute()==true)
                    return array( $cabecera =>$consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));         
                else
                    throw new PDOException("ERROR AL MOSTRAR PEDIDOS");
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function traerSector($estado){

        try{
                $se; $cabecera;
                switch(strtoupper($estado)){

                    case '1': { $se = 1; $cabecera = "CERVECERIA"; break; }
                    case '2': { $se = 2; $cabecera = "COCINA"; break; }
                    case '3': { $se = 3; $cabecera = "BARTENDER"; break; }
                    case '4': { $se = 4; $cabecera = "CANDYBAR"; break; }

                }

                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND m.sector = :se");
                $consulta->bindValue(':se', $se, PDO::PARAM_INT);
            
                if($consulta->execute()==true)
                    return array( $cabecera =>$consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));         
                else
                    throw new PDOException("ERROR AL MOSTRAR PEDIDOS");
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    private static function generarCodigo(){

        $alpha = "123qwertyuiopa456sdfghjklzxcvbnm789";
        $code = "";
        $longitud=5;

        for($i=0;$i<$longitud;$i++)
            $code .= $alpha[rand(0, strlen($alpha)-1)];
        
        return strtoupper($code);
    }

    private static function Verificar($codigo){
        try{
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
            $verificar= $objetoAccesoDato->RetornarConsulta("SELECT COUNT(*) FROM pedido WHERE id = :id");
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
            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()) . "<br>******************************";  
        }
    }

}
?>