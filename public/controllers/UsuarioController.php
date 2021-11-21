<?php
require_once './models/Usuario.php';
require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario implements IApiUsable
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $sector = $parametros['id_sector'];

        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->fecha_alta = date('Y-m-d');
        $id = $usr->crearUsuario($sector);

        $payload = json_encode(array("mensaje" => "Usuario creado con exito. Id: ".$id));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $id_usuario = $args['id'];
        $usuario = Usuario::obtenerUsuario($id_usuario);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        $payload = json_encode(array("listaUsuarios" => $lista));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function ModificarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];
        $sector = $parametros['id_sector'];
        
        $id = $args['id'];

        $usr = new Usuario();
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->id = $id;
        $usr->id_sector = $sector;
        
        $result = $usr->modificar();
        if($result){
          $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "ERROR al modificar el usuario"));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $usuarioId = $args['id'];
        $result = Usuario::borrarUsuario($usuarioId);

        if ($result){
          $payload = json_encode(array("mensaje" => "Usuario dado de baja con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "ERROR, al dar de baja el usuario"));
        }
        
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}