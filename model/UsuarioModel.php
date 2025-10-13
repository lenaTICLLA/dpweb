<?php
require_once("../library/conexion.php");
class UsuarioModel{
    private $conexion;
    function __construct(){
        $this->conexion = new Conexion();
        $this->conexion = $this->conexion->connect();
    }
    public function registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password){
        $consulta = "INSERT INTO persona (nro_identidad, razon_social, telefono, correo, departamento, provincia, distrito, cod_postal, direccion, rol, password) VALUES ('$nro_identidad', '$razon_social', '$telefono', '$correo', '$departamento', '$provincia', '$distrito', '$cod_postal', '$direccion', '$rol', '$password')";
        $sql = $this->conexion->query($consulta);
        if ($sql) {
            $sql = $this->conexion->insert_id;
        }else{
            $sql = 0;
        }
        return $sql;
    }

    /*metodo para verificar si existe una persona con el numero de identidad */
    public function existePersona($nro_identidad){
        $consulta = "SELECT* FROM persona where nro_identidad = '$nro_identidad'";
        $sql = $this->conexion->query($consulta);
        return $sql->num_rows;
    }

    /*metodo para buscar una persona atraves de su numero de identidad */
    public function buscarPersonaPorNroIdentidad($nro_identidad){
        $consulta = "SELECT id, razon_social, password from persona where nro_identidad = '$nro_identidad' limit 1;";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }

    /*metodo para listar */
    public function verUsuarios(){
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona WHERE rol IN ('administrador', 'vendedor', 'cajero')";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }

    /*metodo para ver  */
    public function ver($id){
        $consulta = "SELECT * FROM persona WHERE id='$id'";
        $sql = $this->conexion->query($consulta);
        return $sql->fetch_object();
    }
    
    //metodo para actualizar
    public function actualizar($id_persona, $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol){
        $consulta = "UPDATE persona SET nro_identidad = '$nro_identidad', razon_social = '$razon_social', telefono = '$telefono', correo = '$correo', departamento = '$departamento', provincia = '$provincia', distrito = '$distrito', cod_postal = '$cod_postal', direccion = '$direccion', rol = '$rol' WHERE id = '$id_persona'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }

    //metodo para eliminar
    public function eliminar($id_persona)
    {
        $consulta = "DELETE FROM persona WHERE id='$id_persona'";
        $sql = $this->conexion->query($consulta);
        return $sql;
    }


    

    public function verClientes(){
        $arr_usuarios = array();
        $consulta = "SELECT * FROM persona WHERE rol = 'cliente'";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_usuarios, $objeto);
        }
        return $arr_usuarios;
    }



    public function verProveedores(){
        $arr_proveedor = array();
        $consulta = "SELECT * FROM persona where rol = 'proveedor'";
        $sql = $this->conexion->query($consulta);
        while ($objeto = $sql->fetch_object()) {
            array_push($arr_proveedor, $objeto);
        }
        return $arr_proveedor;
    }


}
