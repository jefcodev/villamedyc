<?php

use GuzzleHttp\Psr7\Query;

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
                        <select name="" id="test">
                            <option value="0">Filtrar por:</option>
                            <option value="1">Dia</option>
                            <option value="2">Semana</option>
                            <option value="3">Mes</option>
                        </select>
                        <table class="table table-bordered table-hover" id="indexconsultas">
                            <thead class="tabla_cabecera">
                                <tr>
                                    <th>Fecha cita</th>
                                    <th>Doctor</th>
                                    <th>Paciente</th>
                                    <th>Cédula</th>
                                    <?php
                                    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        // if ($rol == 'adm' or $rol == 'doc') { 
                                    ?>
                                        <th>Atender</th>
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
                                if ($rol === 'adm') {
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
                                    echo "<td>" . $row['nombre_doctor'] . "</td>";
                                    echo "<td>" . $row['nombres_paciente'] . " " . $row['apellidos_paciente'] . "</td>";
                                    echo "<td>" . $row['numero_identidad'] . "</td>";

                                    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        echo "<td>";
                                        if ($rol == 'adm' or $row['id_doctor'] == $id_usuario) {
                                            echo "<a class='btn btn-success btn-sm' href='bandeja_de_atencion.php?id_cita=$id'>Atender</a>";
                                        }
                                        echo "</td>";
                                    }

                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>


                        <table class="table table-bordered table-hover" id="indexconsultas">
                            <thead class="tabla_cabecera">
                                <tr>
                                    <th>Fecha cita</th>
                                    <th>Doctor</th>
                                    <th>Paciente</th>
                                    <th>Valor</th>
                                    <th>Acciones </th>
                                </tr>
                            </thead>
                            <tbody id="indexconsultas">
                                <?php

                                $pagos = "SELECT * FROM consultas WHERE estado = 'pendiente'";
                                $resulPagos = $mysqli->query($pagos);
                                while ($row = mysqli_fetch_array($resulPagos)) {
                                    $id = $row['id_consulta'];
                                    echo "<td>" . $row['fecha_hora'] . "</td>";
                                    echo "<td>" . $row['nombre_doctor'] . " " . $row['apellidos_paciente'] . "</td>";
                                    echo "<td>" . $row['nombres'] . ' ' . $row['apellidos'] . "</td>";
                                    echo "<td>" . $row['precio'] . "</td>";
                                    echo "<td>";
                                    if ($rol == 'adm') {
                                        echo "<a class='btn btn-success btn-sm' href='cobrar.php?id_cita=$id'>Cobrar</a>";
                                        echo "&nbsp;&nbsp;";
                                        echo "<a class='btn btn-success btn-sm' href=''>Agregar</a>";
                                    }
                                    echo "</td>";
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
        $(document).ready(function() {
            var table = $('#indexconsultas').DataTable();

            $('#test').on("change", function() {
                table.draw();
            });
        });

        $.fn.dataTable.ext.search.push(
            function(oSettings, aData, iDataIndex) {

                var date = $('#test').val();
                var today = new Date();
                var day = moment(today).format('YYYY-MM-DD');

                if (date == 1 || date == 0) {
                    var dateIni = day + ' 000000';
                    var dateFin = day + ' 235959';
                }
                if (date == 2) {
                    // Date.prototype.GetFirstDayOfWeek = function() {
                    //     return (new Date(this.setDate(this.getDate() - this.getDay())));
                    // }
                    // alert(today.GetFirstDayOfWeek());

                    Date.prototype.GetLastDayOfWeek = function() {
                        return (new Date(this.setDate(this.getDate() - this.getDay() + 6)));
                    }

                    var lastDay = moment(today.GetLastDayOfWeek()).format('YYYY-MM-DD');

                    var dateIni = day + ' 000000';
                    var dateFin = lastDay + ' 235959';
                }
                if (date == 3) {
                    var dateIni = day + ' 000000';
                    var dateFin = '20230631 000000';
                }

                var indexCol = 0;

                dateIni = dateIni.replace(/-/g, "");
                dateFin = dateFin.replace(/-/g, "");

                var dateCol = aData[indexCol].replace(/-/g, "");

                if (dateIni === "" && dateFin === "") {
                    return true;
                }

                if (dateIni === "") {
                    return dateCol <= dateFin;
                }

                if (dateFin === "") {
                    return dateCol >= dateIni;
                }

                return dateCol >= dateIni && dateCol <= dateFin;
            }
        );
    </script>
</body>