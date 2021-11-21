<?php
use Psr\Http\Message\ResponseInterface as IResponse;
use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;

require __DIR__ . '/../vendor/autoload.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/ProductoController.php';
require_once './controllers/MesaController.php';
require_once './db/AccesoDatos.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/TPLabo3-LaComanda/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, IResponse $response, $args) {
    $response->getBody()->write("TP La Comanda");
    return $response;
});


// usuarios
$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos');
    $group->get('/{id}', \UsuarioController::class . ':TraerUno');
    $group->post('[/]', \UsuarioController::class . ':CargarUno');
    $group->post('/{id}', \UsuarioController::class . ':ModificarUno');
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno');
});

$app->group('/productos', function (RouteCollectorProxy $group){
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{id}', \ProductoController::class . ':TraerUno');
  $group->delete('/{id}', \ProductoController::class . ':BorrarUno');
});

$app->group('/mesas', function (RouteCollectorProxy $group){
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{codigo}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno');
  $group->put('/pagar/{codigo}', \MesaController::class . ':PagarMesa');
  $group->put('/cerrar/{codigo}', \MesaController::class . ':CerrarMesa');
  $group->delete('/{codigo}', \MesaController::class . ':BorrarUno');
});

$app->group('/pedidos', function (RouteCollectorProxy $group){
  $group->post('[/]', \MesaController::class . ':CargarUno'); //TODO 

});



// Run app
$app->run();