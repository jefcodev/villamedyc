<!DOCTYPE html>
<html>
<?php
include 'header.php';
$status = $_GET['status'];
$pagina = PAGINAS::INICIO;
$class = '';
$close = '';
$id_user = $_SESSION['id'];
?>

<body>
    <div class="cuerpo">
        <div class="row">
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-6 logos_azul">
                        <a href="crear_paciente.php">
                            <i class="fas fa-users fa-6x"></i><br>
                            <label>Nuevo Paciente</label>
                        </a>
                    </div>
                    <div class="col-md-6 logos_azul">
                        <a href="crear_cita.php">
                            <i class="fas fa-calendar-alt fa-6x"></i><br>
                            <label>Nueva Cita</label>
                        </a>
                    </div>
                </div><br><br><br><br>
                <div class="row">
                    <div class="col-md-6 logos_azul">
                        <a href="crear_venta.php">
                            <i class="fas fa-basket-shopping fa-6x"></i><br>
                            <label>Nueva Venta</label>
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
            <div class="col-md-9">
                <div class="row">
                    <div class="col-md-12">
                        <select name="" id="test">
                            <option value="0">Filtrar por:</option>
                            <option value="1">Dia</option>
                            <option value="2">Semana</option>
                            <option value="3">Mes</option>
                        </select>
                        <?php
                        if ($rol == 'adm' OR $rol == 'fis') {


                        ?>

                            <form id="form_doctor">
                                <label for="doctor_select">Seleccionar Doctor:</label>
                                <select id="doctor_select" name="doctor_id">

                                    <option value="todos">Seleccione</option>
                                    <option value="todos">Todos</option>
                                    <?php
                                    // Consultar la tabla de usuarios para obtener la lista de doctores
                                    $result = mysqli_query($mysqli, "SELECT id, nombre FROM usuarios WHERE rol = 'doc' OR rol = 'adm' OR  rol = 'fis' ");
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </form>

                            <div id="citas_container">
                            </div>

                        <?php

                        }
                        ?>

                        <?php
                        if ($rol == 'doc') {
                        ?>
                            <table class="table table-bordered table-hover" id="indexconsultas">
                                <thead class="tabla_cabecera">
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Área</th>
                                        <th>Usuario</th>
                                        <th>Doctor</th>
                                        <th>Paciente</th>
                                        <th>Cédula</th>
                                        <?php
                                        if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        ?>
                                            <th>Atender</th>
                                        <?php
                                        }
                                        ?>
                                    </tr>
                                </thead>
                                <tbody id="indexconsultas">
                                    <?php
                                    date_default_timezone_set('America/Bogota');

                                    $dia_hoy = date("d");
                                    $mes_hoy = date("m");
                                    $anio_hoy = date("Y");

                                    if ($rol === 'adm' or $rol === 'fis') {
                                        $sql_citas_hoy = "SELECT * FROM citas_datos where MONTH(fecha_cita) = '$mes_hoy' and YEAR(fecha_cita) = '$anio_hoy' and consultado='no' order by fecha_cita asc";
                                    } else {
                                        $sql_citas_hoy = "SELECT * FROM citas_datos where DAY(fecha_cita) = '$dia_hoy' and MONTH(fecha_cita) = '$mes_hoy' and YEAR(fecha_cita) = '$anio_hoy' and consultado='no' and id_doctor=$id_usuario order by fecha_cita asc";
                                    }
                                    $result_citas_hoy = $mysqli->query($sql_citas_hoy);
                                    if ($result_citas_hoy->num_rows == 0) {
                                        echo "<tr><td colspan='5' style='text-align: center'>No hay citas agendadas para hoy</td></tr>";
                                    }
                                    while ($row = mysqli_fetch_array($result_citas_hoy)) {
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
                                    ?>
                                </tbody>
                            </table>
                        <?php
                        }
                        ?>


                        <?php
                        if ($rol == 'adm') {
                            // if ($rol == 'adm' or $rol == 'doc') { 
                        ?>
                            <table class="table table-bordered table-hover" id="indexconsultas1">

                                <thead class="tabla_cabecera">
                                    <tr>
                                        <th>Fecha cita</th>
                                        <th>Usuario</th>
                                        <th>Paciente</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="indexconsultas1">
                                    <?php
                                    $pagos = "SELECT * FROM consultas_datos WHERE estado = 'pendiente'";
                                    $resulPagos = $mysqli->query($pagos);
                                    if ($resulPagos->num_rows == 0) {
                                        echo "<tr><td colspan='5' style='text-align: center'>No hay cobros generados</td></tr>";
                                    }
                                    while ($row = mysqli_fetch_array($resulPagos)) {
                                        $id = $row['id_consulta'];
                                        echo "<td>" . date('Y-m-d', strtotime($row['fecha_hora'])) . "</td>";
                                        echo "<td>" . $row['nombre_doctor'] . " " . $row['apellidos_paciente'] . "</td>";
                                        echo "<td>" . $row['nombres'] . ' ' . $row['apellidos'] . "</td>";
                                        echo "<td>";
                                        echo "<a class='btn btn-success btn-sm' href='nota_venta.php?id_cita=$id'>Cobrar</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }

                                    $pagos_ventas = "  SELECT vc.*, p.nombres, p.apellidos
                                                FROM ventas_cabecera vc
                                                LEFT JOIN pacientes p ON vc.id_paciente = p.id
                                                WHERE vc.estado = 'pendiente'";
                                    $resulPagos_v = $mysqli->query($pagos_ventas);
                                    if ($resulPagos_v->num_rows == 0) {
                                        echo "<tr><td colspan='5' style='text-align: center'>No hay cobros generados</td></tr>";
                                    }
                                    while ($row_v = mysqli_fetch_array($resulPagos_v)) {
                                        //$id = $row['id_consulta'];
                                        $id_venta = $row_v['id'];
                                        echo "<td>" . $row_v['fecha_venta'] . "</td>";
                                        echo "<td>" . $row_v['usuario'] . "</td>";
                                        echo "<td>" . $row_v['nombres'] . ' ' . $row_v['apellidos'] . "</td>";
                                        echo "<td>";
                                        //echo "<td><button class='btn btn-info edit-venta' data-venta-id='" . $row['id_venta'] . "'>Editar</button></td>";
                                        echo "<a class='btn btn-success btn-sm' href='editar_venta.php?id_venta=$id_venta'>Cobrar</a>";
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        <?php
                            // }
                        } ?>

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
        var phpDia = <?php echo json_encode($id_user); ?>;
        console.log('Rol ' + phpDia)
        $(document).ready(function() {
            var table = $('#indexconsultas').DataTable();
            $('#test').on("change", function() {
                table.draw();
            });
        });
        $.fn.dataTable.ext.search.push(
            function(oSettings, aData, iDataIndex) {
                var date = $('#test').val();
                var today = moment(); // Utilizamos Moment.js para manejar fechas
                today.locale('es'); // Establecemos la localización, por ejemplo, 'es' para español
                var day = today.format('YYYY-MM-DD');
                var lastDayOfMonth = moment(today).endOf('month').format('YYYY-MM-DD');
                if (date == 1 || date == 0) {
                    var dateIni = day + ' 00:00:00';
                    var dateFin = day + ' 23:59:59';
                }
                if (date == 2) {
                    var lastDayOfWeek = moment(today).endOf('week').format('YYYY-MM-DD');
                    var dateIni = day + ' 00:00:00';
                    var dateFin = lastDayOfWeek + ' 23:59:59';
                }
                if (date == 3) {
                    var dateIni = day + ' 00:00:00';
                    var dateFin = lastDayOfMonth + ' 00:00:00';
                }
                var indexCol = 0;
                var dateCol = moment(aData[indexCol], 'YYYY-MM-DD HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
                if (dateIni === "" && dateFin === "") {
                    return true;
                }
                if (dateIni === "") {
                    return dateCol <= dateFin;
                }
                if (dateFin === "") {
                    return dateCol >= dateIni;
                }
                return moment(dateCol, 'YYYY-MM-DD HH:mm:ss').isBetween(dateIni, dateFin);
            }
        );
    </script>
    <script>
        // Función para cargar las citas al seleccionar un doctor o al cargar la página
        function cargarCitas(doctor_id) {
            // Realizar una solicitud AJAX para obtener las citas del doctor seleccionado
            $.ajax({
                url: 'buscar_citas.php',
                method: 'POST',
                data: {
                    doctor_id: doctor_id
                },
                success: function(response) {
                    // Insertar el contenido del segundo select en el div con id "citas_container"
                    $('#citas_container').html(response);
                }
            });
        }

        $(document).ready(function() {
            // Llamar a la función al cargar la página para obtener todas las citas
            cargarCitas();

            // Agregar evento de cambio al select de doctores
            $('#doctor_select').on('change', function() {
                var doctor_id = $(this).val();
                // Llamar a la función al cambiar el select de doctores
                cargarCitas(doctor_id);
            });
        });
    </script>
</body>

</html>