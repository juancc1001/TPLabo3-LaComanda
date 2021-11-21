<?php

class Usuario
{
    public $id;
    public $usuario;
    public $clave;
    public $fecha_alta;

    public function crearUsuario($id_sector)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (id_sector, nombre, clave, fecha_alta) VALUES (:id_sector, :usuario, :clave, :fecha_alta)");
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':id_sector', strval($id_sector));
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $consulta->bindValue(':clave', $claveHash);
        $consulta->bindValue(':fecha_alta', $this->fecha_alta);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.nombre usuario, usuarios.clave, usuarios.fecha_alta, sectores.nombre_sector FROM usuarios inner join sectores on usuarios.id_sector = sectores.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($id_usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT usuarios.id, usuarios.nombre usuario, usuarios.clave, usuarios.fecha_alta, sectores.nombre_sector FROM usuarios inner join sectores on usuarios.id_sector = sectores.id WHERE usuarios.id = :id");
        $consulta->bindValue(':id', $id_usuario, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Usuario');
    }

    public function modificar()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET nombre = :usuario, clave = :clave, id_sector = :id_sector WHERE id = :id");
        $consulta->bindValue(':usuario', $this->usuario, PDO::PARAM_STR);
        $claveHash = password_hash($this->clave, PASSWORD_DEFAULT);
        $consulta->bindValue(':clave', $claveHash, PDO::PARAM_STR);
        $consulta->bindValue(':id', $this->id, PDO::PARAM_INT);
        $consulta->bindValue(':id_sector', $this->id_sector, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fecha_baja = :fecha_baja WHERE id = :id");
        $fecha = date("Y-m-d");
        $consulta->bindValue(':id', $usuario, PDO::PARAM_INT);
        $consulta->bindValue(':fecha_baja', $fecha);
        return $consulta->execute();
    }
}