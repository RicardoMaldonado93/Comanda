<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './composer/vendor/autoload.php';
require_once './api/LoginApi.php';
require_once './api/MenuApi.php';
require_once './api/PersonalApi.php';
require_once './api/PedidosApi.php';


$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);


$app->group('/api' , function(){

    $this->post('/login[/]', \LoginApi::class . ':Login');
    
    $this->get('/menu[/]', \MenuApi::class . ':MostrarMenu');
    
    $this->group('/empleado', function(){

        //ABM de empleados
        $this->post('[/]', \PersonalApi::class . ':Agregar');
        $this->delete('[/]', \PersonalApi::class . ':Eliminar');
        $this->put('[/]', \PersonalApi::class . ':Modificar');

        //Listas de empleados 
        $this->get('[/]', \PersonalApi::class . ':MostrarTodos');
        $this->get('/{id}', \PersonalApi::class . ':MostrarUno');

        //Cambios de estado y puesto de empleados 
        $this->put('/suspender[/]', \PersonalApi::class . ':Suspender');
        $this->put('/cambiarEstado[/]', \PersonalApi::class . ':CambiarEstado');
        $this->put('/cambiarPuesto[/]', \PersonalApi::class . ':CambiarPuesto');
    });

    $this->group('/pedido', function(){

        //Operaciones con pedidos
        $this->post('[/]', \PedidosApi::class . ':TomarPedido');
        $this->put('[/]', \PedidosApi::class . ':Preparar');
        //$this->put('/', \PedidosApi::class . ':ListoParaServir');
        $this->put('/cancelar[/]', \PedidosApi::class . ':Cancelar');
        $this->put('/servir[/]', \PedidosApi::class . ':ListoParaServir');
        $this->delete('/entregado', \PedidosApi::class . ':Entregar');

        //Listados de pedidos gral y por sector
        $this->get('/{id}', \PedidosApi::class . ':MostrarPedido');
        $this->get('[/]', \PedidosApi::class . ':MostrarPedidos');
        $this->get('/estado/{es}[/]', \PedidosApi::class . ':MostrarEstado');
        $this->get('/sector/{se}[/]', \PedidosApi::class . ':MostrarSector');
      
    
    });
    
    $this->get('/miPedido', \PedidosApi::class . ':VerMiPedido');
});

$app->run();
?>