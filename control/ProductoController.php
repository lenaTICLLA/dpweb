<?php

// Se incluye el modelo ProductoModel.php que contiene la lógica de conexión y operaciones con la base de datos
require_once("../model/ProductoModel.php");

// Se crea una instancia del modelo para poder usar sus métodos
$objProducto = new ProductoModel();
// Se obtiene el tipo de operación que se desea realizar (registrar, etc.)
$tipo = $_GET['tipo'];


if ($tipo == 'registrar') {
    // Captura los campos del formulario
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $detalle = $_POST['detalle'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];
    $fecha_vencimiento = $_POST['fecha_vencimiento'];

    // Manejo de la imagen
    $imagen = '';
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
        $target_dir = "../uploads/productos/"; // Asegúrate de que esta carpeta exista y tenga permisos
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $imagen = $target_dir . basename($_FILES["imagen"]["name"]);
        move_uploaded_file($_FILES["imagen"]["tmp_name"], $imagen);
    }

    // Validación de campos vacíos
    if ($codigo == "" || $nombre == "" || $detalle == "" || $precio == "" || $stock == "" || $fecha_vencimiento == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacios');
    } else {
        // Verifica si existe un producto con el mismo nombre (opcional, ajusta según necesidades)
        $existeProducto = $objProducto->existeProducto($nombre);
        if ($existeProducto > 0) {
            $arrResponse = array('status' => false, 'msg' => 'Error, producto ya existe');
        } else {
            // Registra el producto
            $respuesta = $objProducto->registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $imagen);
            if ($respuesta) {
                $arrResponse = array('status' => true, 'msg' => 'Registrado Correctamente');
            } else {
                $arrResponse = array('status' => false, 'msg' => 'Error, fallo en registro');
            }
        }
    }
    echo json_encode($arrResponse);
}

if ($tipo == "ver_productos") {
    $productos = $objProducto->verProductos();
    echo json_encode($productos);        
}

if ($_GET['tipo'] == 'obtener_producto') {
    header('Content-Type: application/json');

    $id = $_GET['id'];

    require_once '../model/ProductoModel.php';

    $modelo = new ProductoModel();
    $producto = $modelo->obtenerProductoPorId($id);

    echo json_encode($producto);
    exit;
}

// actualizar
if ($tipo == "actualizar_producto") {
    $data = $_POST;
    error_log("Datos recibidos en controlador: " . print_r($data, true));

    $modelo = new ProductoModel();
    
    $nombre = $data['nombre'];
    $id_actual = $data['id_producto'];

    $verificar = $modelo->buscarPorNombre($nombre);
    
    if ($verificar && $verificar['id'] != $id_actual) {
        echo json_encode(['status' => false, 'msg' => 'Error, producto ya existe.']);
    } else {
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == 0) {
            $target_dir = "../Uploads/productos/";
            if (!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $data['imagen'] = $target_dir . basename($_FILES["imagen"]["name"]);
            move_uploaded_file($_FILES["imagen"]["tmp_name"], $data['imagen']);
        } else {
            $productoActual = $modelo->obtenerProductoPorId($id_actual);
            $data['imagen'] = $productoActual['imagen'];
        }

        $actualizado = $modelo->actualizarProducto($data);
        echo json_encode(['status' => $actualizado, 'msg' => $actualizado ? 'Producto actualizado correctamente' : 'Error al actualizar']);
    }
}

// eliminar producto 
if ($tipo == "eliminar_producto") {
    $id = $_GET['id'] ?? 0;
    $result = $objProducto->eliminarProducto($id);
    echo json_encode($result);
}