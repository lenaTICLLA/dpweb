<?php
// Se incluye el archivo de conexión para poder interactuar con la base de datos
require_once("../library/conexion.php");

// Clase con todas las funciones relacionadas con la tabla producto 
class ProductoModel
{
    // Atributo privado para almacenar la conexión con la base de datos
    private $conexion;
    // Constructor de la clase: se ejecuta automáticamente al crear un objeto de esta clase
    function __construct()
    {
        // Se crea una nueva instancia de la clase Conexion
        $this->conexion = new Conexion();
        // Se establece la conexión y se guarda en el atributo $conexion
        $this->conexion = $this->conexion->connect();
    }
    // Método para registrar un nuevo producto en la base de datos
    public function registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen, $id_categoria = NULL, $id_proveedor = NULL)
    {
        $consulta = "INSERT INTO producto (codigo, nombre, detalle, precio, stock, fecha_vencimiento, imagen, id_categoria, id_proveedor) VALUES ('$codigo','$nombre', '$detalle', '$precio', '$stock', '$fecha_vencimiento', ". ($imagen ? "'$imagen'" : "NULL") . ", " . ($id_categoria ? "'$id_categoria'" : "NULL") . ", " . ($id_proveedor ? "'$id_proveedor'" : "NULL") . ")";
        // Se ejecuta la consulta
        $sql = $this->conexion->query($consulta);
        // Si se ejecuta correctamente, devuelve el ID del nuevo registro insertado
        if ($sql) {
            $sql = $this->conexion->insert_id;
        } else {
            $sql = 0;
        }
        return $sql;
    }
    public function existeProducto($nombre)
    {
        $consulta = "SELECT * FROM producto WHERE nombre='$nombre'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }

    public function verProductos() {
        $arr_productos = array();
        $consulta = "SELECT * FROM producto";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
           array_push($arr_productos, $objeto);
        }
        return $arr_productos;
    }
 public function obtenerProductoPorId($id) {
        $stmt = $this->conexion->prepare("SELECT * FROM producto WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function buscarPorNombre($nombre) {
    $stmt = $this->conexion->prepare("SELECT id FROM producto WHERE nombre = ?");
    $stmt->bind_param("s", $nombre);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
}
// actualizar 
public function actualizarProducto($data) {
    // Depuración antes de la consulta
    error_log("Datos recibidos en modelo: " . print_r($data, true));
    error_log("Tipo de dato de detalle: " . gettype($data['detalle']));
    error_log("Valor de detalle antes de bind: " . var_export($data['detalle'], true));

    $stmt = $this->conexion->prepare("UPDATE producto SET codigo = ?, nombre = ?, detalle = ?, precio = ?, stock = ?, fecha_vencimiento = ?, imagen = ? WHERE id = ?");
    
    if ($stmt === false) {
        error_log("Error al preparar la consulta: " . $this->conexion->error);
        return false;
    }

    $stmt->bind_param(
        "ssssdssi",
        $data['codigo'],
        $data['nombre'],
        $data['detalle'],
        $data['precio'],
        $data['stock'],
        $data['fecha_vencimiento'],
        $data['imagen'],
        $data['id_producto']
    );

    $resultado = $stmt->execute();
    if ($resultado === false) {
        error_log("Error al ejecutar la consulta: " . $this->conexion->error);
    } else {
        error_log("Consulta ejecutada con éxito");
    }

    // Depuración después de la consulta
    $stmt->close();
    return $resultado;
}

// eliminar producto 
public function eliminarProducto($id) {
    $stmt = $this->conexion->prepare("DELETE FROM producto WHERE id = ?");
    $stmt->bind_param("i", $id); // "i" porque es un número entero (id)
    
    if ($stmt->execute()) {
        return ["status" => true, "msg" => "Producto eliminado correctamente"];
    } else {
        return ["status" => false, "msg" => "Error al eliminar el producto"];
    }
}

}