<!DOCTYPE html>
<html>
<?php
include 'header.php';
include '../conection/conection.php';
$status = $_GET['status']; 
$class = '';
if (isset($status)) {
    if ($status === 'OK') {
        $error = 'Cita creada correctamente';
        $class = 'class="alert alert-success"';
    } else {
        $error = 'Ocurrió un error al crear la cita';
        $class = 'class="alert alert-danger"';
    }
}
?>


<body>
    <div class="row mt-5">


        <section class="cuerpo">
            <div id="mensajes" <?php echo $class; ?>>
                <?php echo isset($error) ? $error : ''; ?>
            </div>
            <h1>Crear Cita</h1><br>
            <div class="row">
                <div class="col-md-12">
                    <b style="color: #28a745">Buscar un paciente por CI, Nombre o Apellido </b><br><br>
                </div>

            </div>
            <div class="row">

                <div class="col-md-12">
                    <form action="adm_citas.php" method="post">
                        <div class="row">
                            <div class="col-md-12">


                                <?php

                                $sql = "SELECT * FROM `pacientes`";

                                $result = $mysqli->query($sql);

                                ?>
                                <select class="select2 form-control" data-rel="chosen" id='id_paciente' name='id_paciente'>
                                    <option value="" selected="" hidden="">Seleccione el Paciente</option>
                                    <?php
                                    if ($result) {
                                        while ($fila = mysqli_fetch_array($result)) {
                                            $selected = '';
                                            if (isset($_GET['id_paciente']) && $_GET['id_paciente'] == $fila["id"]) {
                                                $selected = 'selected';
                                            }
                                            echo "<option value='{$fila["id"]}' {$selected}>{$fila["numero_identidad"]}  {$fila["nombres"]}  {$fila["apellidos"]}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <br><br>

                                <select class="form-control" id="doctor" name="doctor" required>
                                    <option value="" selected="" hidden="">Seleccione el Doctor</option>
                                    <?php
                                    $sql_traer_doctor = "SELECT * FROM usuarios WHERE rol = 'fis' or  rol = 'doc'";
                                    $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                                    while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . ' ' . $row['apellidos'] . "</option>";
                                    }
                                    ?>
                                </select>

                                <!-- <input type='hidden' id='id_paciente' name='id_paciente'/> -->
                                <input class="form-control" type="text" autocomplete="off" placeholder="Fecha y hora de la cita" id="fecha_cita" name="fecha_cita" required /><br>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <input class="btn btn-primary" type="submit" name="btn_crear_cita" id="btn_crear_cita" value="Aceptar" />
                            </div>
                        </div>
                    </form>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-8">
                    <div id="miDiv" class="alert alert-danger" role="alert" style="display: none"></div>
                </div>
            </div>
        </section>
    </div>


    <?php
    include 'footer.php';
    ?>


    <script type="text/javascript">
        $('.select2').select2({});
    </script>
    <script language="javascript" src="../js/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('#fecha_cita').datetimepicker({
            startDate: new Date(),
            value: new Date(),
            step: 15,
            minDate: 0,
            minTime: '06:00',
            maxTime: '20:00',
            dayOfWeekStart: 0,
            disabledWeekDays: [0, 6],
            closeOnDateSelect: false,
            closeOnTimeSelect: true,
            onSelectTime: function(ct) {
                validarDisponibilidadHorarios();
            }
        });
        $.datetimepicker.setLocale('es');
        $(document).ready(function() {
            setTimeout(function() {
                $("#mensajes").fadeOut(1500);
            }, 2500);
        });

        function buscar_paciente() {
            var numero_id = $("#numero_identidad").val();
            $.ajax({
                url: 'buscar_paciente.php',
                type: 'post',
                data: {
                    numero_identidad: numero_id
                },
                success: function(response) {
                    $("#resultado_paciente").html(response);
                    $('input[name="id_paciente"]').val($("#id_paciente_resultado").val());
                }
            });
        }

        function validar() {
            var numero_identidad = document.getElementById('numero_identidad').value;
            var miDiv = document.getElementById('miDiv');
            var html = "";
            if (numero_identidad === "") {
                document.getElementById("miDiv").style.display = 'block';
                miDiv.innerHTML = ""; //innerHTML te añade código a lo que ya haya por eso primero lo ponemos en blanco.
                html = "No puede dejar el campo Cédula o Pasaporte vacío, debe antes de crear la cita buscar al paciente.";
                miDiv.innerHTML = html;
                return false;
            }
        }
        $('#doctor').change(function() {
            validarDisponibilidadHorarios();
        });

        function validarDisponibilidadHorarios() {
            var fecha_cita = $("#fecha_cita").val();
            var doctor = $("#doctor").val();
            $.ajax({
                url: 'consultar_disponibilidad_horarios.php',
                type: 'post',
                data: {
                    fecha_cita: fecha_cita,
                    doctor: doctor,
                },
                success: function(response) {
                    if ('' === response) {
                        var miDiv = document.getElementById('miDiv');
                        var html = "";
                        document.getElementById("miDiv").style.display = 'block';
                        miDiv.innerHTML = ""; //innerHTML te añade código a lo que ya haya por eso primero lo ponemos en blanco.
                        html = "La fecha seleccionada no está disponible, por favor seleccione otro horario.";
                        miDiv.innerHTML = html;
                        $("#fecha_cita").datetimepicker('show')
                            .datetimepicker('reset');
                    }
                }
            });
        }
    </script>
</body>

</html>