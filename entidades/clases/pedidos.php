<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/enums/estadoPedido.php';
require_once './entidades/clases/validaciones/validacion.php';
require_once './entidades/clases/personal.php';
require_once './entidades/clases/mesa.php';



class Pedidos {

    public static function cargarPedido($mesa, $mozo, $lista_pedido, $cliente){
        
        try{
            
                date_default_timezone_set("America/Argentina/Buenos_Aires"); #seteo el timezone para la fecha y hora
                $v = Mesa::ocuparMesa($mesa); #verifica si la mesa esta cerrada
                $band = false;

                
                if($v == 1){

                    #cargo los pedidos pasados en el json
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                   
                    
                    $hora = date("H:i:s");
                    $fecha = date("Y-m-d");
                    $codigo = self::generarCodigo(); #genero un codigo aleatorio alfanumerico de 5 caracteres

                    foreach( $lista_pedido as $pedido ){ #recorro la lista de pedidos para poder cargarlos
                        
                       
                        if(isset($pedido)){
                            if(Validar::verificar('id','menu',$pedido->{'id'}) != Null){ #verifico si existe el producto

                                #si existe el producto calculo el costo total del producto pedido de forma individual
                                $Ctotal = $objetoAccesoDato->RetornarConsulta("SELECT precio * " . $pedido->{'cantidad'} . " as Total FROM menu  WHERE ID=:id ");
                                $Ctotal->bindValue(':id', $pedido->{'id'}, PDO::PARAM_INT);
                                $Ctotal->execute();
                                $total =  $Ctotal->fetch();
                                
                                #aca se agrega el producto pedido para la preparacion
                                $CP = $objetoAccesoDato->RetornarConsulta("INSERT INTO productopedido( codigo, idProducto , cantidad, estado, total, horaInicio, fecha)
                                                                            VALUES ( :cod, :iP, :cant, :est, :tot, :hi, :fe)");

                                $CP->bindValue(':cod', $codigo , PDO::PARAM_STR);
                                $CP->bindValue(':iP', $pedido->{'id'}, PDO::PARAM_INT);
                                $CP->bindValue(':cant', $pedido->{'cantidad'}, PDO::PARAM_INT);
                                $CP->bindValue(':est', EPedido::Pendiente , PDO::PARAM_INT);
                                $CP->bindValue(':tot', $total[0] , PDO::PARAM_STR);
                                $CP->bindValue(':hi', $hora , PDO::PARAM_STR);
                                $CP->bindValue(':fe', $fecha , PDO::PARAM_STR);
                                
                                #si se cargo correctamente el producto cambio el valor de la bandera 
                                #a true de lo contrario false;
                                if($CP->execute())
                                    $band = true; 
                                else
                                    $band = false;
                            }
                            else
                                throw new Exception(strtoupper('no existe el producto con el id: ' . $pedido->{'id'}));
                        }
                        else
                            break;
                    }

                    if($band){

                        #si se realizo la carga de/los productos calculo el total de la compra
                        $Ctotal = $objetoAccesoDato->RetornarConsulta("SELECT SUM(total) as Total FROM productopedido  WHERE codigo=:cod ");
                        $Ctotal->bindValue(':cod', $codigo, PDO::PARAM_STR);
                        $Ctotal->execute();
                        $total =  $Ctotal->fetch();

                        #cargo la venta con los datos correspondientes
                        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedido(codigo, cliente, mesa , mozo, estado, total, horaInicio, fecha) VALUES ( :cod, :nom, :me, :mo, :est, :tot, :hi, :fe)");

                        $consulta->bindValue(':cod', $codigo , PDO::PARAM_STR);
                        $consulta->bindValue(':nom', $cliente, PDO::PARAM_STR);
                        $consulta->bindValue(':me', $mesa, PDO::PARAM_INT);
                        $consulta->bindValue(':mo', $mozo, PDO::PARAM_INT);
                        $consulta->bindValue(':est', EPedido::Pendiente , PDO::PARAM_INT);
                        $consulta->bindValue(':tot', $total[0] , PDO::PARAM_STR);
                        $consulta->bindValue(':hi', $hora , PDO::PARAM_STR);
                        $consulta->bindValue(':fe', $fecha , PDO::PARAM_STR);

                        
                        if($consulta->execute()==true){
                            #si se realizo correctamente la consulta retorno un array notificando la correcta operacion 
                            #y el codigo alfanumerico del pedido
                            return array('msg'=>"SE REALIZO EL PEDIDO",'type'=>'ok', 'codigo'=>$codigo) ;
                        }
                        else
                            throw new PDOException("ERROR AL REALIZAR PEDIDO",404);
                        }
            
                }
                
                else
                    throw new PDOException( $v['msg']);
        }
        catch( PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
        }
    }

    public static function prepararPedido($id, $codigo, $demora){
        try{

            $v = Validar::ExistePedido($codigo); #verifico si existe el pedido con el codigo ingresado

            if($v != 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                
                $CalHora = $objetoAccesoDato->RetornarConsulta("SELECT horaInicio + INTERVAL :demora MINUTE as Fin FROM productopedido  WHERE codigo =:cod AND idProducto = :id"); 
                $CalHora->bindValue(':id', $id, PDO::PARAM_INT);
                $CalHora->bindValue(':cod', $codigo, PDO::PARAM_STR);
                $CalHora->bindValue(':demora',$demora, PDO::PARAM_INT);
                $CalHora->execute();
                $hora = $CalHora->fetch();

                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE productopedido SET horaFin=:fin,  demora= :de, estado=:es WHERE idProducto = :id AND codigo = :cod");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':cod',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':de', $demora, PDO::PARAM_INT);
                $consulta->bindValue(':es', EPedido::EnPreparacion, PDO::PARAM_INT);
                $consulta->bindValue(':fin', $hora[0], PDO::PARAM_STR);

                if($consulta->execute() == true)
                    if( self::ActualizarEstado($codigo) == true)
                        return array('msg'=>"EL PEDIDO SE ENCUENTRA EN PREPARACION", 'type'=>'ok');
                else
                    throw new PDOException ("ERROR AL PREPARAR EL PEDIDO");    
            }

             else
            {

                if($v == -1)
                    throw new PDOException("NINGUN REGISTRO A BORRAR",405);
                else 
                    throw new PDOException("NO EXISTE REGISTRO",404); 
                    
            

            }
        }

        catch( PDOException $e){

            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
            }
    }

    public static function cancelarPedido($id, $codigo, $sector){
        try{

            $v = Validar::ExistePedido($codigo);

            if($v== 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE productopedido SET estado=:es WHERE iProducto=:id, codigo =:cod");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':cod',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':es', EPedido::Cancelado, PDO::PARAM_INT);

                if($consulta->execute() == true)
                    return array('msg'=>"EN ". strtoupper($sector) . ": SE CANCELO EL PEDIDO.", 'type'=>'ok');
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

            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
            }
     }

    
     public static function pedidoListo($id, $codigo, $sector){
        try{

            $v = Validar::ExistePedido($codigo);

            if($v!= 1){
               
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE productopedido SET estado=:es WHERE idProducto=:id AND codigo =:cod");
                $consulta->bindValue(':id', $id, PDO::PARAM_INT);
                $consulta->bindValue(':cod',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':es', EPedido::ListoParaServir, PDO::PARAM_INT);

                if($consulta->execute() == true)
                    return array('msg'=>"EN ". strtoupper($sector) . ": LISTO PARA SERVIR.", 'type'=>'ok');
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

            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error'); 
            }
    }

    public static function entregarPedido($codigo){
        try{

            $v = Validar::ExistePedido($codigo);

            if($v!= 1){
               
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
                        return array('msg'=>"SE REGISTRO ENTREGA", 'type'=>'ok');
                    else
                        throw new PDOException(Mesa::estadoMesa($nroMesa,5));
                else
                    throw new PDOException ("ERROR AL REGISTRAR ENTREGA");    
            }

             else
            {

                if($v == -1)
                    throw new PDOException("NINGUN REGISTRO A BORRAR",405);
                else 
                    throw new PDOException("NO EXISTE REGISTRO",405);
            

            }
        }

        catch( PDOException $e){

            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
            }
    }

    public static function traerPedido($codigo){
        try{

            $v = Validar::ExistePedido($codigo);
            
            if($v== 1){
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre as pedido FROM productopedido p, menu m Where p.codigo=:cod and m.id = p.idProducto");
                $consulta->bindValue(':cod', $codigo, PDO::PARAM_STR);
                
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

            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
        }
    }

    public static function traerPedidos(){

        try{
            
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta=[];
            $lista=array('PENDIENTES'=>[], 'EN PREPARACION'=>[]);

            for( $i=0; $i<=4; $i++){
                $consulta[$i] = $objetoAccesoDato->RetornarConsulta('SELECT p.*, m.nombre as pedido FROM productopedido p, menu m WHERE m.id = p.idProducto AND p.estado ='  . ($i+1) .'' );
                if($consulta[$i]->execute()==true){
                    switch($i){
                        case 0: { $lista['PENDIENTES'] = $consulta[$i]->fetchAll(PDO::FETCH_CLASS, 'Pedidos'); break;}
                        case 1: { $lista['EN PREPARACION'] = $consulta[$i]->fetchAll(PDO::FETCH_CLASS, 'Pedidos'); break;}
                        case 2: { $lista["PARA SERVIR"] = $consulta[$i]->fetchAll(PDO::FETCH_CLASS, 'Pedidos'); break;}
                        case 3: { $lista["CANCELADOS"] = $consulta[$i]->fetchAll(PDO::FETCH_CLASS, 'Pedidos'); break;}
                        case 4: { $lista["ENTREGADOS"] = $consulta[$i]->fetchAll(PDO::FETCH_CLASS, 'Pedidos'); break;}
                    }
                    
                }
                else
                    throw new PDOException("ERROR AL MOSTRAR PEDIDOS");
            }
                return $lista; 
         
        }
        catch( PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
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
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre  FROM productopedido p, menu m WHERE m.id = p.idProducto AND p.estado = :es");
                $consulta->bindValue(':es', $es, PDO::PARAM_INT);
            
                if($consulta->execute()==true)
                    return array( $cabecera =>$consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));         
                else
                    throw new PDOException("ERROR AL MOSTRAR PEDIDOS");
        }
        catch( PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
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
                $consulta = $objetoAccesoDato->RetornarConsulta("SELECT p.*, m.nombre  FROM productopedido p, menu m WHERE m.id = p.idProducto AND m.sector = :se");
                $consulta->bindValue(':se', $se, PDO::PARAM_INT);
            
                if($consulta->execute()==true)
                    return array( $cabecera =>$consulta->fetchAll(PDO::FETCH_CLASS, 'Pedidos'));         
                else
                    throw new PDOException("ERROR AL MOSTRAR PEDIDOS");
        }
        catch( PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error'); 
        }
    }

    public static function verEstadoPedido($cod, $mesa){

        try{
                date_default_timezone_set("America/Argentina/Buenos_Aires");
                $actual = date('y-m-d H:i:s');
                $v = Validar::ExistePedido($cod); #si el codigo existe me devuelte todo el pedido para poder mostrar
                    
                if($v != 0 && $v != -1){

                        $personal = Personal::MostrarX($v['mozo']); #a partir del id del mozo busco sus datos
                        $mozo= $personal[0]->{'apellido'};  #cargo el apellido del mozo para mostrar 
                        $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                                               
                        #en el siguiente procedimiento me encargo de buscar si el codigo junto al numero de mesa coinciden con la informacion del 
                        #en todo caso de no coincidir me devuelve un valor nulo o vacio.
                        $consulta = $objetoAccesoDato->RetornarConsulta("SELECT m.id FROM mesa m inner join pedido p WHERE (p.codigo=:id AND m.codigo=:mesa AND p.mesa = m.id)");
                        $consulta->bindValue(':id', $cod, PDO::PARAM_STR);
                        $consulta->bindValue(':mesa', $mesa, PDO::PARAM_STR);
                        $consulta->execute();
                        $Cmesa = $consulta->fetch();

                        
                    
                        if($v['mesa'] == $Cmesa[0]){ #verifico si la mesa coincide con el codigo

                            $e = strtotime( $v['fecha'] .$v['horaFin'] );
                            $r = strtotime($actual);
                            $d = date("i:s", $e-$r);
                        
                            $t = $d ." MIN APROX";

                            if(strtotime($d)== false) #si el tiempo se cumplio cambio la leyenda
                            $t = "en proceso de entrega";

                            return array('codigo'=> $cod, 'mesa'=>$mesa, 'mozo'=>$mozo, 'demora'=>$t);
                        }
                        else
                            throw new Exception("LA MESA NO COINCIDE",400);
                }
                else
                    throw new Exception("NO EXISTE EL CODIGO",400);
                    
        }
        catch( PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error'); 
        }
    }

    public static function servirPedido($codigo){
        try{
              
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            
            $pListo = $objetoAccesoDato->RetornarConsulta("SELECT estado, count(estado) AS cantidad FROM productopedido WHERE codigo = :cod AND estado = 3 group by estado"); 
            $pListo->bindValue(':cod', $codigo, PDO::PARAM_STR);
            $pListo->execute();
            $Listo = $pListo->fetch();

            $consulta =$objetoAccesoDato->RetornarConsulta("SELECT count(codigo) AS cantidad FROM productopedido WHERE codigo = :cod");
            $consulta->bindValue(':cod',$codigo, PDO::PARAM_STR);
            $consulta->execute();
            $Reg = $consulta->fetch();

            if( $Reg == true)
                return true;
            else
                throw new PDOException ("ERROR AL ACTUALIZAR EL PEDIDO");    
        }
        catch( PDOException $e){

            return array('msg'=>strtoupper($e->getMessage()) ,'type'=>'error'); 
        }

     }

    
     private static function ActualizarEstado($codigo){
        try{
              
                $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                
                $maxDemora = $objetoAccesoDato->RetornarConsulta("SELECT horafin, MAX(demora)  FROM productopedido  WHERE codigo =:cod "); 
                $maxDemora->bindValue(':cod', $codigo, PDO::PARAM_STR);
                $maxDemora->execute();
                $demora = $maxDemora->fetch();

                $consulta =$objetoAccesoDato->RetornarConsulta("UPDATE pedido SET horaFin=:fin,  demora= :de WHERE codigo = :cod");
                $consulta->bindValue(':cod',$codigo, PDO::PARAM_STR);
                $consulta->bindValue(':de', $demora[1], PDO::PARAM_INT);
                $consulta->bindValue(':fin', $demora[0], PDO::PARAM_STR);

                if($consulta->execute() == true)
                    return true;
                else
                    throw new PDOException ("ERROR AL ACTUALIZAR EL PEDIDO");    
            
        }

        catch( PDOException $e){

            return array('msg'=>strtoupper($e->getMessage()),'type'=>"error"); 
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

        return array('fecha'=>$fecha, 'hora'=>$hora, 'codigo'=>$codigo, 'mesa'=>$codMesa[0], 'mozo'=>$codMozo[1], 
                     'pedido'=>strtoupper($codCom[0]) , 'cantidad'=>$cantidad, 'total'=>$total);

        
    }


    public static function agregarPedido($codigo,$lista_pedido){

        date_default_timezone_set("America/Argentina/Buenos_Aires");

        try{
                $v = Validar::ExistePedido($codigo); #verifica si existe el pedido
                $band = false;
             
                
                if($v != null ){

                    #cargo los pedidos pasados en el json
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $hora = date("H:i:s");
                    $fecha = date("Y-m-d");
                    
                    foreach( $lista_pedido as $pedido ){ #recorro la lista de pedidos para poder cargarlos
                        
                       
                        if(isset($pedido)){
                            if(Validar::verificar('id','menu',$pedido->{'id'}) != Null){ #verifico si existe el producto
                                $Ctotal = $objetoAccesoDato->RetornarConsulta("SELECT precio * " . $pedido->{'cantidad'} . " as Total FROM menu  WHERE ID=:id ");
                                $Ctotal->bindValue(':id', $pedido->{'id'}, PDO::PARAM_INT);
                                $Ctotal->execute();
                                $total =  $Ctotal->fetch();
                                
                                $CP = $objetoAccesoDato->RetornarConsulta("INSERT INTO productopedido( codigo, idProducto , cantidad, estado, total, horaInicio, fecha)
                                                                            VALUES ( :cod, :iP, :cant, :est, :tot, :hi, :fe)");

                                $CP->bindValue(':cod', $codigo , PDO::PARAM_STR);
                                $CP->bindValue(':iP', $pedido->{'id'}, PDO::PARAM_INT);
                                $CP->bindValue(':cant', $pedido->{'cantidad'}, PDO::PARAM_INT);
                                $CP->bindValue(':est', EPedido::Pendiente , PDO::PARAM_INT);
                                $CP->bindValue(':tot', $total[0] , PDO::PARAM_STR);
                                $CP->bindValue(':hi', $hora , PDO::PARAM_STR);
                                $CP->bindValue(':fe', $fecha , PDO::PARAM_STR);
                                
                                #si se cargo correctamente el producto cambio el valor de la bandera 
                                #a true de lo contrario false;
                                if($CP->execute())
                                    $band = true; 
                                else
                                    $band = false;
                            }
                            else
                                throw new Exception(strtoupper('no existe el producto con el id: ' . $pedido->{'id'}));
                        }
                        else
                            break;
                    }

                    if($band){

                        $Ctotal = $objetoAccesoDato->RetornarConsulta("SELECT SUM(total) as Total FROM productopedido  WHERE codigo=:cod ");
                        $Ctotal->bindValue(':cod', $codigo, PDO::PARAM_STR);
                        $Ctotal->execute();
                        $total =  $Ctotal->fetch();

                        //de ser la bandera verdadera, cargo el pedido a la base de datos
                        $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedido(codigo, cliente, mesa , mozo, estado, total, horaInicio, fecha) VALUES ( :cod, :nom, :me, :mo, :est, :tot, :hi, :fe)");

                        $consulta->bindValue(':cod', $codigo , PDO::PARAM_STR);
                        $consulta->bindValue(':nom', $v[0]->{'cliente'}, PDO::PARAM_STR);
                        $consulta->bindValue(':me', $v[0]->{'mesa'}, PDO::PARAM_INT);
                        $consulta->bindValue(':mo', $v[0]->{'mozo'}, PDO::PARAM_INT);
                        $consulta->bindValue(':est', EPedido::Pendiente , PDO::PARAM_INT);
                        $consulta->bindValue(':tot', $total[0] , PDO::PARAM_STR);
                        $consulta->bindValue(':hi', $hora , PDO::PARAM_STR);
                        $consulta->bindValue(':fe', $fecha , PDO::PARAM_STR);

                        
                        if($consulta->execute()==true){
                            return array('msg'=>"SE REALIZO EL AGREGADO AL PEDIDO",'type'=>'ok') ;
                        }
                        else
                            throw new PDOException("ERROR AL REALIZAR PEDIDO",404);
                        }
            
                }
                
                else
                    throw new PDOException( $v['msg']);
        }
        catch( PDOException $e){
            return array('msg'=>strtoupper($e->getMessage()), 'type'=>'error');
        }
    }
}
?>