<?php

require_once './entidades/clases/conexion/AccesoDatos.php';
require_once './entidades/clases/validaciones/validacion.php';

class Cliente{

    public static function Encuesta($cod, $calMe, $calR, $calMo, $calC, $opi){
        
        try{
            
            if( $existe = Validar::ExisteCodigo($cod)){
                if( Validar::Puntuacion($calMe) && Validar::Puntuacion($calR) && Validar::Puntuacion($calMo) && Validar::Puntuacion($calC) ){
                    $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso(); 
                    $consulta = $objetoAccesoDato->RetornarConsulta("INSERT INTO encuesta(codigo,calMesa,calResto,calMozo,calCocinero, opinion) VALUES ( :cod, :me, :res, :mo, :co, :op)");
                    $consulta->bindValue(':cod', $cod, PDO::PARAM_STR);
                    $consulta->bindValue(':me', $calMe, PDO::PARAM_INT);
                    $consulta->bindValue(':res', $calR, PDO::PARAM_INT);
                    $consulta->bindValue(':mo', $calMo, PDO::PARAM_INT);
                    $consulta->bindValue(':co', $calC, PDO::PARAM_INT);
                    $consulta->bindValue(':op', $opi, PDO::PARAM_STR);

                    if($consulta->execute()==true)
                        return array('msg'=>"GRACIAS POR COMPLETAR LA ENCUESTA", 'type'=>'ok');
                    
                    else
                        throw new PDOException("ERROR AL AGREGAR LA ENCUESTA");
                }
            }
            if( $existe == 0)
                throw new PDOException("EL CODIGO NO EXISTE");
                
        }
        catch( PDOException $e){
            if($e->getCode()=='23000')
                return array('msg'=>strtoupper('usted ya ha realizado la encuesta'), 'type'=>'error');
            else
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

}
?>