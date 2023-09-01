<?php
include '../conection/conection.php'; // Incluir la conexión a la base de datos

$tipo_item = $_GET['tipo_item'];

// Realizar la consulta según el tipo de ítem
if ($tipo_item === 'producto') {
    $query = "SELECT id, codigo, nombre, descripcion FROM productos";
} elseif ($tipo_item === 'servicio') {
    $query = "SELECT id_servicio as id, titulo_servicio as nombre FROM servicios";
} elseif ($tipo_item === 'paquete') {
    $query = "SELECT paquete_id as id, titulo_paquete as nombre FROM paquete_cabecera";
}

$result = $mysqli->query($query);

$items = [];
while ($row = $result->fetch_assoc()) {
    $items[] = $row;
}

// Devolver los datos en formato JSON
echo json_encode($items);

$mysqli->close();
?>
