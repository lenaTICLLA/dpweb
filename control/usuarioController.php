<?php
require_once("../model/UsuarioModel.php");

$objPersona = new UsuarioModel();

$tipo = $_GET['tipo'];

if ($tipo == "registrar") {
    //print_r($_POST);
    $nro_identidad =  $_POST['nro_identidad'];
    $razon_social =  $_POST['razon_social'];
    $telefono =  $_POST['telefono'];
    $correo =  $_POST['correo'];
    $departamento =  $_POST['departamento'];
    $provincia =  $_POST['provincia'];
    $distrito =  $_POST['distrito'];
    $cod_postal =  $_POST['cod_postal'];
    $direccion =  $_POST['direccion'];
    $rol =  $_POST['rol'];
    //ENCRIPTANDO nro_identidad PARA USAR COMO CONTRASEÑA
    $password = password_hash($nro_identidad, PASSWORD_DEFAULT);

    /* validar que los campos no esten vacios*/
    if ($nro_identidad == "" || $razon_social == "" || $telefono == "" || $correo == "" || $departamento == "" || $provincia == "" || $distrito == "" || $cod_postal == "" || $direccion == "" || $rol == "") {

        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        // validar si existe persona con el mismo nro_documento
        $existePersona = $objPersona->existePersona($nro_identidad);
        if ($existePersona > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error: nro documento ya existe');
        } else {
            $respuesta = $objPersona->registrar($nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol, $password);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'REGISTRADO CORRECTAMENTE');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'ERROR: FALLO AL REGISTAR');
            }
        }
    }
    echo json_encode($arrResponse);
}


/* para iniciar sesion*/
if ($tipo == "iniciar_sesion") {
    $nro_identidad = $_POST['username'];
    $password = $_POST['password'];
    if ($nro_identidad == "" || $password == "") {
        $respuesta = array('status' => false, 'msg' => 'ERROR: campos vacios');
    } else {
        $existePersona = $objPersona->existePersona($nro_identidad);
        if (!$existePersona) {
            $respuesta = array('status' => false, 'msg' => 'ERROR: usuario no registrado');
        } else {
            $persona = $objPersona->buscarPersonaPorNroIdentidad($nro_identidad);
            if (password_verify($password, $persona->password)) {
                session_start();
                $_SESSION['ventas_id'] = $persona->id;
                $_SESSION['ventas_usuario'] = $persona->razon_social;
                $respuesta = array('status' => true, 'msg' => 'bienvenido');
            } else {
                $respuesta = array('status' => false, 'msg' => 'ERROR: contraseña incorrecta');
            }
        }
    }
    echo json_encode($respuesta);
}


/* ver usuarios registrados
if ($tipo == "ver_usuarios") {
    $usuarios = $objPersona->verUsuarios();
    echo json_encode($usuarios);
}*/

if ($tipo == "ver_usuarios") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $usuarios = $objPersona->verUsuarios();
    if (count($usuarios)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
    }
    echo json_encode($respuesta);
}


/*ver para editar */
if ($tipo == "ver") {
    //print_r($_POST);
    $respuesta = array('status' => false, 'msg' => '');
    $id_persona = $_POST['id_persona'];
    $usuario = $objPersona->ver($id_persona);
    if ($usuario) {
        $respuesta['status'] = true;
        $respuesta['data'] = $usuario;
    } else {
        $respuesta['msg'] = 'Error, usuario no existe';
    }
    echo json_encode($respuesta);
}

/*para actualizar*/
if ($tipo == "actualizar") {
    //print_r($_POST);
    $id_persona = $_POST['id_persona'];
    $nro_identidad =  $_POST['nro_identidad'];
    $razon_social =  $_POST['razon_social'];
    $telefono =  $_POST['telefono'];
    $correo =  $_POST['correo'];
    $departamento =  $_POST['departamento'];
    $provincia =  $_POST['provincia'];
    $distrito =  $_POST['distrito'];
    $cod_postal =  $_POST['cod_postal'];
    $direccion =  $_POST['direccion'];
    $rol =  $_POST['rol'];
    if ($id_persona == "" || $nro_identidad == "" || $razon_social == "" || $telefono == "" || $correo == "" || $departamento == "" || $provincia == "" || $distrito == "" || $cod_postal == "" || $direccion == "" || $rol == "") {

        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        $existeID = $objPersona->ver($id_persona);
        if (!$existeID) {
            //devolver respuesta
            $arrResponse = array('status' => false, 'msg' => 'Error, usuario no existe en BD');
            echo json_encode($arrResponse);
            //cerrar funcion
            exit;
        } else {
            //actualizar
            $actualizar = $objPersona->actualizar($id_persona, $nro_identidad, $razon_social, $telefono, $correo, $departamento, $provincia, $distrito, $cod_postal, $direccion, $rol);
            if ($actualizar) {
                $arrResponse = array('status' => true, 'msg' => "Actualizado correctamente");
            } else {
                $arrResponse = array('status' => false, 'msg' => $actualizar);
            }
            echo json_encode($arrResponse);
            exit;
        }
    }
}


// Metodo para Elimar datos de Usuario
/*if ($tipo == "eliminar") {
    // El JS envía 'id', no 'id_persona'
    $id_persona = isset($_POST['id']) ? $_POST['id'] : '';

    if ($id_persona == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, ID vacío');
    } else {
        $existeId = $objPersona->ver($id_persona);
        if (!$existeId) {
            $arrResponse = array('status' => false, 'msg' => 'Error, usuario no existe en Base de Datos!!');
        } else {
            $eliminar = $objPersona->eliminar($id_persona);
            if ($eliminar) {
                $arrResponse = array('status' => true, 'msg' => "Eliminado correctamente");
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar');
            }
        }
    }
    echo json_encode($arrResponse);
    exit;
}*/
//2 manera de eliminar
if ($tipo == "eliminar") {
    //print_r($_POST);
    $id_persona = $_POST['id_persona'];
    $respuesta = array('status' => false, 'msg' => '');
    $resultado = $objPersona->eliminar($id_persona);
    if ($resultado) {
        $respuesta = array('status' => true, 'msg' => 'Eliminado Correctamente');
    }else {
        $respuesta = array('status' => false, 'msg' => $resultado);
    }
    echo json_encode($respuesta);
}

/* para cerrar sesion */
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : '';
if ($tipo == 'cerrar_sesion') {
    session_start();
    session_destroy();
    echo json_encode(['status' => true, 'msg' => 'Sesión cerrada correctamente']);
    exit;
}

//ver clientes
if ($tipo == "ver_clientes") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $usuarios = $objPersona->verClientes();
    if (count($usuarios)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
    }
    echo json_encode($respuesta);
}

//ver proveedores
if ($tipo == "ver_proveedores") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $usuarios = $objPersona->verProveedores();
    if (count($usuarios)) {
        $respuesta = array('status' => true, 'msg' => '', 'data' => $usuarios);
    }
    echo json_encode($respuesta);
}