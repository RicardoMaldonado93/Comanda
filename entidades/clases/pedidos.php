<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/enums/estadoPedido.php';

class Pedidos {

    public static function cargarPedido($mesa, $mozo, $pedido, $cantidad, $cliente){
        date_default_timezone_set("America/Argentina/Buenos_Aires");
       
        
        try{

            //var_dump($aP);
           /* $c = explode(',', $aC);
            var_dump($c);
            $p = explode(',',$aP);
            $tp= sizeof($p);
            if($tp>1){
                    
                for($i=0; $i<$tp; $i++){
                    if(sizeof($c)==0){
                            $b[$i] = 1;
                            echo ("IF INSERT INTO pedido(cliente,pedido,cantidad, estado) VALUES (".$cliente . "," .  $p[$i] . ",". $b[$i]. ",:est).<br>");
                    }

                    else{
                        if( sizeof($c)< $tp && 1 < sizeof($c) ){
                            
                            $b[sizeof($c)+$i]= 1;
                            echo (" ELSE INSERT INTO pedido(cliente,pedido,cantidad, estado) VALUES (".$cliente . "," .  $p[$i] . ",". $b[$i]. ",:est).<br>");
                        }
                        
                    
                        echo ("FUERA INSERT INTO pedido(cliente,pedido,cantidad, estado) VALUES (".$cliente . "," .  $p[$i] . ",". $c[$i]. ",:est).<br>");
                    }
                   
                    //$v = $p[1][0];
                    

                }
            }*/
            //echo self::generarCodigo();
            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
            $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO pedido(ID, cliente, mesa , mozo, pedido, cantidad, estado, total, fechaInicio) VALUES ( :id, :nom, :me, :mo, :ped, :cant, :est, :tot, :fi)");

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
            $consulta->bindValue(':fi', date("Y-m-d H:i:s"), PDO::PARAM_STR);
   
            if($consulta->execute()==true)
                return "---------> SE REALIZO EL PEDIDO <---------<br>" . "CODIGO: " . $codigo ;
            
            else
               throw new PDOException("ERROR AL REALIZAR PEDIDO",404);
        }
        catch( PDOException $e){
            return "*********** ERROR ***********<br>" . $e->getCode() . ': '. strtoupper($e->getMessage()) . "<br>******************************"; 
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

    
}
?>