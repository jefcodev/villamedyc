<?php
include '../conection/conection.php';

$tipoItem = $_GET['tipo_item'];
$item_id = $_GET['item_id'];

switch ($tipoItem) {
    case 'producto':
        $sql = "SELECT precio_v FROM productos WHERE id = ?";
        break;
    case 'servicio':
        
        $sql = "SELECT valor_adicional FROM servicios WHERE id_servicio = ?";
        break;
    case 'paquete':
        
        $sql = "SELECT ahorra FROM paquete_cabecera WHERE paquete_id = ?";
        break;
    default:
        echo json_encode(array('error' => 'Tipo de item no válido'));
        exit();
}

$stmt = $mysqli->prepare($sql);
$stmt->bind_param('s', $item_id);   
$stmt->execute();
$stmt->bind_result($precio);
if ($stmt->fetch()) {
    echo json_encode(array('precio' => $precio));
} else {
    echo json_encode(array('error' => 'No se encontró el precio'));
}
$stmt->close();

$mysqli->close();
?>
