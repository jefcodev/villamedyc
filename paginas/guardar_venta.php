<?php
include '../conection/conection.php';

$data = json_decode(file_get_contents("php://input"), true);

$fecha_venta = $data['fecha_venta'];
$id_paciente = $data['id_paciente'];
$usuario = $data['usuario'];
$detalles = $data['detalles'];

// Insertar la cabecera de la venta
$insert_cabecera_sql = "INSERT INTO ventas_cabecera (fecha_venta, id_paciente, usuario, total)
                        VALUES (?, ?, ?, 0)";

$stmtCabecera = $mysqli->prepare($insert_cabecera_sql);
$stmtCabecera->bind_param("sss", $fecha_venta, $id_paciente, $usuario);

if ($stmtCabecera->execute()) {
    $venta_id = $stmtCabecera->insert_id;

    // Insertar el detalle de la venta
    $insert_detalle_sql = "INSERT INTO ventas_detalle (venta_id, tipo_item, item_id, cantidad, precio_unitario, subtotal)
    VALUES (?, ?, ?, ?, ?, ?)";

    $stmtDetalle = $mysqli->prepare($insert_detalle_sql);

    foreach ($detalles as $detalle) {
        $item = $detalle['item'];
        $cantidad = $detalle['cantidad'];
        $precio_unitario = $detalle['precioUnitario'];
        $subtotal = $detalle['subtotal'];

        $stmtDetalle->bind_param("ssdd", $venta_id, $item, $detalle['id'], $cantidad, $precio_unitario, $subtotal);
        $stmtDetalle->execute();
    }

    $stmtDetalle->close();
    $stmtCabecera->close();

    echo json_encode(array('success' => true, 'message' => 'Venta guardada exitosamente.'));
} else {
    echo json_encode(array('success' => false, 'message' => 'Error al guardar la venta: ' . $stmtCabecera->error));
}

$mysqli->close();

?>
