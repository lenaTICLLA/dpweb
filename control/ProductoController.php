<?php
require_once("../model/ProductoModel.php");
require_once("../model/CategoriaModel.php");

$objProducto = new ProductoModel();
$objCategoria = new CategoriaModel();

$tipo = $_GET['tipo'];

if ($tipo == "registrar") {
    //print_r($_POST);
    $codigo =  $_POST['codigo'];
    $nombre =  $_POST['nombre'];
    $detalle =  $_POST['detalle'];
    $precio =  $_POST['precio'];
    $stock =  $_POST['stock'];
    $id_categoria =  $_POST['id_categoria'];
    $fecha_vencimiento =  $_POST['fecha_vencimiento'];
    //$imagen =  $_POST['imagen'];
    $id_proveedor =  $_POST['id_proveedor'];

    /*
    // validar que los campos no esten vacios
    if ($codigo == "" || $nombre == "" || $detalle == "" || $precio == "" || $stock == "" || $id_categoria == "" || $fecha_vencimiento == "" || $imagen == "" || $id_proveedor == "") {

        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    }else {
        // validar si existe producto con el mismo 
        $existeProducto = $objProducto->existeProducto($codigo);
        if ($existeProducto > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error: codigo ya existe');
        } else {
            $respuesta = $objProducto->registrar($codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $fecha_vencimiento, $imagen, $id_proveedor);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'REGISTRADO CORRECTAMENTE');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'ERROR: FALLO AL REGISTAR');
            }
        }
    }
    echo json_encode($arrResponse);
    */
    
    
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => false, 'msg' => 'Error, imagen no recibida']);
        exit;
    }
    if ($objProducto->existeProducto($codigo) > 0) {
        echo json_encode(['status' => false, 'msg' => 'Error, el código ya existe']);
        exit;
    }
    $file = $_FILES['imagen'];
    $ext  = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $extPermitidas = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $extPermitidas)) {
        echo json_encode(['status' => false, 'msg' => 'Formato de imagen no permitido']);
        exit;
    }
    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        echo json_encode(['status' => false, 'msg' => 'La imagen supera 2MB']);
        exit;
    }
    $carpetaUploads = "../uploads/productos/";
    if (!is_dir($carpetaUploads)) {
        @mkdir($carpetaUploads, 0775, true);
    }

    $nombreUnico = uniqid('prod_') . '.' . $ext;
    $rutaFisica  = $carpetaUploads . $nombreUnico;
    $rutaRelativa = "uploads/productos/" . $nombreUnico;

    if (!move_uploaded_file($file['tmp_name'], $rutaFisica)) {
        echo json_encode(['status' => false, 'msg' => 'No se pudo guardar la imagen']);
        exit;
    }
    $id = $objProducto->registrar($codigo,$nombre,$detalle,$precio,$stock,$id_categoria,$fecha_vencimiento,$rutaRelativa, $id_proveedor);
    if ($id > 0) {
        echo json_encode(['status' => true, 'msg' => 'Registrado correctamente', 'id' => $id, 'img' => $rutaRelativa]);
    } else {
        @unlink($rutaFisica); // revertir archivo si falló BD
        echo json_encode(['status' => false, 'msg' => 'Error, falló en registro']);
    }
    exit;
    
}


/* Ver productos registrados */
if ($tipo == "ver_productos") {
    $productos = $objProducto->verProductos();
    echo json_encode($productos);
}

/* Ver para editar */
if ($tipo == "ver") {
    $respuesta = array('status' => false, 'msg' => '');
    $id_producto = $_POST['id_producto'];
    $producto = $objProducto->ver($id_producto);
    if ($producto) {
        $respuesta['status'] = true;
        $respuesta['data'] = $producto;
    } else {
        $respuesta['msg'] = 'Error, producto no existe';
    }
    echo json_encode($respuesta);
}



/* Para actualizar */
if ($tipo == "actualizar") {
    $id_producto = $_POST['id_producto'];
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $detalle = $_POST['detalle'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $id_categoria = $_POST['id_categoria'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];
    $id_proveedor = $_POST['id_proveedor'];

    if ($id_producto == "" || $codigo == "" || $nombre == "" || $detalle == "" || $precio == "" || $stock == "" || $id_categoria == "" || $fecha_vencimiento == "" || $id_proveedor == "") {

        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacíos');
    } else {
        $existeID = $objProducto->ver($id_producto);
        if (!$existeID) {
            $arrResponse = array('status' => false, 'msg' => 'Error, producto no existe en BD');
            echo json_encode($arrResponse);
            exit;
        } else {
            $actualizar = $objProducto->actualizar($id_producto, $codigo, $nombre, $detalle, $precio, $stock, $id_categoria, $fecha_vencimiento, $id_proveedor);
            if ($actualizar) {
                $arrResponse = array('status' => true, 'msg' => "Producto actualizado correctamente");
            } else {
                $arrResponse = array('status' => false, 'msg' => $actualizar);
            }
            echo json_encode($arrResponse);
            exit;
        }
    }
}

/* Método para Eliminar producto */
if ($tipo == "eliminar") {
    $id_producto = isset($_POST['id']) ? $_POST['id'] : '';

    if ($id_producto == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, ID vacío');
    } else {
        $existeId = $objProducto->ver($id_producto);
        if (!$existeId) {
            $arrResponse = array('status' => false, 'msg' => 'Error, producto no existe en Base de Datos');
        } else {
            $eliminar = $objProducto->eliminar($id_producto);
            if ($eliminar) {
                $arrResponse = array('status' => true, 'msg' => 'Producto eliminado correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error al eliminar el producto');
            }
        }
    }
    echo json_encode($arrResponse);
    exit;
}
