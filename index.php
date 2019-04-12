<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once './composer/vendor/autoload.php';
require_once './api/LoginApi.php';
require_once './api/MenuApi.php';
require_once './api/PersonalApi.php';
require_once './api/PedidosApi.php';
require_once './api/LogApi.php';
require_once './api/MesasApi.php';
require_once './api/ClienteApi.php';
require_once './entidades/clases/auth.php';

$config['displayErrorDetails'] = true;
$config['addContentLengthHeader'] = true;

$app = new \Slim\App(["settings" => $config]);

$app->post('/api/login[/]', \LoginApi::class . ':Login');
$app->get('/api/menu[/]', \MenuApi::class . ':MostrarMenu');
$app->group('/api/cliente', function(){
    $this->get('/miPedido[/]', \ClienteApi::class . ':VerMiPedido');
    $this->get('/encuesta[/]', \ClienteApi::calss . ':MostrarEncuesta' );
});

$app->group('/api' , function(){

    $this->group('/empleado', function(){

        //ABM de empleados
        $this->post('[/]', \PersonalApi::class . ':Agregar')->add(\MWAuth::class . ':VerificarUsuario');
        $this->delete('[/]', \PersonalApi::class . ':Eliminar');
        $this->put('[/]', \PersonalApi::class . ':Modificar');

        //Listas de empleados 
        $this->get('[/]', \PersonalApi::class . ':MostrarTodos');
        $this->get('/{id}', \PersonalApi::class . ':MostrarUno');

        //Cambios de estado y puesto de empleados 
        $this->put('/suspender[/]', \PersonalApi::class . ':Suspender');
        $this->put('/cambiarEstado[/]', \PersonalApi::class . ':CambiarEstado');
        $this->put('/cambiarPuesto[/]', \PersonalApi::class . ':CambiarPuesto');

    })->add(\MWAuth::class . ':Auth');

    $this->group('/pedido', function(){

        //Operaciones con pedidos
        $this->post('[/]', \PedidosApi::class . ':TomarPedido');
        $this->put('[/]', \PedidosApi::class . ':Preparar');
        //$this->put('/', \PedidosApi::class . ':ListoParaServir');
        $this->put('/cancelar[/]', \PedidosApi::class . ':Cancelar');
        $this->put('/servir[/]', \PedidosApi::class . ':ListoParaServir');
        $this->delete('/entregado', \PedidosApi::class . ':Entregar');
        $this->put('/agregar[/]', \PedidosApi::class . ':AgregarAPedido');

        //Listados de pedidos gral y por sector
        $this->get('/{id}', \PedidosApi::class . ':MostrarPedido');
        $this->get('[/]', \PedidosApi::class . ':MostrarPedidos');
        $this->get('/estado/{es}[/]', \PedidosApi::class . ':MostrarEstado');
        $this->get('/sector/{se}[/]', \PedidosApi::class . ':MostrarSector');
    
    })->add(\MWAuth::class . ':Auth');

    $this->group('/mesa', function(){

        $this->post('[/]', \MesasApi::class . ':AgregarMesa');
        $this->put('[/]', \MesasApi::class . ':ModificarMesa');
        $this->delete('[/]', \MesasApi::class . ':EliminarMesa');

        $this->group('/listado', function(){
        
            $this->get('[/]', \MesasApi::class . ':MostrarMesas');
            $this->get('/mayorImporte[/]', \MesasApi::class . ':MostrarMayorImporte'); #muestra 1 o mas mesas con mayor importe
            $this->get('/mayorFacturacion[/]',\MesasApi::class . ':MostrarMayorFacturacion'); #muestra la mesa con mayor facturacion
            $this->get('/mayorCalificacion[/]', \MesasApi::class . ':MostrarMayorCalificacion');
            $this->get('/masUsada[/]', \MesasApi::class . ':MostrarMasUsada'); #muestro la mesa con mas uso

            $this->get('/menorImporte[/]', \MesasApi::class . ':MostrarMenorImporte'); #muestra la mesa con el menor importe
            $this->get('/menorFacturacion[/]', \MesasApi::class . ':MostrarMenorFacturacion'); #muestra la mesa con la menor facturacion
            $this->get('/menorCalificacion[/]', \MesasApi::class . ':MostrarMenorCalificacion');
            $this->get('/menosUsada[/]', \MesasApi::class . ':MostrarMenosUsada'); #muestro la mesa menos usada
        });

    });

})->add(\LogApi::class . ':Registro');

$app->run();
?>