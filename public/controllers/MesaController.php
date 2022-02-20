<?php
use Psr\Http\Message\UploadedFileInterface;

require_once './models/Mesa.php';
require_once './interfaces/IApiUsable.php';

class MesaController extends Mesa //implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $mesa = new Mesa();
        $mesa->fecha_alta = date('Y-m-d');
        $id = $mesa->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa dada de alta con exito. Código: ".$id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function CargarFoto($request, $response, $args)
    {
        $uploadedFiles = $request->getUploadedFiles();
        $uploadedFile = $uploadedFiles['foto'];

        if ($uploadedFile->getError() === UPLOAD_ERR_OK) {
            $filename = MesaController::moveUploadedFile(__DIR__.'\fotos_mesas', $uploadedFile, $args['codigo']);
            $response->getBody()->write('Uploaded: ' . $filename . '<br/>');
        }

        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, string $codigoMesa)
{
    $extension = pathinfo($uploadedFile->getClientFilename(), PATHINFO_EXTENSION);
    $filename =  $codigoMesa.".".$extension;

    $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);
    return $filename;
}

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        $payload = json_encode(array("listaMesas" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $mesa = Mesa::obtenerMesa($codigo);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function MasUsada($request, $response, $args)
    {
        $pedido = Mesa::obtenerMasUsada();
        $id_mesa = $pedido->id_mesa;
        $mesa = Mesa::obtenerMesa($id_mesa);
        $payload = json_encode($mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function PagarMesa($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $result = Mesa::update($codigo, 13);
        if ($result){
            $payload = json_encode(array("mensaje"=> "Cliente pagando mesa."));
        }else{
            $payload = json_encode(array("mensaje"=> "Error al pagar mesa, verifique el código."));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function CerrarMesa($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $result = Mesa::update($codigo, 14);
        if ($result){
            $payload = json_encode(array("mensaje"=> "Mesa cerrada con exito."));
        }else{
            $payload = json_encode(array("mensaje"=> "Error al cerrar mesa, verifique el código."));
        }

        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $codigo = $args['codigo'];
        $result = Mesa::borrarMesa($codigo);

        if ($result){
          $payload = json_encode(array("mensaje" => "Mesa dada de baja con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "ERROR, al dar de baja la mesa"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}

?>