<?php
require_once "../model/CategoriaModel.php";

$categoria = new CategoriaModel();

if ($_GET['tipo'] == 'registrar') {
    $nombre = $_POST['nombre'];
    $detalle = $_POST['detalle'];

    if ($categoria->existeCategoria($nombre) > 0) {
        echo json_encode(['status' => false, 'msg' => 'La categoría ya está registrada']);
    } else {
        $resp = $categoria->registrar($nombre, $detalle);
        if ($resp > 0) {
            echo json_encode(['status' => true, 'msg' => 'Categoría registrada correctamente']);
        } else {
            echo json_encode(['status' => false, 'msg' => 'Error al registrar categoría']);
        }
    }
}
