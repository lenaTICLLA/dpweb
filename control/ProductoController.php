<?php

// Se incluye el modelo ProductoModel.php que contiene la lógica de conexión y operaciones con la base de datos
require_once("../model/ProductoModel.php");

// Se crea una instancia del modelo para poder usar sus métodos
$objProducto = new ProductoModel();
// Se obtiene el tipo de operación que se desea realizar (registrar, etc.)
$tipo = $_GET['tipo'];

//
if ($tipo == 'registrar') {
    // Captura los campos del formulario
    $codigo = $_POST['codigo'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $detalle = $_POST['detalle'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $stock = $_POST['stock'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $id_categoria = $_POST['id_categoria'] ?? '';
    $id_proveedor = $_POST['id_persona'] ?? ''; // Proveedor es opcional

    // Validar campos obligatorios (excluyendo id_proveedor)
    if ($codigo == "" || $nombre == "" || $detalle == "" || $precio == "" || $stock == "" || $fecha_vencimiento == "" || $id_categoria == "") {
        $arrResponse = array('status' => false, 'msg' => 'Error, campos vacíos');
        echo json_encode($arrResponse);
        exit;
    }

    // Validar la imagen
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        $arrResponse = array('status' => false, 'msg' => 'Error, imagen no recibida');
        echo json_encode($arrResponse);
        exit;
    }

    // Validar si el código ya existe
    if ($objProducto->existeCodigo($codigo) > 0) {
        $arrResponse = array('status' => false, 'msg' => 'Error, el código ya existe');
        echo json_encode($arrResponse);
        exit;
    }

    // Validar formato y tamaño de la imagen
    $file = $_FILES['imagen'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $extPermitidas = ['jpg', 'jpeg', 'png'];

    if (!in_array($ext, $extPermitidas)) {
        $arrResponse = array('status' => false, 'msg' => 'Formato de imagen no permitido');
        echo json_encode($arrResponse);
        exit;
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        $arrResponse = array('status' => false, 'msg' => 'La imagen supera 5MB');
        echo json_encode($arrResponse);
        exit;
    }

    // Guardar la imagen con un nombre único
    $carpetaUploads = "../Uploads/productos/";
    if (!is_dir($carpetaUploads)) {
        @mkdir($carpetaUploads, 0775, true);
    }

    $nombreUnico = uniqid('prod_') . '.' . $ext;
    $rutaFisica = $carpetaUploads . $nombreUnico;
    $rutaRelativa = "Uploads/productos/" . $nombreUnico;

    if (!move_uploaded_file($file['tmp_name'], $rutaFisica)) {
        $arrResponse = array('status' => false, 'msg' => 'No se pudo guardar la imagen');
        echo json_encode($arrResponse);
        exit;
    }

    // Validar si id_categoria existe
    require_once("../model/CategoriaModel.php");
    $objCategoria = new CategoriaModel();
    $categoria = $objCategoria->obtenerCategoriaPorId($id_categoria);
    if (!$categoria) {
        $arrResponse = array('status' => false, 'msg' => 'Error, la categoría no existe');
        echo json_encode($arrResponse);
        exit;
    }

    // Registrar el producto
    $id = $objProducto->registrar($codigo, $nombre, $detalle, $precio, $stock, $fecha_vencimiento, $rutaRelativa, $id_categoria, $id_proveedor);
    if ($id > 0) {
        $arrResponse = array('status' => true, 'msg' => 'Registrado correctamente', 'id' => $id, 'img' => $rutaRelativa);
    } else {
        @unlink($rutaFisica); // Revertir archivo si falló la BD
        $arrResponse = array('status' => false, 'msg' => 'Error, fallo en registro');
    }

    echo json_encode($arrResponse);
    exit;
}
//
if ($tipo == "ver_productos") {
    $productos = $objProducto->verProductos();
    echo json_encode($productos);
}

if ($tipo == 'obtener_producto') {
    header('Content-Type: application/json');
    $id = $_GET['id'];
    $producto = $objProducto->obtenerProductoPorId($id);
    echo json_encode($producto);
    exit;
}
// actualizar
if ($tipo == "actualizar_producto") {
    $data = $_POST;
    $id_actual = $data['id_producto'] ?? 0;
    $nombre = $data['nombre'] ?? '';
    $id_categoria = $data['id_categoria'] ?? NULL;
    $id_proveedor = $data['id_persona'] ?? NULL;
    $imagen_actual = $data['imagen_actual'] ?? ''; // Ruta antigua

    // Validar duplicados por nombre
    $verificar = $objProducto->buscarPorNombre($nombre);
    if ($verificar && $verificar['id'] != $id_actual) {
        echo json_encode(['status' => false, 'msg' => 'Error, producto ya existe.']);
        exit;
    }

    // Validar categoría
    require_once("../model/CategoriaModel.php");
    $objCategoria = new CategoriaModel();
    if ($id_categoria && !$objCategoria->obtenerCategoriaPorId($id_categoria)) {
        echo json_encode(['status' => false, 'msg' => 'Error, la categoría no existe']);
        exit;
    }

    // Lógica de imagen 
    $carpetaUploads = "../Uploads/productos/";
    if (!is_dir($carpetaUploads)) {
        @mkdir($carpetaUploads, 0775, true);
    }

    $nuevaImagenRel = $imagen_actual; // Por defecto mantener la actual
    $nuevaImagenFis = "../" . $imagen_actual;

    // Si el usuario sube una nueva imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extPermitidas = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $extPermitidas)) {
            echo json_encode(['status' => false, 'msg' => 'Formato de imagen no permitido']);
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            echo json_encode(['status' => false, 'msg' => 'La imagen supera 5MB']);
            exit;
        }

        // Guardar la nueva imagen
        $nombreUnico = uniqid('prod_') . '.' . $ext;
        $rutaFisica = $carpetaUploads . $nombreUnico;
        $rutaRelativa = "Uploads/productos/" . $nombreUnico;

        if (move_uploaded_file($file['tmp_name'], $rutaFisica)) {
            // Eliminar la imagen anterior si existía
            if ($imagen_actual != '' && file_exists("../" . $imagen_actual)) {
                @unlink("../" . $imagen_actual);
            }
            $nuevaImagenRel = $rutaRelativa;
            $nuevaImagenFis = $rutaFisica;
        } else {
            echo json_encode(['status' => false, 'msg' => 'No se pudo guardar la nueva imagen']);
            exit;
        }
    }

    //Actualizar en la BD 
    $data['imagen'] = $nuevaImagenRel;
    $data['id_categoria'] = $id_categoria;
    $data['id_proveedor'] = $id_proveedor;

    $actualizado = $objProducto->actualizarProducto($data);

    if ($actualizado) {
        echo json_encode(['status' => true, 'msg' => 'Producto actualizado correctamente']);
    } else {
        // Si falló la BD y se subió nueva imagen, eliminarla para no dejar basura
        if (isset($rutaFisica) && file_exists($rutaFisica)) {
            @unlink($rutaFisica);
        }
        echo json_encode(['status' => false, 'msg' => 'Error al actualizar producto']);
    }
}


// eliminar producto 
if ($tipo == "eliminar_producto") {
    $id = $_GET['id'] ?? 0;
    $result = $objProducto->eliminarProducto($id);
    echo json_encode($result);
}
