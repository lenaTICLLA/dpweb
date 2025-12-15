<?php
require_once("../model/VentaModel.php");
require_once("../model/ProductoModel.php");

$objProducto = new ProductoModel();
$objVenta = new VentaModel();

$tipo = $_GET['tipo'] ?? '';

// ==================== REGISTRAR TEMPORAL (igual que dpweb-main) ====================
if ($tipo == "registrarTemporal") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $id_producto = intval($_POST['id_producto'] ?? 0);
    $precio = floatval($_POST['precio'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 1);

    $b_producto = $objVenta->buscarTemporal($id_producto);
    if ($b_producto) {
        $n_cantidad = $b_producto->cantidad + $cantidad;
        $objVenta->actualizarCantidadTemporal($id_producto, $n_cantidad);
        $respuesta = array('status' => true, 'msg' => 'actualizado');
    } else {
        $registro = $objVenta->registrar_temporal($id_producto, $precio, $cantidad);
        $respuesta = array('status' => true, 'msg' => 'registrado');
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== LISTAR TEMPORALES (igual que dpweb-main) ====================
if ($tipo == "listar_venta_temporal") {
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    $b_producto = $objVenta->buscarTemporales();
    if ($b_producto && count($b_producto) > 0) {
        $respuesta = array('status' => true, 'data' => $b_producto);
    } else {
        $respuesta = array('status' => false, 'msg' => 'no se encontraron datos', 'data' => []);
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== ACTUALIZAR CANTIDAD (igual que dpweb-main) ====================
if ($tipo == "actualizar_cantidad") {
    $id = intval($_POST['id'] ?? 0);
    $cantidad = intval($_POST['cantidad'] ?? 1);
    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');
    
    $consulta = $objVenta->actualizarCantidadTemporalByid($id, $cantidad);
    if ($consulta) {
        $respuesta = array('status' => true, 'msg' => 'success');
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== ELIMINAR TEMPORAL ====================
if ($tipo == "eliminar_temporal") {
    $id = intval($_GET['id'] ?? 0);
    $respuesta = array('status' => false, 'msg' => 'fallo');
    
    if ($objVenta->eliminarTemporal($id)) {
        $respuesta = array('status' => true, 'msg' => 'eliminado');
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== VACIAR CARRITO ====================
if ($tipo == "vaciar_carrito") {
    $respuesta = array('status' => false, 'msg' => 'fallo');
    
    if ($objVenta->eliminarTemporales()) {
        $respuesta = array('status' => true, 'msg' => 'carrito vaciado');
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== BUSCAR CLIENTE POR DNI ====================
if ($tipo == "buscar_por_dni") {
    $dni = $_POST['dni'] ?? '';
    $respuesta = array('status' => false, 'msg' => 'Cliente no encontrado');

    if (strlen($dni) >= 8) {
        $cliente = $objVenta->buscarClientePorDni($dni);
        if ($cliente) {
            $respuesta = array(
                'status' => true, 
                'origen' => 'local',
                'data' => $cliente
            );
        }
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== CONSULTAR API RENIEC/SUNAT ====================
if ($tipo == "consultar_api") {
    $documento = $_GET['documento'] ?? '';
    $respuesta = array('status' => false, 'msg' => 'No se encontró información');

    if (strlen($documento) < 8) {
        echo json_encode($respuesta);
        exit;
    }

    $tipo_doc = strlen($documento) == 8 ? 'dni' : 'ruc';
    $token = 'apis-token-12444.yBxkfv0Ed4BdyY9gOj4ByItW37wQUigZ';

    if ($tipo_doc == 'dni') {
        $url = "https://api.apis.net.pe/v2/reniec/dni?numero=" . $documento;
    } else {
        $url = "https://api.apis.net.pe/v2/sunat/ruc?numero=" . $documento;
    }

    $curl = curl_init();
    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ]
    ]);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    if ($httpCode == 200 && $response) {
        $data = json_decode($response, true);

        if ($tipo_doc == 'dni' && isset($data['nombres'])) {
            $nombre = trim($data['nombres'] . ' ' . ($data['apellidoPaterno'] ?? '') . ' ' . ($data['apellidoMaterno'] ?? ''));
            $respuesta = array(
                'status' => true,
                'origen' => 'reniec',
                'data' => array(
                    'nro_identidad' => $documento,
                    'razon_social' => $nombre,
                    'direccion' => ''
                )
            );
        } elseif ($tipo_doc == 'ruc' && isset($data['razonSocial'])) {
            $respuesta = array(
                'status' => true,
                'origen' => 'sunat',
                'data' => array(
                    'nro_identidad' => $documento,
                    'razon_social' => $data['razonSocial'],
                    'direccion' => $data['direccion'] ?? ''
                )
            );
        }
    }

    echo json_encode($respuesta);
    exit;
}

// ==================== REGISTRAR CLIENTE RAPIDO ====================
if ($tipo == "registrar_cliente") {
    $nro_identidad = $_POST['nro_identidad'] ?? '';
    $razon_social = $_POST['razon_social'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    
    $respuesta = array('status' => false, 'msg' => 'Error al registrar');

    if (!empty($nro_identidad) && !empty($razon_social)) {
        $id_cliente = $objVenta->registrarClienteRapido($nro_identidad, $razon_social, $direccion);
        if ($id_cliente) {
            $respuesta = array(
                'status' => true, 
                'msg' => 'Cliente registrado',
                'id_cliente' => $id_cliente
            );
        }
    }
    echo json_encode($respuesta);
    exit;
}

// ==================== REGISTRAR VENTA (igual que dpweb-main) ====================
if ($tipo == "registrar_venta") {
    session_start();
    $id_cliente = intval($_POST['id_cliente'] ?? 0);
    $fecha_venta = $_POST['fecha_venta'] ?? date('Y-m-d H:i:s');
    $id_vendedor = $_SESSION['ventas_id'] ?? 0;

    $respuesta = array('status' => false, 'msg' => 'fallo el controlador');

    if ($id_vendedor == 0) {
        $respuesta['msg'] = 'Sesión no válida';
        echo json_encode($respuesta);
        exit;
    }

    // Verificar que hay productos
    $temporales = $objVenta->buscarTemporales();
    if (empty($temporales)) {
        $respuesta['msg'] = 'El carrito está vacío';
        echo json_encode($respuesta);
        exit;
    }

    // Obtener correlativo
    $ultima_venta = $objVenta->buscar_ultima_venta();
    $correlativo = $ultima_venta ? intval($ultima_venta->codigo) + 1 : 1;

    // Registrar venta
    $venta = $objVenta->registrar_venta($correlativo, $fecha_venta, $id_cliente, $id_vendedor);
    
    if ($venta) {
        // Registrar detalles
        foreach ($temporales as $temporal) {
            $objVenta->registrar_detalle_venta($venta, $temporal->id_producto, $temporal->precio, $temporal->cantidad);
        }
        
        // Limpiar temporales
        $objVenta->eliminarTemporales();
        
        $respuesta = array(
            'status' => true, 
            'msg' => 'Venta registrada con éxito',
            'codigo' => $correlativo
        );
    } else {
        $respuesta['msg'] = 'Error al registrar la venta';
    }
    
    echo json_encode($respuesta);
    exit;
}

echo json_encode(['status' => false, 'msg' => 'Tipo no válido']);