<?php
include 'header.php';
$status = $_GET['status'];
$pagina = PAGINAS::INICIO;
$class = '';
$close = '';
if (isset($status)) {
    $close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';
    if ($status === 'AD') {
        $error = 'Usted no tiene accesos para ejecutar la acción solicitada';
        $class = 'class="alert alert-danger alert-dismissible fade show" role="alert"';
    } else {
        $error = 'Exitoso';
        $class = 'class="alert alert-success alert-dismissible fade show" role="alert"';
    }
}
?>

<body>
    <div class="cuerpo">
        <div <?php echo $class; ?>>
            <?php echo isset($error) ? $error : ''; ?>
            <?php echo $close; ?>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 logos_azul">
                        <a href="lista_pacientes.php">
                            <i class="fas fa-users fa-6x"></i><br>
                            <label>Pacientes</label>
                        </a>
                    </div>
                    <div class="col-md-6 logos_azul">
                        <a href="lista_citas.php">
                            <i class="fas fa-calendar-alt fa-6x"></i><br>
                            <label>Citas</label>
                        </a>
                    </div>
                </div><br><br><br><br>
                <div class="row">
                    <div class="col-md-6 logos_azul">
                        <a href="lista_historias.php">
                            <i class="far fa-file-alt fa-6x"></i><br>
                            <label>Historias</label>
                        </a>
                    </div>
                    <div class="col-md-6 logos_azul">
                        <a href="#">
                            <i class="fas fa-print fa-6x"></i><br>
                            <label>Impresión</label>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-11">
                        <table class="table table-bordered table-hover" id="indexconsultas">
                            <thead class="tabla_cabecera">
                                <tr>
                                    <th>Fecha</th>
                                    <th>No Historia</th>
                                    <th>Cédula</th>
                                    <th>Paciente</th>
                                    <?php
                                    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        // if ($rol == 'adm' or $rol == 'doc') { 
                                    ?>
                                        <th>Acciones</th>
                                    <?php
                                        // }
                                    } ?>
                                </tr>
                            </thead>
                            <tbody id="indexconsultas">
                                <?php
                                date_default_timezone_set('America/Bogota');
                                $dia_hoy = date("d");
                                $mes_hoy = date("m");
                                $anio_hoy = date("Y");
                                if ($rol === 'adm' || $rol === 'fis') {
                                    $sql_citas_hoy = "SELECT cf.consulta_fisio_id, p.id as paciente_id, cf.fecha, pc.titulo_paquete, pc.tipo_paquete, pc.numero_sesiones, pc.total, p.numero_identidad, CONCAT(p.nombres, ' ', p.apellidos) as nombres 
                                                        FROM consultas_fisioterapeuta cf, pacientes p, paquete_cabecera pc
                                                        WHERE cf.paciente_id = p.id AND pc.paquete_id = cf.paquete_id AND cf.estado_atencion='Por Atender'";
                                }
                                $result_citas_hoy = $mysqli->query($sql_citas_hoy);
                                if ($result_citas_hoy->num_rows == 0) {
                                    echo "<tr><td colspan='5' style='text-align: center'>No hay sesiones agregadas</td></tr>";
                                }
                                while ($row = mysqli_fetch_array($result_citas_hoy)) {
                                    $id = $row['consulta_fisio_id'];
                                    echo "<tr id='" . $row['consulta_fisio_id'] . "'>";
                                    echo "<td>" . $row['fecha'] . "</td>";
                                    echo "<td>VM-001-" . $row['paciente_id'] . "</td>";
                                    echo "<td>" . $row['numero_identidad'] . "</td>";
                                    echo "<td>" . $row['nombres'] . "</td>";

                                    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        echo "<td>";
                                        // if ($rol == 'adm' or $row['usuario_id'] == $id_usuario) {
                                        // echo "<a class='btn btn-success btn-sm' href='bandeja_de_atencion.php?id_cita=$id'>Ver Detalles</a>";
                                        echo "<a class='btn btn-success btn-sm' href='evaluacion_paciente.php?consulta_fisio_id=$id'>Atender</a>";
                                        // }
                                        echo "</td>";
                                    }

                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    <?php
    include 'footer.php';
    ?>
    <script src="../js/jquerysearch.js"></script>
    <script src="https://momentjs.com/downloads/moment-with-locales.min.js"></script>
    <script>

    </script>
</body>