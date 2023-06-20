<?php
include '../conection/conection.php';

// Obtener los datos del formulario
$idConsulta = $_GET['id_cita']; // Suponiendo que estás pasando el id de la consulta como parámetro en la URL

// Obtener los datos de la consulta para insertar en la tabla de ventas
$consultaDatos = "SELECT * FROM consultas_datos WHERE id_consulta = $idConsulta";
$resultadoConsulta = $mysqli->query($consultaDatos);
$row = $resultadoConsulta->fetch_assoc();

$fechaVenta = date("Y-m-d"); // Fecha actual
$total = $row['precio']; // Suponiendo que el precio se encuentra en la columna 'precio' de la tabla consultas_datos

// Insertar los datos en la tabla de ventas
$insertarVenta = "INSERT INTO ventas (fecha_venta, id_consulta, total) VALUES ('$fechaVenta', $idConsulta, $total)";
if ($mysqli->query($insertarVenta)) {
    // Actualizar el campo 'estado' en la tabla 'citas'
    $actualizarEstado = "UPDATE consultas SET estado = 'pagado' WHERE id = $idConsulta";
    if ($mysqli->query($actualizarEstado)) {
        echo "<script> window.location.href = 'inicio.php';</script>";
    } else {
        echo "Error al actualizar el estado de la cita: " . $mysqli->error;
    }
} else {
    echo "Error al registrar la venta: " . $mysqli->error;
}

// Cerrar la conexión con la base de datos
$mysqli->close();
?>