<?php

require_once './entidades/clases/menu/menu.php';
require_once './entidades/interfaces/Imenu.php';

class MenuApi extends Menu implements IMenu{

    public static function MostrarMenu( $request, $response, $args){

        $menu= Menu::mostrar();

        if($menu != NULL)
            return $response->withJson($menu, 200);
        
        else 
            return $response->withJson($menu, 400);


    }
}
?>