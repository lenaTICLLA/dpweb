<?php
require_once("../library/conexion.php");
class ProductoModel
{
    private $conexion;
    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    public function registrar($codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $fecha_vencimiento, $imagen, $id_proveedor)
    {
        $codigo            = $this->conexion->real_escape_string($codigo);
        $nombre            = $this->conexion->real_escape_string($nombre);
        $detalle           = $this->conexion->real_escape_string($detalle);
        $precio            = floatval($precio);
        $stock             = intval($stock);
        $id_categoria      = intval($id_categoria);
        $fecha_vencimiento = $this->conexion->real_escape_string($fecha_vencimiento);
        $id_proveedor      = intval($id_proveedor);
        $imagen            = $this->conexion->real_escape_string($imagen);
        $consulta = "INSERT INTO producto (codigo, nombre, detalle, precio, stock, id_categoria, fecha_vencimiento, imagen, id_proveedor) VALUES ('$codigo', '$nombre', '$detalle', $precio, $stock, $id_categoria, '$fecha_vencimiento', '$imagen', '$id_proveedor')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
    /*
    public function registrar($codigo, $nombre, $detalle, $precio, $stock, $id_categoria,$fecha_vencimiento, $imagen, $id_proveedor){
        $consulta = "INSERT INTO producto (codigo, nombre, detalle, precio, stock, id_categoria, fecha_vencimiento, imagen, id_proveedor) VALUES ('$codigo', '$nombre', '$detalle', '$precio', '$stock', '$id_categoria', $fecha_vencimiento, $imagen '$id_proveedor')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            $sql = $this->conexion->insert_id;
        }else{
            $sql = 0;
        }
        return $sql;
    }*/

    public function existeProducto($codigo)
    {
        $consulta = "SELECT* FROM producto where codigo = '$codigo'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }


    /*metodo para buscar una producto atraves de codigo */
    public function buscarProductoPorNroCodigo($codigo)
    {
        $consulta = "SELECT codigo from producto where codigo = '$codigo' limit 1;";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }

    //metodo para listar producto
    public function verProductos()
    {
        $arr_productos = array();
        $consulta = "SELECT * from producto";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_productos, $objeto);
        }
        return $arr_productos;
    }

    //metodo para ver producto
    public function ver($id)
    {
        $consulta = "SELECT * FROM producto WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }

    //metodo para actualizar
    public function actualizar($id_producto, $codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $fecha_vencimiento, $id_proveedor)
    {
    $consulta = "UPDATE producto SET codigo = '$codigo', nombre = '$nombre', detalle = '$detalle', precio = '$precio', stock = '$stock', id_categoria = '$id_categoria', fecha_vencimiento = '$fecha_vencimiento', id_proveedor = '$id_proveedor' WHERE id = '$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    //metodo para eliminar
    public function eliminar($id_producto)
    {
        $consulta = "DELETE FROM producto WHERE id='$id_producto'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }
}
