<?php
include 'header.php';
include '../conection/conection.php';
$status = $_GET['status'];
$pagina = PAGINAS::INICIO;
$class = '';
$close = '';
$id_user = $_SESSION['id'];

// Verificar si se ha seleccionado un doctor
echo '
<table class="table table-bordered table-hover"  >
                

    <thead class="tabla_cabecera">
                    <tr>
                        <th>Fecha</th>
                        <th>Área</th>
                        <th>Usuario</th>
                        <th>Doctor</th>
                        <th>Paciente</th>
                        <th>Cédula</th>
                        <th>Atender</th>
                    </tr>
                </thead>
    ';
if (isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];

    // Consultar las citas del doctor para el día actual
    date_default_timezone_set('America/Bogota');
    $dia_hoy = date("d");
    $mes_hoy = date("m");
    $anio_hoy = date("Y");
    
    if($doctor_id == 'todos' ){

        $citas_del_dia = mysqli_query($mysqli, "SELECT * FROM citas_datos WHERE DAY(fecha_cita) = '$dia_hoy' AND MONTH(fecha_cita) = '$mes_hoy' AND YEAR(fecha_cita) = '$anio_hoy' AND consultado='no' ORDER BY fecha_cita ASC");

    } else
    {

        $citas_del_dia = mysqli_query($mysqli, "SELECT * FROM citas_datos WHERE DAY(fecha_cita) = '$dia_hoy' AND MONTH(fecha_cita) = '$mes_hoy' AND YEAR(fecha_cita) = '$anio_hoy' AND consultado='no' AND id_doctor=$doctor_id ORDER BY fecha_cita ASC");
    }
    
    
    echo '<tbody>';
    if (mysqli_num_rows($citas_del_dia) > 0) {
        while ($cita = mysqli_fetch_assoc($citas_del_dia)) {
            $id = $cita['id'];
            echo '<tr>';
            echo '<td>' . $cita['fecha_cita'] . '</td>';
            echo '<td>';
            if ($cita['rol'] == 'fis' or $cita['rol'] == 'adm') {
                echo 'Fisioterapia';
            } elseif ($cita['rol'] == 'doc') {
                echo 'Traumatología';
            }
            echo '</td>';
            echo '<td>' . $cita['nombre_creador'] . '</td>';
            echo '<td>' . $cita['nombre_doctor'] . '</td>';
            echo '<td>' . $cita['nombres_paciente'] . ' ' . $cita['apellidos_paciente'] . '</td>';
            echo '<td>' . $cita['numero_identidad'] . '</td>';
            

            $id_paciente = $cita['id_paciente'];

            if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {


                echo "<td>";

                if ($rol == 'adm' or $cita['id_doctor'] == $id_usuario) {

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
                            if ($cita['rol'] == 'fis' or $cita['rol'] == 'adm') {
                                echo "<a class='btn btn-success btn-sm' href='evaluacion_paciente.php?id_consulta=$id_consulta&id_cita=$id'>Atender Fis</a>";
                            }
                        } else {
                            echo "<a class='btn btn-danger btn-sm'>No tiene sesiones</a>";
                        }
                    }
                }

                echo "</td>";
                echo '</tr>';
            }
        }
    } else {
        echo '<tr><td colspan="6">No se encontraron citas para el día de hoy</td></tr>';
    }
} else {
    echo '<tr><td colspan="6">Seleccione una opción</td></tr>';
}

echo '</tbody> </table>';


?>
