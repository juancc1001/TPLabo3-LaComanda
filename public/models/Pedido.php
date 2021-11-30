<?php

class Pedido
{
    public $codigo;
    public $estado;
    public $codigo_mesa;
    public $id_empleado_asignado;
    public $total;
    public $demora;
    public $fecha_creacion;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (codigo, id_estado, id_mesa, id_empleado_asignado, costo_total, fecha_creacion) VALUES (:codigo, :id_estado, :id_mesa, :id_empleado, :total, :fecha_alta)");
        $consulta->bindValue(':codigo', $this->codigo);
        $consulta->bindValue(':id_estado', 20);
        $consulta->bindValue(':id_mesa', $this->codigo_mesa);
        $consulta->bindValue(':id_empleado', $this->id_empleado);
        $consulta->bindValue(':total', $this->total);
        $consulta->bindValue(':fecha_alta', $this->fecha_creacion);

        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerPedido($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.codigo, estados.descripcion estado, pedidos.id_mesa codigo_mesa, pedidos.id_empleado_asignado, pedidos.demora, pedidos.costo_total total, pedidos.fecha_creacion FROM pedidos inner join estados on pedidos.id_estado = estados.id WHERE pedidos.codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function actualizarPedido($codigo, $id_estado, $demora)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE pedidos SET id_estado = :id_estado, demora = :demora WHERE codigo = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_STR);
        $consulta->bindValue(':demora', $demora, PDO::PARAM_INT);

        Pedido::actualizarBarraTragos($codigo, $id_estado);
        Pedido::actualizarBarraChoperas($codigo, $id_estado);
        Pedido::actualizarCocina($codigo, $id_estado);
        Pedido::actualizarCandybar($codigo, $id_estado);

        return $consulta->execute();
    }

    public static function actualizarBarraTragos($codigo, $id_estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE barra_de_tragos SET id_estado = :id_estado WHERE codigo_pedido = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $id_estado, PDO::PARAM_STR);
        $consulta->execute();
    }

    public static function actualizarBarraChoperas($codigo, $id_estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE barra_de_choperas SET id_estado = :id_estado WHERE codigo_pedido = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $id_estado);
        $consulta->execute();
    }

    public static function actualizarCocina($codigo, $id_estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE cocina SET id_estado = :id_estado WHERE codigo_pedido = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $id_estado);
        $consulta->execute();
    }

    public static function actualizarCandybar($codigo, $id_estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE candybar SET id_estado = :id_estado WHERE codigo_pedido = :codigo");
        $consulta->bindValue(':codigo', $codigo, PDO::PARAM_STR);
        $consulta->bindValue(':id_estado', $id_estado);
        $consulta->execute();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.codigo, estados.descripcion estado, pedidos.id_mesa codigo_mesa, pedidos.id_empleado_asignado, pedidos.demora, pedidos.costo_total total, pedidos.fecha_creacion FROM pedidos inner join estados on pedidos.id_estado = estados.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPendientes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT pedidos.codigo, estados.descripcion estado, pedidos.id_mesa codigo_mesa, pedidos.id_empleado_asignado, pedidos.demora, pedidos.costo_total total, pedidos.fecha_creacion FROM pedidos inner join estados on pedidos.id_estado = estados.id WHERE pedidos.id_estado = 20");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerPendientesPorSector($sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT ". $sector.".codigo_pedido, productos.nombre FROM ". $sector." inner join productos on productos.id = ". $sector.".id_producto WHERE ". $sector.".id_estado = 20");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_ASSOC);
    }
}
