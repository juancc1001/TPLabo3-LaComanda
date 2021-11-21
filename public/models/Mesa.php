<?php

class Mesa
{   
    public $id;
    public $estado;

    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (id_estado, fecha_alta) VALUES (:id_estado, :fecha_alta)");
        $consulta->bindValue(':id_estado', 10, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_alta', $this->fecha_alta);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id, estados.descripcion as estado, mesas.fecha_alta FROM mesas inner join estados on mesas.id_estado = estados.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    
    public static function obtenerMesa($codigo)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT mesas.id, estados.descripcion as estado, mesas.fecha_alta FROM mesas inner join estados on mesas.id_estado = estados.id WHERE mesas.id = :id");
        $consulta->bindValue(':id', $codigo, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function update($codigo, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas set id_estado = $estado where id = :id");
        $consulta->bindValue(':id', $codigo, PDO::PARAM_STR);
        return $consulta->execute();
    }

    public static function borrarMesa($codigo)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET fecha_baja = :fecha_baja WHERE id = :id");
        $fecha = date("Y-m-d");
        $consulta->bindValue(':id', $codigo, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', $fecha);
        return $consulta->execute();
    }

}   

?>