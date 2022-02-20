<?php

class Encuesta
{
    public $puntaje_mesa;
    public $puntaje_restaurante;
    public $puntaje_mozo;
    public $puntaje_cocinero;
    public $texto;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (puntaje_mesa, puntaje_restaurante, puntaje_mozo, puntaje_cocinero, texto) VALUES (:puntaje_mesa, :puntaje_restaurante, :puntaje_mozo, :puntaje_cocinero, :texto)");
        $consulta->bindValue(':puntaje_mesa', $this->puntaje_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntaje_restaurante', $this->puntaje_restaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntaje_mozo', $this->puntaje_mozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntaje_cocinero', $this->puntaje_cocinero, PDO::PARAM_INT);
        $consulta->bindValue(':texto', $this->texto, PDO::PARAM_STR);
        
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerMejoresPuntajes()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT puntaje_mesa, puntaje_restaurante, puntaje_mozo, puntaje_cocinero, texto FROM encuestas ORDER BY puntaje_restaurante DESC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

}