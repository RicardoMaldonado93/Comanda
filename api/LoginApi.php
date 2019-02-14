<?php

require_once './entidades/clases/login.php';
require_once './entidades/interfaces/Ilogin.php';

class LoginApi extends Login implements ILogin{

    public static function Login($request, $response, $args){
        $datos= $request->getParsedBody();
        $login = Login::Loguear($datos['usuario'],$datos['pass']);

        if($login != NULL)
            return $response->withJson($login, 200);
        else
            return $response->withStatus(400);
    }

}

?>