<?php
include '../conection/conection.php';

$paqueteId = $_POST['paqueteId'];

// Obtener cabecera de paquete 
$sql_paquete = "SELECT * FROM paquete_cabecera WHERE paquete_id = $paqueteId";
$result_paquete = $mysqli->query($sql_paquete);
$row_paquete = mysqli_fetch_assoc($result_paquete);
$precioPaquete = $row_paquete['total'];


// Obtener los detalles de paquete

$sql_detalles = "SELECT * FROM paquete_detalle WHERE paquete_id = $paqueteId";
$result_detalles = $mysqli->query($sql_detalles);

$detalles_paquete_items = "";
while ($row_detalles = mysqli_fetch_assoc($result_detalles)) {
    $detalles_paquete_items .= "<tr>" .
                                "<td>" . $row_detalles['nombre'] . "</td>" .
                                "<td>" . $row_detalles['tipo'] . "</td>" .
                                "<td>" . $row_detalles['cantidad'] . "</td>" .
                                "</tr>";
}

// Obtener el valor total almacenado en la tabla paquete_cabecera
$sql_valor_total = "SELECT total FROM paquete_cabecera";
$result_valor_total = $mysqli->query($sql_valor_total);
$row_valor_total = mysqli_fetch_assoc($result_valor_total);
$valorTotal = $row_valor_total['total'];

// Construir la respuesta JSON
$response = array(
    "detalles_paquete" => " <td> " . $row_paquete['titulo_paquete']. "</td>".
                          " <td> " . $row_paquete['numero_sesiones']. "</td>".
                          " <td> " . $row_paquete['total']. "</td>",
    "detalles_paquete_items" =>  $detalles_paquete_items,
    "precio_paquete" => $precioPaquete,
    "valor_total" => $valorTotal
);

// Devolver la respuesta JSON
echo json_encode($response);
?>
