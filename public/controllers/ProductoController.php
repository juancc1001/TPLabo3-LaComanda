<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
	  public function ModificarUno($request, $response, $args){}

    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_sector = $parametros['id_sector'];
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        $tipo = $parametros['tipo'];

        $producto = new Producto();
        $producto->nombre = $nombre;
        $producto->precio = $precio;
        $producto->tipo = $tipo;
        $id = $producto->crearProducto($id_sector);

        $payload = json_encode(array("mensaje" => "Producto creado con exito. Id: ".$id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        $payload = json_encode(array("listaProductos" => $lista));

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $id = $args['id'];
        $result = Producto::borrarProducto($id);

        if ($result){
          $payload = json_encode(array("mensaje" => "Producto borrado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "ERROR, al eliminar el producto"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $id_producto = $args['id'];
        $producto = Producto::obtenerProducto($id_producto);
        if ($producto == "false")
        {
          $payload = json_encode($producto);
        }else{
          $payload = json_encode(array("mensaje"=>"producto no encontrado"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}


?>