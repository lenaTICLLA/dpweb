<?php
// Se incluye el archivo de conexión para poder interactuar con la base de datos
require_once("../library/conexion.php");
// tendra todas las funciones relacionadas con la tabla persona 
class UsuarioModel
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
    // Método para registrar una nueva persona en la base de datos
    public function registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password)
    {
        $consulta = "INSERT INTO persona (nro_identidad, razon_social, telefono, correo, departamento, provincia, distrito, cod_postal, direccion, rol, password) VALUES ('$nro_identidad', '$razon_social', '$telefono', '$correo', '$departamento', '$provincia', '$distrito',' $cod_postal', '$direccion', '$rol', '$password')";
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
    public function existePersona($nro_identidad)
    {
        $consulta = "SELECT *FROM persona WHERE nro_identidad='$nro_identidad'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }
    // Método para buscar a una persona por su número de identidad, normalmente usado en el inicio de sesión
    public function buscarPersonaPorNroIdentidad($nro_identidad)
    {
        $consulta = "SELECT id, razon_social, password FROM persona WHERE nro_identidad='$nro_identidad' LIMIT 1";
        $sql = $this->conexion->query($consulta);
        // devuelve los datos como un objeto
        return $sql->fetch_object();
    }


    public function verUsuarios()
    {
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }
    public function obtenerUsuarioPorId($id)
    {
        $stmt = $this->conexion->prepare("SELECT * FROM persona WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();
        return $resultado->fetch_assoc();
    }

    public function mostrarProveedores(){
        $arr_proveedores = array();
        $consulta = "SELECT * FROM persona WHERE rol = 'proveedor'";
        $sql = $this->conexion->query($consulta);

        if (!$sql) {
            error_log("Error en query(): " . $this->conexion->error);
            return $arr_proveedores;
        }
        while ($objeto = $sql->fetch_object()){
            array_push($arr_proveedores, $objeto);
        }
        return $arr_proveedores;
    }

    public function existeCorreoEnOtroUsuario($correo, $excluirId) {
        $consulta = "SELECT id FROM persona WHERE correo = ? AND id != ? LIMIT 1";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            error_log("Error en prepare(): " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("si", $correo, $excluirId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $existe = $resultado->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function existeIdentidadEnOtroUsuario($nro_identidad, $excluirId) {
        $consulta = "SELECT id FROM persona WHERE nro_identidad = ? AND id != ? LIMIT 1";
        $stmt = $this->conexion->prepare($consulta);
        if (!$stmt) {
            error_log("Error en prepare(): " . $this->conexion->error);
            return false;
        }
        $stmt->bind_param("si", $nro_identidad, $excluirId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        $existe = $resultado->num_rows > 0;
        $stmt->close();
        return $existe;
    }

    public function actualizarPersona($data)
    {
        $stmt = $this->conexion->prepare("UPDATE persona SET nro_identidad = ?, razon_social = ?, telefono = ?, correo = ?, departamento = ?, provincia = ?, distrito = ?, cod_postal = ?, direccion = ?, rol = ? WHERE id = ?");

        $stmt->bind_param(
            "ssssssssssi",
            $data['nro_identidad'],
            $data['razon_social'],
            $data['telefono'],
            $data['correo'],
            $data['departamento'],
            $data['provincia'],
            $data['distrito'],
            $data['cod_postal'],
            $data['direccion'],
            $data['rol'],
            $data['id_persona']
        );

        return $stmt->execute(); // Devuelve true si se actualizó correctamente, false si no
    }

    public function eliminarUsuario($id)
    {
        $stmt = $this->conexion->prepare("DELETE FROM persona WHERE id = ?");
        $stmt->bind_param("i", $id);
        if ($stmt->execute()) {
            return ["status" => true, "msg" => "Usuario eliminado"];
        }else {
            return ["status" => false, "msg" => "Error al eliminar usuario"];
        }
    }
}
