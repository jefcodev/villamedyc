<?php

use GuzzleHttp\Psr7\Query;

include '../conection/conection.php';

$idConsulta = $_POST['idConsulta'];
$totalPaquete = $_POST['totalPaquete'];
$paqueteId = $_POST['paqueteId'];

// Escapar los valores para evitar problemas de seguridad
$idConsulta = $mysqli->real_escape_string($idConsulta);
$paqueteId = $mysqli->real_escape_string($paqueteId);
$totalPaquete = $mysqli->real_escape_string($totalPaquete);



// Insertar los datos en la tabla de ventas
$fechaVenta = date("Y-m-d"); // Fecha actual
$sql_insertar_venta = "INSERT INTO ventas (fecha_venta, id_consulta, id_paquete, total) VALUES ('$fechaVenta', '$idConsulta', '$paqueteId', '$totalPaquete')";
$result_insertar_venta = $mysqli->query($sql_insertar_venta);

// Consultar id_paciente 
$consultaDatos = "SELECT * FROM consultas_datos WHERE id_consulta = $idConsulta";
$result_datos = $mysqli->query($consultaDatos);
$row_datos = $result_datos->fetch_assoc();
$id_paciente = $row_datos['id_paciente'];
$fecha_hora = $row_datos['fecha_hora'];


// Verificar si la inserción fue exitosa
if ($result_insertar_venta) {

    // Actualizar el campo 'estado' en la tabla 'citas'
    $actualizarEstado = "UPDATE consultas SET estado = 'pagado' WHERE id = $idConsulta";
    $mysqli->query($actualizarEstado);

    //Insertar venta a la tabla fisioterapia
    $isertar_venta_fisio = "INSERT INTO `consultas_fisioterapeuta`(`paciente_id`, `usuario_id`, `paquete_id`, 
    `fecha`, `profesion`, `tipo_trabajo`, `sedestacion_prolongada`, `esfuerzo_fisico`, 
    `habitos`, `antecendentes_diagnostico`, `tratamientos_anteriores`, `contracturas`, 
    `irradiacion`, `hacia_donde`, `intensidad`, `sensaciones`, `limitacion_movilidad`, `estado_atencion`)  
    VALUES ($id_paciente, 2, $paqueteId, '', '', '', 0, 0, '', '', '', '', 0, '', '', '', 0, 'Por Asignar Cita')";
    $result_venta_fisio = $mysqli->query($isertar_venta_fisio);

    if ($result_venta_fisio) {
        $response = array(
            "success" => true,
            "message" => "Venta realizada correctamente: "
        );
    } else {
        $response = array(
            "success" => false,
            "message" => "Error al insertar a tabla fisioterapia: " . $mysqli->error
        );
    }

    $response = array(
        "success" => true,
        "message" => "Venta realizada correctamente: "
    );
} else {
    $response = array(
        "success" => false,
        "message" => "Error al realizar la venta: " . $mysqli->error
    );
}

// Devolver la respuesta JSON
echo json_encode($response);
