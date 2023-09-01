<?php
include '../conection/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $proveedor = $_POST['proveedor'];
    $num_factura = $_POST['num_factura'];
    $total = $_POST['total'];
    $productos = $_POST['producto'];
    $precios = $_POST['precio'];
    $cantidades = $_POST['cantidad'];

    $insertCabecera = "INSERT INTO compra_cabecera (fecha, proveedor, num_factura, total) VALUES ('$fecha', '$proveedor', '$num_factura', $total)";
    $mysqli->query($insertCabecera);
    $cabeceraId = $mysqli->insert_id;

    for ($i = 0; $i < count($productos); $i++) {
        $productoId = $productos[$i];
        $precio = $precios[$i];
        $cantidad = $cantidades[$i];
        $insertDetalle = "INSERT INTO compra_detalle (cabecera_id, producto_codigo, precio_c, cantidad) VALUES ($cabeceraId, $productoId, $precio, $cantidad)";
        $mysqli->query($insertDetalle);

        // Actualizar el stock del producto
        $updateStock = "UPDATE productos SET stock = stock + $cantidad WHERE id = $productoId";
        $mysqli->query($updateStock);
    }

    echo "Compra guardada exitosamente.";
}
?>