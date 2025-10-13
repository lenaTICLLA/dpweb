<?php
require_once("../model/SesionModel.php");
$objSesion = new SesionModel();

$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';

if ($tipo == "login") {

    // Datos enviados desde el formulario de login
    $usuario = trim($_POST['usuario'] ?? '');
    $clave = trim($_POST['clave'] ?? '');

    if ($usuario == "" || $clave == "") {
        $arrResponse = array('status' => false, 'msg' => 'Por favor, complete todos los campos.');
    } else {
        // Validar usuario y contraseña en el modelo
        $login = $objSesion->iniciarSesion($usuario, $clave);
        if ($login) {
            session_start();
            $_SESSION['ventas_id'] = $login['id'];
            $_SESSION['ventas_usuario'] = $login['usuario'];
            $_SESSION['ventas_nombre'] = $login['nombre'];
            $_SESSION['ventas_rol'] = $login['rol'];

            $arrResponse = array('status' => true, 'msg' => 'Inicio de sesión correcto.');
        } else {
            $arrResponse = array('status' => false, 'msg' => 'Usuario o contraseña incorrectos.');
        }
    }
    echo json_encode($arrResponse);
    exit;

} elseif ($tipo == "registrar") {

    // Registrar sesión (si aún lo necesitas)
    $id_persona = $_POST['id_persona'] ?? '';
    $fecha_hora_inicio = $_POST['fecha_hora_inicio'] ?? '';
    $fecha_hora_fin = $_POST['fecha_hora_fin'] ?? '';
    $token = $_POST['token'] ?? '';
    $ip = $_POST['ip'] ?? '';

    if ($id_persona == "" || $fecha_hora_inicio == "" || $fecha_hora_fin == "" || $token == "" || $ip == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacíos.');
    } else {
        $existeSesion = $objSesion->existeSesion($id_persona);
        if ($existeSesion > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error: id_persona ya existe.');
        } else {
            $respuesta = $objSesion->registrar($id_persona, $fecha_hora_inicio, $fecha_hora_fin, $token, $ip);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Sesión registrada correctamente.');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al registrar la sesión.');
            }
        }
    }
    echo json_encode($arrResponse);
    exit;

} elseif ($tipo == "logout") {
    session_start();
    session_destroy();
    header("Location: ../login");
    exit;
} else {
    echo json_encode(['status' => false, 'msg' => 'Solicitud no válida.']);
}
?>
