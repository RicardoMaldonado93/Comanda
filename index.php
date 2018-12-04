<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './vendor/autoload.php';
require_once './api/MenuApi.php';
require_once './api/PersonalApi.php';
require_once './api/PedidosApi.php';
/*require_once './api/UsuarioApi.php';
require_once './api/LoginApi.php';
require_once './api/LogApi.php';
require_once './api/CompraApi.php';
require_once './clases/MWAuth.php';*/

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = false;

$app = new \Slim\App(["settings" => $config]);

/*
$app->post('/api/login', \loginApi::class . ':Login');

$app->group('/api', function(){

   
s
    $this->group('/usuario', function(){
        $this->post('[/]', \UsuarioApi::class . ':AgregarUsr');
        $this->get('[/]', \UsuarioApi::class . ':MostrarUsr')->add( \MWAuth::class . ':Auth');
    });
    
    $this->group('/compra', function(){
        $this->post('[/]', \CompraApi::class . ':CargarCompra')->add(\MWAuth::class . ':VerificarUsuario');
        $this->get('[/]', \CompraApi::Class . ':MostrarListado')->add(\MWAuth::class . ':Auth');
        $this->get('/{marca}', \CompraApi::Class . ':MostrarMarca')->add(\MWAuth::class . ':Auth');
    });
    $this->get('/productos', \CompraApi::class . ':ListarProductos');
    
})->add(\LogApi::class . ':Registro');*/

$app->group('/api' , function(){
    
    $this->get('/menu[/]', \MenuApi::class . ':MostrarMenu');

    $this->group('/empleado', function(){
        $this->post('[/]', \PersonalApi::class . ':Agregar');
        $this->delete('[/]', \PersonalApi::class . ':Eliminar');
        $this->put('[/]', \PersonalApi::class . ':Modificar');

        $this->get('[/]', \PersonalApi::class . ':MostrarTodos');
        $this->get('/{id}', \PersonalApi::class . ':MostrarUno');

        $this->put('/suspender[/]', \PersonalApi::class . ':Suspender');
        $this->put('/cambiarEstado[/]', \PersonalApi::class . ':CambiarEstado');
        $this->put('/cambiarPuesto[/]', \PersonalApi::class . ':CambiarPuesto');
    });

    $this->group('/pedido', function(){
        $this->post('[/]', \PedidosApi::class . ':TomarPedido');
        $this->put('[/]', \PedidosApi::class . ':Preparar');
        $this->put('/cancelar[/]', \PedidosApi::class . ':Cancelar');
        $this->get('/servir[/]', \PedidosApi::class . ':Servir');
        $this->get('/{id}', \PedidosApi::class . ':MostrarPedido');
        $this->get('[/]', \PedidosApi::class . ':MostrarPedidos');
    });
});

$app->run();
?>