<?php
require_once("../library/conexion.php");

class VentaModel
{
    private $conexion;

    function __construct()
    {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    // ==================== TEMPORAL (igual que dpweb-main) ====================

    public function registrar_temporal($id_producto, $precio, $cantidad)
    {
        $stmt = $this->conexion->prepare("INSERT INTO temporal_venta (id_producto, precio, cantidad) VALUES (?, ?, ?)");
        $stmt->bind_param("idi", $id_producto, $precio, $cantidad);
        if ($stmt->execute()) {
            return $this->conexion->insert_id;
        }
        return 0;
    }

    public function actualizarCantidadTemporal($id_producto, $cantidad)
    {
        $stmt = $this->conexion->prepare("UPDATE temporal_venta SET cantidad = ? WHERE id_producto = ?");
        $stmt->bind_param("ii", $cantidad, $id_producto);
        return $stmt->execute();
    }

    public function actualizarCantidadTemporalByid($id, $cantidad)
    {
        $stmt = $this->conexion->prepare("UPDATE temporal_venta SET cantidad = ? WHERE id = ?");
        $stmt->bind_param("ii", $cantidad, $id);
        return $stmt->execute();
    }

    public function buscarTemporales()
    {
        $arr_temporal = array();
        $consulta = "SELECT tv.*, p.nombre FROM temporal_venta tv INNER JOIN producto p ON tv.id_producto = p.id";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_temporal, $objeto);
        }
        return $arr_temporal;
    }

    public function buscarTemporal($id_producto)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM temporal_venta WHERE id_producto = ?");
        $stmt->bind_param("i", $id_producto);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_object();
    }

    public function eliminarTemporal($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM temporal_venta WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    public function eliminarTemporales()
    {
        $consulta = "DELETE FROM temporal_venta";
        return $this->conexion->query($consulta);
    }

    // ==================== VENTAS (igual que dpweb-main) ====================

    public function buscar_ultima_venta()
    {
        $consulta = "SELECT codigo FROM venta ORDER BY id DESC LIMIT 1";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }

    public function registrar_venta($correlativo, $fecha_venta, $id_cliente, $id_vendedor)
    {
        $stmt = $this->conexion->prepare("INSERT INTO venta (codigo, fecha_hora, id_cliente, id_vendedor, estado) VALUES (?, ?, ?, ?, 1)");
        $stmt->bind_param("ssii", $correlativo, $fecha_venta, $id_cliente, $id_vendedor);
        if ($stmt->execute()) {
            return $this->conexion->insert_id;
        }
        return 0;
    }

    public function registrar_detalle_venta($id_venta, $id_producto, $precio, $cantidad)
    {
        $stmt = $this->conexion->prepare("INSERT INTO detalle_venta (id_venta, id_producto, precio, cantidad) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iidi", $id_venta, $id_producto, $precio, $cantidad);
        return $stmt->execute();
    }

    // ==================== CLIENTES ====================

    public function buscarClientePorDni($dni)
    {
        $stmt = $this->conexion->prepare("SELECT id, nro_identidad, razon_social, telefono, correo, direccion FROM persona WHERE nro_identidad = ?");
        $stmt->bind_param("s", $dni);
        $stmt->execute();
        $resultado = $stmt->get_result();
        return $resultado->fetch_object();
    }

    public function registrarClienteRapido($nro_identidad, $razon_social, $direccion = '')
    {
        // Verificar si ya existe
        $existe = $this->buscarClientePorDni($nro_identidad);
        if ($existe) {
            return $existe->id;
        }

        $password = password_hash($nro_identidad, PASSWORD_DEFAULT);
        $rol = '2';

        $stmt = $this->conexion->prepare("INSERT INTO persona (nro_identidad, razon_social, direccion, rol, password, estado) VALUES (?, ?, ?, ?, ?, 1)");
        $stmt->bind_param("sssss", $nro_identidad, $razon_social, $direccion, $rol, $password);

        if ($stmt->execute()) {
            return $this->conexion->insert_id;
        }
        return 0;
    }
}