<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './controllers/TokenController.php';

class Verificadora
{
    public function CrearJWT (Request $request, RequestHandler $handler)
    {
        $response = $handler->handle($request);
    
        $estado = $response->getStatusCode();
    
        if ($estado >= 200 && $estado <= 299)
        {
            $body = $response->getBody();
            $datos = json_decode($body, TRUE);
            $token = TokenController::CrearToken($datos);
            $payload = json_encode(array('token' => $token));
            $response = new Response();
            $response->getBody()->write($payload);
        }
    
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function VerificarSocio (Request $request, RequestHandler $handler)
    {
        $response = new Response();

        $header = $request->getHeaderLine('Authorization');

        if (empty($header))
        {
            $payload = json_encode(array("mensaje" => "ERROR: Sin token."));
        }
        else
        {
            $token = trim(explode("Bearer", $header)[1]);
            try {
                $data = TokenController::ObtenerData($token);

                if ($data->id_sector == "5")
                {
                    $request = $request->withAttribute("id_usuario", $data->id);
                    $request = $request->withAttribute("id_sector", $data->id_sector);

                    return $handler->handle($request);
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "ERROR: Acceso denegado."));
                }
            }
            catch (Exception $e) {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }           
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }

    public function VerificarMozo (Request $request, RequestHandler $handler)
    {
        $response = new Response();

        $header = $request->getHeaderLine('Authorization');

        if (empty($header))
        {
            $payload = json_encode(array("mensaje" => "ERROR: Sin token."));
        }
        else
        {
            $token = trim(explode("Bearer", $header)[1]);
            try {
                $data = TokenController::ObtenerData($token);

                if ($data->id_sector == "6")
                {
                    $request = $request->withAttribute("id_usuario", $data->id);
                    $request = $request->withAttribute("id_sector", $data->id_sector);

                    return $handler->handle($request);
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "ERROR: Acceso denegado."));
                }
            }
            catch (Exception $e) {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }           
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }
    
    public function VerificarCocinero (Request $request, RequestHandler $handler)
    {
        $response = new Response();

        $header = $request->getHeaderLine('Authorization');

        if (empty($header))
        {
            $payload = json_encode(array("mensaje" => "ERROR: Sin token."));
        }
        else
        {
            $token = trim(explode("Bearer", $header)[1]);
            try {
                $data = TokenController::ObtenerData($token);

                if ($data->id_sector == "3")
                {
                    $request = $request->withAttribute("id_usuario", $data->id);
                    $request = $request->withAttribute("id_sector", $data->id_sector);

                    return $handler->handle($request);
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "ERROR: Acceso denegado."));
                }
            }
            catch (Exception $e) {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }           
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }

    public function VerificarBaristas (Request $request, RequestHandler $handler)
    {
        $response = new Response();

        $header = $request->getHeaderLine('Authorization');

        if (empty($header))
        {
            $payload = json_encode(array("mensaje" => "ERROR: Sin token."));
        }
        else
        {
            $token = trim(explode("Bearer", $header)[1]);
            try {
                $data = TokenController::ObtenerData($token);

                if ($data->id_sector == "1" || $data->id_sector == "2")
                {
                    $request = $request->withAttribute("id_usuario", $data->id);
                    $request = $request->withAttribute("id_sector", $data->id_sector);

                    return $handler->handle($request);
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "ERROR: Acceso denegado."));
                }
            }
            catch (Exception $e) {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }           
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }

    public function VerificarCandybar (Request $request, RequestHandler $handler)
    {
        $response = new Response();

        $header = $request->getHeaderLine('Authorization');

        if (empty($header))
        {
            $payload = json_encode(array("mensaje" => "ERROR: Sin token."));
        }
        else
        {
            $token = trim(explode("Bearer", $header)[1]);
            try {
                $data = TokenController::ObtenerData($token);

                if ($data->id_sector == "4")
                {
                    $request = $request->withAttribute("id_usuario", $data->id);
                    $request = $request->withAttribute("id_sector", $data->id_sector);

                    return $handler->handle($request);
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "ERROR: Acceso denegado."));
                }
            }
            catch (Exception $e) {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }           
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }

    public function VerificarEmpleado (Request $request, RequestHandler $handler)
    {
        $response = new Response();

        $header = $request->getHeaderLine('Authorization');

        if (empty($header))
        {
            $payload = json_encode(array("mensaje" => "ERROR: Sin token."));
        }
        else
        {
            $token = trim(explode("Bearer", $header)[1]);
            try {
                $data = TokenController::ObtenerData($token);

                if ($data->id_sector != "" && !is_null($data->id_sector))
                {
                    $request = $request->withAttribute("id_usuario", $data->id);
                    $request = $request->withAttribute("id_sector", $data->id_sector);

                    return $handler->handle($request);
                }
                else
                {
                    $payload = json_encode(array("mensaje" => "ERROR: Acceso denegado."));
                }
            }
            catch (Exception $e) {
                $payload = json_encode(array("mensaje" => $e->getMessage()));
            }           
        }

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json')->withStatus(403);
    }
}