<?php
include '../conection/conection.php';
date_default_timezone_set('America/Bogota');
$dia_hoy = date("d");
$mes_hoy = date("m");
$anio_hoy = date("Y");
$filtro_doctor = isset($_GET['doctor']) ? $_GET['doctor'] : '';

if ($rol === 'adm' or $rol === 'fis') {
    $sql_citas_hoy = "SELECT * FROM citas_datos where MONTH(fecha_cita) = '$mes_hoy' and YEAR(fecha_cita) = '$anio_hoy' and consultado='no' ";
    if ($filtro_doctor !== '') {
        $sql_citas_hoy .= " AND id_doctor = 1 ";
    }
} else {
    $sql_citas_hoy = "SELECT * FROM citas_datos where DAY(fecha_cita) = '$dia_hoy' and MONTH(fecha_cita) = '$mes_hoy' and YEAR(fecha_cita) = '$anio_hoy' and consultado='no' and id_doctor=$id_usuario";
}

$result_citas_hoy = $mysqli->query($sql_citas_hoy);

// Verificar si la consulta fue exitosa
if (!$result_citas_hoy) {
    die("Error en la consulta de citas: " . $mysqli->error);
}

if ($result_citas_hoy->num_rows == 0) {
    echo "<tr><td colspan='5' style='text-align: center'>No hay citas agendadas para hoy</td></tr>";
}
while ($row = mysqli_fetch_array($result_citas_hoy)) {
    echo "<tr>"; 
    $id = $row['id'];

    echo "<td>" . $row['fecha_cita'] . "</td>";
    echo "<td>";
    if ($row['rol'] == 'fis' or $row['rol'] == 'adm') {
        echo "Fisioterapia";
    } elseif ($row['rol'] == 'doc') {
        echo "Traumatología";
    }
    echo "</td>";
    echo "<td>" . $row['nombre_creador'] . "</td>";
    echo "<td>" . $row['nombre_doctor'] . "</td>";
    echo "<td>" . $row['nombres_paciente'] . " " . $row['apellidos_paciente'] . "</td>";
    echo "<td>" . $row['numero_identidad'] . "</td>";

    $id_paciente = $row['id_paciente'];

    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {


        echo "<td>";

        if ($rol == 'adm' or $row['id_doctor'] == $id_usuario) {

            if ($rol == 'doc') {

                echo "<a class='btn btn-success btn-sm' href='bandeja_de_atencion.php?id_cita=$id'>Atender</a>";
                //echo "<a href='cancelar_cita.php?id_cita=$id' class='btn btn-danger btn-sm'>Cancelar Cita</a>";

            }
            if ($rol == 'adm') {

                echo "<a href='cancelar_cita.php?id_cita=$id' class='btn btn-danger btn-sm'>Cancelar Cita</a>";
            }

            if ($rol == 'fis' or $rol == 'adm') {

                $sql_fisio = "SELECT * FROM consultas_fisioterapeuta WHERE paciente_id = $id_paciente AND estado ='pagado'";
                $respuesta_fisio = $mysqli->query($sql_fisio);

                // Inicializamos una variable para verificar si hay sesiones pendientes
                $sesiones_pendientes = false;

                while ($row_fisio = $respuesta_fisio->fetch_assoc()) {
                    $num_sesion = $row_fisio['numero_sesiones'];

                    if ($num_sesion > 0) {
                        // Si encontramos una sesión con valor mayor a cero, marcamos como true
                        $sesiones_pendientes = true;
                        // Puedes almacenar aquí el ID de la consulta de fisioterapeuta si lo necesitas
                        $id_consulta = $row_fisio['consulta_fisio_id']; // Suponiendo que el campo se llama 'id'
                    }
                }

                if ($sesiones_pendientes) {
                    // Si hay sesiones pendientes, puedes usar el ID de la última consulta encontrada
                    if ($row['rol'] == 'fis' or $row['rol'] == 'adm') {


                        echo "<a class='btn btn-success btn-sm' href='evaluacion_paciente.php?id_consulta=$id_consulta&id_cita=$id'>Atender Fis</a>";
                    }
                } else {
                    echo "<a class='btn btn-danger btn-sm'>No tiene sesiones</a>";
                }
            }
        }

        echo "</td>";
    }

    echo "</tr>";
    
}
