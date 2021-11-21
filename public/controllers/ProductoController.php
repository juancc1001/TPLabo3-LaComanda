<?php
require_once './models/Producto.php';
require_once './interfaces/IApiUsable.php';

class ProductoController extends Producto implements IApiUsable
{
	  public function CargarUno($request, $response, $args){}
	  public function ModificarUno($request, $response, $args){}

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
        $payload = json_encode($producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}


?>