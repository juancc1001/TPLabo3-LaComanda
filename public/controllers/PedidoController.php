<?php
require_once './models/Producto.php';
require_once './models/Pedido.php';
require_once './interfaces/IApiUsable.php';

class PedidoController extends Pedido //implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_empleado = $parametros['id_empleado'];
        $codigo_mesa = $parametros['codigo_mesa'];
        $productos = $parametros['productos'];
        $demora = $parametros['demora'];

        $array_productos = explode(',', $productos);
        $total = PedidoController::CalcularTotal($array_productos);
        $codigo_pedido = PedidoController::ObtenerCodigo();
        PedidoController::CargarPedidosEnSector($array_productos, $codigo_pedido);

        $pedido = new Pedido();
        $pedido->id_empleado = $id_empleado;
        $pedido->codigo_mesa = $codigo_mesa;
        $pedido->fecha_creacion = date('Y-m-d');
        $pedido->total = $total;
        $pedido->codigo = $codigo_pedido;
        $pedido->demora = $demora;
        $id = $pedido->crearPedido();

        $payload = json_encode(array("mensaje" => "Pedido creado con exito. Id: ".$id." Codigo: ".$codigo_pedido));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        $payload = json_encode(array("listaPedidos" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientes($request, $response, $args)
    {
        $lista = Pedido::obtenerPendientes();
        $payload = json_encode(array("listaPedidosPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesBarraDeTragos($request, $response, $args)
    {
        $lista = Pedido::obtenerPendientesPorSector("barra_de_tragos");
        $payload = json_encode(array("listaPedidosPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesBarraDeChoperas($request, $response, $args)
    {
        $lista = Pedido::obtenerPendientesPorSector("barra_de_choperas");
        $payload = json_encode(array("listaPedidosPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesCocina($request, $response, $args)
    {
        $lista = Pedido::obtenerPendientesPorSector("cocina");
        $payload = json_encode(array("listaPedidosPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerPendientesCandybar($request, $response, $args)
    {
        $lista = Pedido::obtenerPendientesPorSector("candybar");
        $payload = json_encode(array("listaPedidosPendientes" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $codigo_pedido = $args['codigo'];
        $pedido = Pedido::obtenerPedido($codigo_pedido);
        if ($pedido != "false")
        {
            $payload = json_encode($pedido);
        }else
        {
            $payload = json_encode(array("mensaje" => "Pedido no encontrado. Verifique el codigo ingresado."));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Preparar($request, $response, $args)
    {
        $codigo_pedido = $args['codigo'];
        if (isset($args['demora']))
        {
            $demora = $args['demora'];
        }else
        {
            $demora = 0;
        }
        
        $result = Pedido::actualizarPedido($codigo_pedido, 21, $demora);
        
        if($result == true)
        {
            $payload = json_encode(array("mensaje" => "Pedido en preparacion. CODIGO: ".$codigo_pedido));
        }else{
            $payload = json_encode(array("mensaje" => "Pedido no encontrado, verifique codigo"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function Servir($request, $response, $args)
    {
        $codigo_pedido = $args['codigo'];
        if (isset($args['demora']))
        {
            $demora = $args['demora'];
        }else
        {
            $demora = 0;
        }
        
        $result = Pedido::actualizarPedido($codigo_pedido, 22, $demora);

        if($result == true)
        {
            $payload = json_encode(array("mensaje" => "Pedido listo para servir. CODIGO: ".$codigo_pedido));
        }else{
            $payload = json_encode(array("mensaje" => "Pedido no encontrado, verifique codigo"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function CalcularTotal($productos)
    {
        $total = 0;
        foreach ($productos as $id_producto)
        {
            $prod_aux = Producto::obtenerProducto($id_producto);
            if($prod_aux == "false")
            {
                continue;
            }else{
                $total+=$prod_aux->precio;
            }
        }
        return $total;
    }

    public static function CargarPedidosEnSector($productos, $codigo)
    {
        foreach ($productos as $id_producto)
        {
            $prod_aux = Producto::obtenerProductoSector($id_producto);
            switch ($prod_aux['id_sector'])
            {
                case "1":
                    Producto::cargarEnSector($codigo, "barra_de_tragos", $prod_aux['id']);
                    break;
                case "2":
                    Producto::cargarEnSector($codigo, "barra_de_choperas", $prod_aux['id']);
                    break;
                case "3":
                    Producto::cargarEnSector($codigo, "cocina", $prod_aux['id']);
                    break;
                case "4":
                    Producto::cargarEnSector($codigo, "candybar", $prod_aux['id']);
                    break;
            }
        }
    }

    public static function ObtenerCodigo()
    {
        $caracteres = 'ABCDEFFHIJKLMNOPQRSTUVWXYZ0123456789';
        return substr(str_shuffle($caracteres), 0, 5);
    }



}