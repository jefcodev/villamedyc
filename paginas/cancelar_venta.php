<?php
include '../conection/conection.php';

// Verificar si se recibió el ID de la venta
if (isset($_POST['id_venta'])) {
    $id_venta = $_POST['id_venta'];

    // Iniciar una transacción para garantizar la consistencia de los datos
    $mysqli->begin_transaction();

    // 1. Cancelar la venta actualizando el estado de la venta
    $update_query = "UPDATE ventas_cabecera SET estado = 'cancelada' WHERE id = $id_venta";
    $update_result = $mysqli->query($update_query);

    // 2. Obtener los detalles de la venta para actualizar el stock
    $detalles_query = "SELECT * FROM ventas_detalle WHERE venta_id = $id_venta";
    $detalles_result = $mysqli->query($detalles_query);

    // 3. Actualizar el stock de los productos vendidos
    while ($detalle = mysqli_fetch_assoc($detalles_result)) {
        $tipo_item = $detalle['tipo_item'];
        $id_item =  $detalle['item_id'];
        $cantidad = $detalle['cantidad'];

        if ($tipo_item === 'producto') {
            // Actualizar el stock de productos
            $stock_query = "UPDATE productos SET stock = stock + $cantidad WHERE id = $id_item";
            $stock_result = $mysqli->query($stock_query);

            if (!$stock_result) {
                // Error al actualizar el stock, revertir la transacción
                $mysqli->rollback();
                //echo "Error al actualizar el stock de productos.";
                exit();
            }
        }
    }

    // Confirmar la transacción si todo se realizó con éxito
    $mysqli->commit();

    // Cerrar la conexión a la base de datos
    $mysqli->close();

    // Envía una respuesta de éxito al cliente
    echo "Venta cancelada con éxito y stock actualizado.";
} else {
    echo "No se recibió el ID de la venta.";
}
