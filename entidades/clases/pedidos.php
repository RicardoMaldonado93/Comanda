<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/enums/estadoPedido.php';
require_once './entidades/clases/validaciones/validacion.php';
require_once './entidades/clases/mesa.php';

class Pedidos {

    public static function cargarPedido($mesa, $mozo, $pedido, $cantidad, $cliente){
        
        date_default_timezone_set("America/Argentina/Buenos_Aires");
        try{
                $v = Mesa::ocuparMesa($mesa);
                
                if($v == 1){
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedido(ID, cliente, mesa , mozo, pedido, cantidad, estado, total, horaInicio, fecha) VALUES ( :id, :nom, :me, :mo, :ped, :cant, :est, :tot, :hi, :fe)");

                    $Ctotal = $objetoAccesoDato->RetornarConsulta("SELECT precio * " . $cantidad . " as Total FROM menu  WHERE ID=:id ");
                    $Ctotal->bindValue(':id', $pedido, PDO::PARAM_INT);
                    $Ctotal->execute();
                    $total =  $Ctotal->fetch();
                    $codigo = self::generarCodigo();
                    $hora = date("H:i:s");
                    $fecha = date("Y-m-d");

                    $consulta->bindValue(':id', $codigo , PDO::PARAM_STR);
                    $consulta->bindValue(':nom', $cliente, PDO::PARAM_STR);
                    $consulta->bindValue(':me', $mesa, PDO::PARAM_INT);
                    $consulta->bindValue(':mo', $mozo, PDO::PARAM_INT);
                    $consulta->bindValue(':ped', $pedido , PDO::PARAM_INT);
                    $consulta->bindValue(':cant', $cantidad, PDO::PARAM_INT);
                    $consulta->bindValue(':est', EPedido::Pendiente , PDO::PARAM_INT);
                    $consulta->bindValue(':tot', $total[0] , PDO::PARAM_STR);
                    $consulta->bindValue(':hi', $hora , PDO::PARAM_STR);
                    $consulta->bindValue(':fe', $fecha , PDO::PARAM_STR);
        
                    if($consulta->execute()==true)
                        return "---------> SE REALIZO EL PEDIDO <---------<br><br>" . self::CrearTicket($codigo, $mesa, $mozo, $pedido, $cantidad, $total[0], $hora, $fecha) ;
                    
                    else
                        throw new PDOException("ERROR AL REALIZAR PEDIDO",404);
                }
                
                else
                    throw new PDOException( Mesa::ocuparMesa($mesa));
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . strtoupper($e->getMessage()) . "<br>******************************"; 
        }
    }

    public static function prepararPedido($codigo,$demora){
        try{

            $v = Validar::Existe($codigo,'pedido');

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

            $v = Validar::Existe($codigo,'pedido');

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

            $v = Validar::Existe($codigo,'pedido');

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

    public static function entregarPedido($codigo){
        try{

            $v = Validar::Existe($codigo,'pedido');

            if($v== 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $mesa = $objetoAccesoDato->RetornarConsulta("SELECT m.id FROM mesa m, pedido p WHERE p.ID =:cod AND m.id = p.mesa");
                $mesa->bindValue(':cod', $codigo, PDO::PARAM_STR);
                $mesa->execute();
                $nroMesa = $mesa->fetch();

                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedido SET estado=:es WHERE ID LIKE :id");
                $consulta->bindValue(':id',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':es', EPedido::Entregado, PDO::PARAM_INT);

                if($consulta->execute() == true)
                    if(Mesa::estadoMesa($nroMesa[0], 2) == 1)
                        return " ---------> SE REGISTRO ENTREGA <---------";
                    else
                        throw new PDOException(Mesa::estadoMesa($nroMesa,5));
                else
                    throw new PDOException ("ERROR AL REGISTRAR ENTREGA");    
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

            $v = Validar::Existe($codigo,'pedido');

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
            $consulta5 = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM pedido p, menu m WHERE m.id = p.pedido AND p.estado = 5");
            
            if($consulta->execute()==true && $consulta2->execute()==true && $consulta3->execute()==true && $consulta4->execute()==true && $consulta5->execute()==true){
                $pe = array( "PENDIENTES"=>$consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $pre = array( "EN PREPARACION"=> $consulta2->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $li = array( "PARA SERVIR"=> $consulta3->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $ca = array( "CANCELADOS"=>$consulta4->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));
                $en = array( "ENTREGADOS"=>$consulta5->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));


                return $pe + $pre + $li + $ca + $en;
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

    private static function CrearTicket($codigo, $mesa, $mozo, $pedido, $cantidad, $total, $hora, $fecha){

        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();
        $Cmesa = $objetoAccesoDato->RetornarConsulta("SELECT m.codigo FROM mesa m WHERE m.id=:id ");
        $Cmesa->bindValue(':id', $mesa, PDO::PARAM_INT);
        $Cmesa->execute();

        $Cmozo = $objetoAccesoDato->RetornarConsulta("SELECT nombre,apellido FROM personal  WHERE ID=:id ");
        $Cmozo->bindValue(':id', $mozo, PDO::PARAM_INT);
        $Cmozo->execute();

        $Ccomida = $objetoAccesoDato->RetornarConsulta("SELECT c.nombre FROM menu c WHERE c.id=:id ");
        $Ccomida->bindValue(':id', $pedido, PDO::PARAM_INT);
        $Ccomida->execute();

        $codMesa= $Cmesa->fetch();
        $codMozo= $Cmozo->fetch();
        $codCom= $Ccomida->fetch();

        return "<pre><br>******************** TICKET DE COMPRA *********************<br><br>".
                " FECHA : " . $fecha  . "<br> HORA : " . $hora .
                "<br><br>***********************************************************<br><br>".
                " CODIGO : " .  $codigo . "<br>" .
                " MESA : " . $codMesa[0] . "<br>".
                " MOZO : " . $codMozo[1] . "<br>" . 
                "<br>***********************************************************<br><br>".
                " PEDIDO : " .  strtoupper($codCom[0]) . "<br>".
                " CANTIDAD : " . $cantidad . "<br>".
                "<br>***********************************************************<br><br>".
                " TOTAL : $" . $total ;

        
    }


}
?>