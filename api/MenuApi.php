<?php

require_once './entidades/clases/menu/menu.php';
require_once './entidades/interfaces/Imenu.php';

class MenuApi extends Menu implements IMenu{

    public static function MostrarMenu( $request, $response, $args){

        $menu= Menu::mostrar();

        if($menu != NULL)
            $newResponse = $response->withJson($menu, 200);
        
        else 
            $newResponse = $response->withJson($menu, 400);

        return $newResponse;

    }
}
?>