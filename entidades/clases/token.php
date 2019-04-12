<?php

require_once './composer/vendor/autoload.php';

use Firebase\JWT\JWT;

class Token{

    private static $key = 'Hitachy93';
    private static $aud = null;
    private static $tipoEncriptacion = ['HS256'];

    public static function crearToken($param){

        $hora= time();

        $payload = array(
            "aud" => self::Aud(),
            "data" => $param,
            "iat" => $hora,
            #"exp" => $hora + 3600,
            "nbf" => 1357000000
        );
        

        return JWT::encode($payload, self::$key);

    }
    
    public static function VerificarToken($token)
    {
    
        if(empty($token))
        {   
            throw new Exception("El token esta vacio.");
        } 

      
      try {

            $decodificado = JWT::decode(  
                                            $token, 
                                            self::$key, 
                                            self::$tipoEncriptacion
                                        );
            

            if ( $decodificado ->aud  !==  self :: Aud ()){
                throw  new  Exception ( " No es el usuario valido " );
            }
            
            else
                return true;
             
        } catch (Exception $e) {
            
            throw new Exception(strtoupper($e->getMessage()));
    
        } 
       
    }
    
   
     public static function ObtenerPayLoad($token)
    {
        return JWT::decode(
            $token,
            self::$key,
            self::$tipoEncriptacion
        );
    }
     public static function ObtenerData($token)
    {
        return JWT::decode(
            $token,
            self::$key,
            self::$tipoEncriptacion
        )->data;
    }

    private static function Aud()
    {
        $aud = '';
        
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $aud = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $aud = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $aud = $_SERVER['REMOTE_ADDR'];
        }
        
        $aud .= @$_SERVER['HTTP_USER_AGENT'];
        $aud .= gethostname();
        
        return sha1($aud);
    }
}