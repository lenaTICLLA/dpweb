<?php
require_once("../model/UsuarioModel.php");

$objPersona = new UsuarioModel();
$tipo = $_GET['tipo'] ?? '';

/* === REGISTRAR PERSONA === */
if ($tipo == 'registrar') {
   $nro_identidad = $_POST['nro_identidad'];
   $razon_social = $_POST['razon_social'];
   $telefono = $_POST['telefono'];
   $correo = $_POST['correo'];
   $departamento = $_POST['departamento'];
   $provincia = $_POST['provincia'];
   $distrito = $_POST['distrito'];
   $cod_postal = $_POST['cod_postal'];
   $direccion = $_POST['direccion'];
   $rol = $_POST['rol'];
   $password = password_hash($nro_identidad, PASSWORD_DEFAULT);

   if (
      $nro_identidad == "" || $razon_social == "" || $telefono == "" || $correo == "" ||
      $departamento == "" || $provincia == "" || $distrito == "" ||
      $cod_postal == "" || $direccion == "" || $rol == ""
   ) {
      $arrResponse = array('status' => false, 'msg' => 'Error, campos vacíos');
   } else {
      $existePersona = $objPersona->existePersona($nro_identidad);
      if ($existePersona > 0) {
         $arrResponse = array('status' => false, 'msg' => 'Error, nro de documento ya existe');
      } else {
         $respuesta = $objPersona->registrar(
            $nro_identidad,
            $razon_social,
            $telefono,
            $correo,
            $departamento,
            $provincia,
            $distrito,
            $cod_postal,
            $direccion,
            $rol,
            $password
         );
         if ($respuesta) {
            $arrResponse = array('status' => true, 'msg' => 'Registrado correctamente');
         } else {
            $arrResponse = array('status' => false, 'msg' => 'Error, fallo en el registro');
         }
      }
   }
   echo json_encode($arrResponse);
   exit;
}

/* === INICIAR SESIÓN === */
if ($tipo == "iniciar_sesion") {
   $nro_identidad = $_POST['username'] ?? '';
   $password = $_POST['password'] ?? '';

   if ($nro_identidad == "" || $password == "") {
      $respuesta = array('status' => false, 'msg' => 'Error, campos vacíos');
   } else {
      $existePersona = $objPersona->existePersona($nro_identidad);
      if (!$existePersona) {
         $respuesta = array('status' => false, 'msg' => 'Error, usuario no registrado');
      } else {
         $persona = $objPersona->buscarPersonaPorNroIdentidad($nro_identidad);
         if ($persona && password_verify($password, $persona->password)) {
            session_start();
            $_SESSION['ventas_id'] = $persona->id;
            $_SESSION['ventas_usuario'] = $persona->razon_social;
            $_SESSION['rol'] = $persona->rol ?? '';
            $respuesta = array('status' => true, 'msg' => 'Inicio de sesión exitoso');
         } else {
            $respuesta = array('status' => false, 'msg' => 'Error, contraseña incorrecta');
         }
      }
   }
   echo json_encode($respuesta);
   exit;
}

/* === VER USUARIOS === */
if ($tipo == "ver_usuarios") {
   $usuarios = $objPersona->verUsuarios();
   echo json_encode($usuarios);
   exit;
}

/* === OBTENER USUARIO POR ID === */
if ($tipo == 'obtener_usuario') {
   header('Content-Type: application/json');
   $id = $_GET['id'];
   $usuario = $objPersona->obtenerUsuarioPorId($id);
   echo json_encode($usuario);
   exit;
}

/* === ACTUALIZAR USUARIO === */
if ($tipo == "actualizar_usuario") {
   $data = $_POST;
   $modelo = new UsuarioModel();
   $nro = $data['nro_identidad'];
   $id_actual = $data['id_persona'];
   $verificar = $modelo->buscarPorDocumento($nro);

   if ($verificar && $verificar['id'] != $id_actual) {
      echo json_encode(['status' => false, 'msg' => 'Este número de documento ya está registrado con otro usuario.']);
   } else {
      $actualizado = $modelo->actualizarPersona($data);
      echo json_encode(['status' => $actualizado, 'msg' => $actualizado ? 'Usuario actualizado correctamente' : 'Error al actualizar']);
   }
   exit;
}

/* === ELIMINAR USUARIO === */
if ($tipo == "eliminar_usuario") {
   $id = $_GET['id'] ?? 0;
   $result = $objPersona->eliminarUsuario($id);
   echo json_encode($result);
   exit;
}

/* === VER PROVEEDORES === */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($tipo == "ver_proveedores") {
   $proveedores = $objPersona->verProveedores();
   $respuesta = ['status' => false, 'data' => []];
   if (count($proveedores) > 0) $respuesta = ['status' => true, 'data' => $proveedores];
   header('Content-Type: application/json');
   echo json_encode($respuesta);
   exit;
}

/* === VER CLIENTES === */
if ($tipo == "ver_clientes") {
   $usuarios = $objPersona->verClientes();
   $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
   if (count($usuarios)) {
      $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
   }
   echo json_encode($respuesta);
   exit;
}

/* === VER PROVEEDORES DETALLADOS === */
if ($tipo == "ver_proveedores_detallados") {
   $usuarios = $objPersona->verProveedoresDetallados();
   $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
   if (count($usuarios)) {
      $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
   }
   echo json_encode($respuesta);
   exit;
}
