<?php

class Producto
{   
    public $id;
    public $nombre;
    public $tipo;
    public $precio;

    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.id, productos.nombre, productos.precio, productos.tipo, sectores.nombre_sector, if (productos.disponible = 0, 'Si', 'No' ) as disponible FROM productos inner join sectores on productos.id_sector = sectores.id");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function borrarProducto($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE productos SET disponible = 0 WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        return $consulta->execute();
    }

    public static function obtenerProducto($id_producto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT productos.id, productos.nombre, productos.precio, productos.tipo FROM productos WHERE productos.id = :id");
        $consulta->bindValue(':id', $id_producto, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Producto');
    }


}

?>