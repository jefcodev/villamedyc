
<!DOCTYPE html>
<html>
<?php
include 'header.php';
$status = $_GET['status'];
$pagina = PAGINAS::INICIO;
$class = '';
$close = '';

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
                        <table class="table table-bordered table-hover" id="indexconsultas">
                            <thead class="tabla_cabecera">
                                <tr>
                                    <th>Fecha cita</th>
                                    <th>Usuario</th>
                                    <th>Doctor</th>
                                    <th>Paciente</th>
                                    <th>Cédula</th>
                                    <?php
                                    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        // if ($rol == 'adm' or $rol == 'doc') { 
                                    ?>
                                        <th>Atender</th>
                                    <?php
                                        //¿ }
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
                                    echo "<td>" . $row['nombre_creador'] . "</td>";
                                    echo "<td>" . $row['nombre_doctor'] . "</td>";
                                    echo "<td>" . $row['nombres_paciente'] . " " . $row['apellidos_paciente'] . "</td>";
                                    echo "<td>" . $row['numero_identidad'] . "</td>";

                                    $id_paciente = $row['id_paciente'];

                                    if (Seguridad::tiene_permiso($rol, $pagina, ACCIONES::ATENDER_CITA)) {
                                        echo "<td>";
                                        if ($rol == 'adm' or $row['id_doctor'] == $id_usuario) {

                                            if( $rol == 'doc' ){

                                                echo "<a class='btn btn-success btn-sm' href='bandeja_de_atencion.php?id_cita=$id'>Atender</a>";

                                            }
                                            if( $rol == 'fis' ){

                                                $sql_fisio = "SELECT * FROM consultas_fisioterapeuta WHERE paciente_id = $id_paciente";
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
                                                    echo "<a class='btn btn-success btn-sm' href='evaluacion_paciente.php?id_consulta=$id_consulta&id_cita=$id'>Atender Fis</a>";
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
                                    if ($rol=='adm') {
                                        // if ($rol == 'adm' or $rol == 'doc') { 
                                    ?>
                        <table class="table table-bordered table-hover" id="indexconsultas">
                        
                            <thead class="tabla_cabecera">
                                <tr>
                                    <th>Fecha cita</th>
                                    <th>Doctor</th>
                                    <th>Paciente</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="indexconsultas">
                                <?php
                                

                                    $pagos = "SELECT * FROM consultas_datos WHERE estado = 'pendiente'";
                                    $resulPagos = $mysqli->query($pagos);
                                    if ($resulPagos->num_rows == 0) {
                                        echo "<tr><td colspan='5' style='text-align: center'>No hay cobros generados</td></tr>";
                                    }
                                    while ($row = mysqli_fetch_array($resulPagos)) {
                                        $id = $row['id_consulta'];
                                        echo "<td>" . $row['fecha_hora'] . "</td>";
                                        echo "<td>" . $row['nombre_doctor'] . " " . $row['apellidos_paciente'] . "</td>";
                                        echo "<td>" . $row['nombres'] . ' ' . $row['apellidos'] . "</td>";
                                        echo "<td>";
                                        if ($rol == 'adm') {
                                            
                                            echo "<a class='btn btn-success btn-sm' href='nota_venta.php?id_cita=$id'>Cobrar</a>";
                                        }
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
                var lastDayOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);


                if (date == 1 || date == 0) {
                    var dateIni = day + ' 000000';
                    var dateFin = day + ' 235959';
                }
                if (date == 2) {

                    Date.prototype.GetLastDayOfWeek = function() {
                        return (new Date(this.setDate(this.getDate() - this.getDay() + 6)));
                    }

                    var lastDay = moment(today.GetLastDayOfWeek()).format('YYYY-MM-DD');

                    var dateIni = day + ' 000000';
                    var dateFin = lastDay + ' 235959';
                }
                if (date == 3) {
                    var dateIni = day + ' 000000';
                    var dateFin = lastDayOfMonth+ ' 000000';
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

</html>