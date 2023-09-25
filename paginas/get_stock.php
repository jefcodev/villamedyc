<?php
// get_stock.php

include '../conection/conection.php';

$item_id = $_GET['item_id'];

// Realiza una consulta SQL para obtener el stock del producto
$stock_query = "SELECT stock FROM productos WHERE id = $item_id";
$stock_result = $mysqli->query($stock_query);

if ($stock_result && $stock_result->num_rows > 0) {
    $row = $stock_result->fetch_assoc();
    $stock = $row['stock'];
    echo json_encode(['stock' => $stock]);
} else {
    echo json_encode(['stock' => 0]); // Maneja el caso de que no se encuentre el producto
}

?>
