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
require_once './controllers/PedidoController.php';
require_once './controllers/EncuestaController.php';
require_once './db/AccesoDatos.php';

require_once './middlewares/Verificadora.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/public');
$app->addRoutingMiddleware();

$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function ($request, IResponse $response, $args) {
    $response->getBody()->write("TP La Comanda");
    return $response;
});


$app->group('/usuarios', function (RouteCollectorProxy $group) {
    $group->get('[/]', \UsuarioController::class . ':TraerTodos')
      ->add(\Verificadora::class . ':VerificarSocio');
    $group->get('/{id}', \UsuarioController::class . ':TraerUno')
      ->add(\Verificadora::class . ':VerificarSocio');;
    $group->post('[/]', \UsuarioController::class . ':CargarUno')
      ->add(\Verificadora::class . ':VerificarSocio');;
    $group->post('/modificar/{id}', \UsuarioController::class . ':ModificarUno')
      ->add(\Verificadora::class . ':VerificarSocio');;
    $group->delete('/{id}', \UsuarioController::class . ':BorrarUno')
      ->add(\Verificadora::class . ':VerificarSocio');;

    $group->post('/login', \UsuarioController::class . ':Login')
      ->add(\Verificadora::class . ':CrearJWT');
});

$app->group('/productos', function (RouteCollectorProxy $group){
  $group->get('[/]', \ProductoController::class . ':TraerTodos');
  $group->get('/{id}', \ProductoController::class . ':TraerUno');
  $group->post('[/]', \ProductoController::class . ':CargarUno')
    ->add(\Verificadora::class . ':VerificarSocio');
  $group->delete('/{id}', \ProductoController::class . ':BorrarUno')
  ->add(\Verificadora::class . ':VerificarSocio');
});

$app->group('/mesas', function (RouteCollectorProxy $group){
  $group->get('[/]', \MesaController::class . ':TraerTodos');
  $group->get('/{codigo}', \MesaController::class . ':TraerUno');
  $group->post('[/]', \MesaController::class . ':CargarUno')
    ->add(\Verificadora::class . ':VerificarSocio');
  $group->put('/pagar/{codigo}', \MesaController::class . ':PagarMesa')
    ->add(\Verificadora::class . ':VerificarMozo');
  $group->put('/cerrar/{codigo}', \MesaController::class . ':CerrarMesa')
    ->add(\Verificadora::class . ':VerificarSocio');
  $group->delete('/{codigo}', \MesaController::class . ':BorrarUno')
    ->add(\Verificadora::class . ':VerificarSocio');
  $group->post('/foto/{codigo}', \MesaController::class . ':CargarFoto');
});

$app->group('/pedidos', function (RouteCollectorProxy $group){
  $group->get('[/]', \PedidoController::class . ':TraerTodos');
  $group->get('/codigo/{codigo}', \PedidoController::class . ':TraerUno');
  $group->get('/pendientes', \PedidoController::class . ':TraerPendientes');
  $group->post('[/]', \PedidoController::class . ':CargarUno')
    ->add(\Verificadora::class . ':VerificarMozo');
  $group->put('/preparar/{codigo}/{demora}', \PedidoController::class . ':Preparar')
    ->add(\Verificadora::class . ':VerificarEmpleado');
  $group->put('/servir/{codigo}', \PedidoController::class . ':Servir')
    ->add(\Verificadora::class . ':VerificarMozo');

  $group->get('/pendientes/barradetragos', \PedidoController::class . ':TraerPendientesBarraDeTragos')
    ->add(\Verificadora::class . ':VerificarBaristas');
  $group->get('/pendientes/cocina', \PedidoController::class . ':TraerPendientesCocina')
    ->add(\Verificadora::class . ':VerificarCocinero');
  $group->get('/pendientes/barradechoperas', \PedidoController::class . ':TraerPendientesBarraDeChoperas')
    ->add(\Verificadora::class . ':VerificarBaristas');
  $group->get('/pendientes/candybar', \PedidoController::class . ':TraerPendientesCandybar')
  ->add(\Verificadora::class . ':VerificarCandybar');
});

$app->group('/encuestas', function (RouteCollectorProxy $group){
  $group->post('[/]', \EncuestaController::class . ':CargarUno');

});

// Run app
$app->run();