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
    $rutaRelativa = "uploads/productos/" . $nombreUnico;

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

    $respuesta = array('status' => false, 'msg' => '');
    $id_producto       = $_POST['id_producto'] ?? '';
    $codigo            = $_POST['codigo'] ?? '';
    $nombre            = $_POST['nombre'] ?? '';
    $detalle           = $_POST['detalle'] ?? '';
    $precio            = $_POST['precio'] ?? '';
    $stock             = $_POST['stock'] ?? '';
    $id_categoria      = $_POST['id_categoria'] ?? '';
    $fecha_vencimiento = $_POST['fecha_vencimiento'] ?? '';
    $id_proveedor      = $_POST['id_persona'] ?? '';

    // Validar campos vacíos
    if ($id_producto === "" || $codigo === "" || $nombre === "" || $detalle === "" || $precio === "" || $stock === "" || $id_categoria === "" || $fecha_vencimiento === "" ) {
        $respuesta['msg'] = 'Error, campos vacíos';
        echo json_encode($respuesta);
        exit;
    }

    // Verificar si el producto existe
    $producto = $objProducto->obtenerProductoPorId($id_producto);
    if (!$producto) {
        $respuesta['msg'] = 'Error, producto no existe en BD';
        echo json_encode($respuesta);
        exit;
    }

    // Verificar si otro producto tiene el mismo nombre
    $verificar = $objProducto->buscarPorNombre($nombre);
    if ($verificar && $verificar['id'] != $id_producto) {
        $respuesta['msg'] = 'Error, producto ya existe';
        echo json_encode($respuesta);
        exit;
    }

    // Verificar si la categoría existe
    require_once("../model/CategoriaModel.php");
    $objCategoria = new CategoriaModel();
    if (!$objCategoria->obtenerCategoriaPorId($id_categoria)) {
        $respuesta['msg'] = 'Error, la categoría no existe';
        echo json_encode($respuesta);
        exit;
    }

    // Manejar imagen
   // Manejar imagen
    if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
        $imagen = $producto['imagen']; // Conservar imagen actual si no se sube una nueva
    } else {
        // Validar formato y tamaño de la imagen
        $file = $_FILES['imagen'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $extPermitidas = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $extPermitidas)) {
            $respuesta['msg'] = 'Formato de imagen no permitido';
            echo json_encode($respuesta);
            exit;
        }

        if ($file['size'] > 5 * 1024 * 1024) { // 5MB
            $respuesta['msg'] = 'La imagen supera 5MB';
            echo json_encode($respuesta);
            exit;
        }

        // Guardar la imagen con un nombre único
        $carpetaUploads = "../Uploads/productos/";
        if (!is_dir($carpetaUploads)) {
            @mkdir($carpetaUploads, 0775, true);
        }

        $nombreUnico = uniqid('prod_') . '.' . $ext;
        $rutaFisica = $carpetaUploads . $nombreUnico;
        $imagen = "Uploads/productos/" . $nombreUnico;

        if (!move_uploaded_file($file['tmp_name'], $rutaFisica)) {
            $respuesta['msg'] = 'No se pudo guardar la imagen';
            echo json_encode($respuesta);
            exit;
        }

        // Eliminar la imagen anterior si existe y se está subiendo una nueva
        if (!empty($producto['imagen'])) {
            // Probar ambas rutas posibles (uploads y Uploads)
            $rutaAnterior1 = "../" . $producto['imagen'];
            $rutaAnterior2 = "../" . str_replace('uploads/', 'Uploads/', $producto['imagen']);
            $rutaAnterior3 = "../" . str_replace('Uploads/', 'uploads/', $producto['imagen']);
            
            if (file_exists($rutaAnterior1)) {
                @unlink($rutaAnterior1);
            } elseif (file_exists($rutaAnterior2)) {
                @unlink($rutaAnterior2);
            } elseif (file_exists($rutaAnterior3)) {
                @unlink($rutaAnterior3);
            }
        }
    }

    // Actualizar producto
    $actualizar = $objProducto->actualizarProducto([
        'id_producto'       => $id_producto,
        'codigo'            => $codigo,
        'nombre'            => $nombre,
        'detalle'           => $detalle,
        'precio'            => $precio,
        'stock'             => $stock,
        'id_categoria'      => $id_categoria,
        'fecha_vencimiento' => $fecha_vencimiento,
        'id_proveedor'      => $id_proveedor,
        'imagen'            => $imagen
    ]);

    if ($actualizar) {
        $respuesta['status'] = true;
        $respuesta['msg'] = 'Producto actualizado correctamente';
    } else {
        // Revertir la subida de la imagen si falla la actualización
        if (isset($rutaFisica) && file_exists($rutaFisica)) {
            @unlink($rutaFisica);
        }
        $respuesta['msg'] = 'Error al actualizar el producto';
    }

    echo json_encode($respuesta);
    exit;
}

// eliminar producto 
if ($tipo == "eliminar_producto") {
    $id = $_GET['id'] ?? 0;
    $result = $objProducto->eliminarProducto($id);
    echo json_encode($result);
}
// buscar producto
if ($tipo == "buscar_Producto_venta") {
    $dato = $_POST['dato'] ?? '';

    $productos = $objProducto->buscarProductoByNombreOrCodigo($dato);

    if (count($productos) > 0) {
        $respuesta = array( 'status' => true,  'msg'    => '','data'   => $productos   // Ya viene con categoría incluida
        );
    } else {
        $respuesta = array(
            'status' => false,
            'msg'    => 'No se encontraron productos'
        );
    }

    header('Content-Type: application/json');
    echo json_encode($respuesta);
    exit;
}