<?php
// Establecer la conexión con la base de datos
include '../conection/conection.php';

$id_consulta = $_POST['id_consulta'];
// Consultar el precio de la consulta
$sql = "SELECT precio FROM consultas_datos WHERE id_consulta = $idConsulta"; // Reemplaza "1" con el ID correspondiente a la consulta que deseas obtener el precio
$resultado = $mysqli->query($sql);

if ($resultado->num_rows > 0) {
  $row = $resultado->fetch_assoc();
  $precioConsulta = $row['precio'];

  // Devolver el precio de la consulta en formato JSON
  echo json_encode(['precio' => $precioConsulta]);
} else {
  echo json_encode(['error' => 'No se encontró el precio de la consulta']);
}
?>
