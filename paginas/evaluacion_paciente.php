<?php
include 'header.php';
$id_cita = $_GET['consulta_fisio_id'];
?>
<link rel="stylesheet" href="../css/bootstrap_select.min.css">

<body>
    <section class="cuerpo">
        <h1>Atención al paciente</h1><br>
        <?php
        $sql_datos_cita = "SELECT cf.*, p.id, p.numero_identidad, CONCAT(p.nombres,' ',p.apellidos) as nombres_paciente, pc.titulo_paquete, pc.tipo_paquete, pc.numero_sesiones FROM consultas_fisioterapeuta cf, pacientes p, paquete_cabecera pc 
        WHERE p.id=cf.paciente_id AND pc.paquete_id=cf.paquete_id AND consulta_fisio_id=$id_cita";
        $result_datos_cita = $mysqli->query($sql_datos_cita);
        $rowscita = $result_datos_cita->fetch_assoc();
        ?>
        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8">
            <b style="font-size: 18px">Datos de consulta</b><br>
            <div class="row" id="edicion_paciente">
                <div class="col-md-4">
                    <div><?php echo $rowscita['numero_historia'] ?></div>
                    <div><?php echo $rowscita['numero_identidad'] ?></div>
                    <div><?php echo $rowscita['nombres_paciente'] ?></div>
                    <div><?php echo $rowscita['titulo_paquete'] ?></div>
                    <div><?php echo $rowscita['numero_sesiones'] ?></div>
                </div>
            </div>
        </div><br>
        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8">
            <b style="font-size: 18px">Factores Ocupacionales</b><br><br>
            <div id="crear_consulta">
                <div class="row">
                    <div class="col-md-6">
                        <textarea class="form-control" title="Profesión" placeholder="Profesión" id="motivo_consulta" name="motivo_consulta"></textarea>
                        <textarea class="form-control" title="Tipo de trabajo" placeholder="Tipo de trabajo" id="examen_fisico" name="examen_fisico"></textarea>
                        <select class="form-control" id="estado_civil" name="estado_civil" title="Sedestación Prolongada">
                            <option value="3">Sedestacion Prolongada</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" id="estado_civil" name="estado_civil" title="Sedestación Prolongada">
                            <option value="0">Esfuerzo Fisico</option>
                            <option value="1">Bajo</option>
                            <option value="2">Medio</option>
                            <option value="3">Alto</option>
                        </select>
                        <textarea class="form-control" title="Diagnóstico" placeholder="Hábitos/Otras actividades" id="diagnostico" name="diagnostico"></textarea>
                    </div>
                </div>


                <b style="font-size: 18px">Diagnóstico</b><br><br>

                <div class="row">
                    <div class="col-md-6">
                        <textarea class="form-control" title="Profesión" placeholder="Antecedentes del diagnóstico" id="motivo_consulta" name="motivo_consulta"></textarea>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" title="Detalles precio" placeholder="Tratamientos anteriores" id="descripcion_precio" name="descripcion_precio"></textarea>
                    </div>
                </div>

                <b style="font-size: 18px">Palpación y Dolor</b><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <textarea class="form-control" title="Profesión" placeholder="Contracturas" id="motivo_consulta" name="motivo_consulta"></textarea>
                        <select class="form-control" id="estado_civil" name="estado_civil" title="Sedestación Prolongada">
                            <option value="3">Irradiación</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                        <textarea class="form-control" title="Profesión" placeholder="Hacia donde?" id="motivo_consulta" name="motivo_consulta"></textarea>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" title="Detalles precio" placeholder="Intensidad" id="descripcion_precio" name="descripcion_precio"></textarea>
                        <textarea class="form-control" title="Profesión" placeholder="Sensaciones" id="motivo_consulta" name="motivo_consulta"></textarea>
                    </div>
                </div>

                <b style="font-size: 18px">Limitación de la Movilidad</b><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control" id="estado_civil" name="estado_civil" title="Sedestación Prolongada">
                            <option value="1">Crujidos</option>
                            <optgroup label="Osteoarticular">
                                <option value="2">Topes articulares</option>
                                <option value="3">Musculo Tendinosos</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" id="id_cita" name="id_cita" value="<?php echo $id_cita; ?>" />
                    </div>
                    <div class="col-md-6">
                        <input class="btn btn-primary float-left" type="button" name="btn_crear_consulta" id="btn_crear_consulta" value="Guardar" onclick="crear_consulta()" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <?php
    include 'footer.php';
    ?>

    <script src="../js/bootstrap-select.js"></script>
    <script type="text/javascript">
        var select = document.getElementById("cie_10");
        var tArea = document.getElementById("diagnostico");
        select.onchange = function() {
            if (tArea.value.length === 0) {
                tArea.value = select.options[select.selectedIndex].value;
            } else {
                tArea.value = tArea.value + '\n' + select.options[select.selectedIndex].value;
            }
        }

        function editar_paciente() {
            var id_paciente = $("#id_paciente").val();
            var numero_identidad = $("#numero_identidad").val();
            var nombres = $("#nombres").val();
            var apellidos = $("#apellidos").val();
            var fecha_nacimiento = $("#fecha_nacimiento").val();
            var genero = document.getElementById("genero").value;
            var telefono_fijo = $("#telefono_fijo").val();
            var telefono_movil = $("#telefono_movil").val();
            var direccion = $("#direccion").val();
            var raza = document.getElementById("raza").value;
            var ocupacion = $("#ocupacion").val();
            var estado_civil = document.getElementById("estado_civil").value;
            var correo_electronico = $("#correo_electronico").val();
            var antecedentes_personales = document.getElementById("antecedentes_personales").value;
            var antecedentes_familiares = document.getElementById("antecedentes_familiares").value;
            $.ajax({
                url: 'editar_paciente_ajax.php',
                type: 'post',
                data: {
                    id_paciente: id_paciente,
                    numero_identidad: numero_identidad,
                    nombres: nombres,
                    apellidos: apellidos,
                    fecha_nacimiento: fecha_nacimiento,
                    genero: genero,
                    telefono_fijo: telefono_fijo,
                    telefono_movil: telefono_movil,
                    direccion: direccion,
                    raza: raza,
                    ocupacion: ocupacion,
                    estado_civil: estado_civil,
                    correo_electronico: correo_electronico,
                    antecedentes_personales: antecedentes_personales,
                    antecedentes_familiares: antecedentes_familiares,
                },
                success: function(response) {
                    $("#edicion_paciente").html(response);
                }
            });
        }

        function crear_consulta() {
            var id_paciente = $("#id_paciente").val();
            var motivo_consulta = document.getElementById("motivo_consulta").value;
            var precio = $("#precio").val();
            var descripcion_precio = document.getElementById("descripcion_precio").value;
            var examen_fisico = document.getElementById("examen_fisico").value;
            var diagnostico = document.getElementById("diagnostico").value;
            var observaciones = document.getElementById("observaciones").value;
            var tratamiento = $("#tratamiento").val();
            var certificado = $("#certificado").val();
            var id_cita = $("#id_cita").val();
            $.ajax({
                url: 'crear_consulta.php',
                type: 'post',
                data: {
                    id_paciente: id_paciente,
                    motivo_consulta: motivo_consulta,
                    examen_fisico: examen_fisico,
                    diagnostico: diagnostico,
                    tratamiento: tratamiento,
                    id_cita: id_cita,
                    certificado: certificado,
                    observaciones: observaciones,
                    precio: precio,
                    descripcion_precio: descripcion_precio
                },
                success: function(response) {
                    $("#crear_consulta").html(response);
                }
            });
        }
    </script>
</body>