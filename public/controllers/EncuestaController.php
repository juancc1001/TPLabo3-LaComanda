<?php
use Psr\Http\Message\UploadedFileInterface;

require_once './models/Mesa.php';
require_once './models/Encuesta.php';
require_once './interfaces/IApiUsable.php';

class EncuestaController extends Encuesta //implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['codigo_mesa'];
        $puntaje_mesa = $parametros['puntaje_mesa'];
        $puntaje_restaurante = $parametros['puntaje_restaurante'];
        $puntaje_mozo = $parametros['puntaje_mozo'];
        $puntaje_cocinero = $parametros['puntaje_cocinero'];
        $texto = $parametros['texto'];

        $mesa = Mesa::obtenerMesa($id_mesa);
        if($mesa == false){
            $payload = json_encode(array("mensaje" => "La mesa ingresada no existe"));
        }else if($mesa->estado == 'cerrada')
        {
            $encuesta = new Encuesta();
            $encuesta->id_mesa = $id_mesa;
            $encuesta->puntaje_mesa = $puntaje_mesa;
            $encuesta->puntaje_restaurante = $puntaje_restaurante;
            $encuesta->puntaje_mozo = $puntaje_mozo;
            $encuesta->puntaje_cocinero = $puntaje_cocinero;
            $encuesta->texto = $texto;
            $id = $encuesta->crearEncuesta();
    
            $payload = json_encode(array("mensaje" => "Encuesta creada con exito. Id: ".$id));
        }else{
            $payload = json_encode(array("mensaje" => "La mesa ingresada aun no esta cerrada"));
        }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

}