<?php
require_once("../library/conexion.php");

class SesionModel {
    private $conexion;

    public function __construct() {
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }

    /*Registrar sesión de usuario */
    public function registrar($id_persona, $fecha_hora_inicio, $fecha_hora_fin, $token, $ip) {
        $sql = "INSERT INTO sesiones (id_persona, fecha_hora_inicio, fecha_hora_fin, token, ip) 
                VALUES (?, ?, ?, ?, ?)";
        $query = $this->conexion->prepare($sql);
        $query->bind_param("issss", $id_persona, $fecha_hora_inicio, $fecha_hora_fin, $token, $ip);
        $query->execute();
        return $query->insert_id;
    }

    /*Verificar si ya existe una sesión activa del usuario */
    public function existeSesion($id_persona) {
        $sql = "SELECT id_sesion FROM sesiones WHERE id_persona = ?";
        $query = $this->conexion->prepare($sql);
        $query->bind_param("i", $id_persona);
        $query->execute();
        $result = $query->get_result();
        return $result->num_rows;
    }

    /*Verificar usuario y contraseña para iniciar sesión */
    public function iniciarSesion($usuario, $clave) {
        // Asegúrate que esta tabla y columnas existan en tu BD
        $sql = "SELECT id_persona, usuario, clave, nombre, apellido, rol 
                FROM usuarios 
                WHERE usuario = ? 
                LIMIT 1";
        $query = $this->conexion->prepare($sql);
        $query->bind_param("s", $usuario);
        $query->execute();
        $result = $query->get_result();

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();

            // Verificar contraseña (si está cifrada)
            if (password_verify($clave, $data['clave'])) {
                return $data; // Login correcto
            }

            // Si no usas password_hash(), compara texto plano:
            if ($clave === $data['clave']) {
                return $data; //Login correcto (texto plano)
            }
        }

        return false; //  Login fallido
    }
}
