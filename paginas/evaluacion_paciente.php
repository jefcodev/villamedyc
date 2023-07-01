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
                        <textarea class="form-control" title="Profesión" placeholder="Profesión" id="profesion" name="profesion"></textarea>
                        <textarea class="form-control" title="Tipo de trabajo" placeholder="Tipo de trabajo" id="tipo_trabajo" name="tipo_trabajo"></textarea>
                        <select class="form-control" id="sedestacion_prolongada" name="sedestacion_prolongada" title="Sedestación Prolongada">
                            <option value="3">Sedestacion Prolongada</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select class="form-control" id="esfuerzo_fisico" name="esfuerzo_fisico" title="Esfuerzo Físico">
                            <option value="0">Esfuerzo Fisico</option>
                            <option value="1">Bajo</option>
                            <option value="2">Medio</option>
                            <option value="3">Alto</option>
                        </select>
                        <textarea class="form-control" title="Hábitos/Otras actividades" placeholder="Hábitos/Otras actividades" id="habitos" name="habitos"></textarea>
                    </div>
                </div>


                <b style="font-size: 18px">Diagnóstico</b><br><br>

                <div class="row">
                    <div class="col-md-6">
                        <textarea class="form-control" title="Antecedentes del diagnóstico" placeholder="Antecedentes del diagnóstico" id="antecendentes_diagnostico" name="antecendentes_diagnostico"></textarea>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" title="Tratamientos anteriores" placeholder="Tratamientos anteriores" id="tratamientos_anteriores" name="tratamientos_anteriores"></textarea>
                    </div>
                </div>

                <b style="font-size: 18px">Palpación y Dolor</b><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <textarea class="form-control" title="Contracturas" placeholder="Contracturas" id="contracturas" name="contracturas"></textarea>
                        <select class="form-control" id="irradiacion" name="irradiacion" title="Irradiación">
                            <option value="3">Irradiación</option>
                            <option value="1">Si</option>
                            <option value="0">No</option>
                        </select>
                        <textarea class="form-control" title="Hacia donde?" placeholder="Hacia donde?" id="hacia_donde" name="hacia_donde"></textarea>
                    </div>
                    <div class="col-md-6">
                        <textarea class="form-control" title="Intensidad" placeholder="Intensidad" id="intensidad" name="intensidad"></textarea>
                        <textarea class="form-control" title="Sensaciones" placeholder="Sensaciones" id="sensaciones" name="sensaciones"></textarea>
                    </div>
                </div>

                <b style="font-size: 18px">Limitación de la Movilidad</b><br><br>
                <div class="row">
                    <div class="col-md-6">
                        <select class="form-control" id="limitacion_movilidad" name="limitacion_movilidad" title="Limitación de la Movilidad">
                            <option value="1">Crujidos</option>
                            <optgroup label="Osteoarticular">
                                <option value="2">Topes articulares</option>
                                <option value="3">Musculo Tendinosos</option>
                            </optgroup>
                        </select>
                    </div>
                </div>

                <b style="font-size: 18px">Procedimiento realizado</b><br><br>
                <div class="row">
                    <?php
                    $sql = "SELECT * FROM servicios";
                    $result = $mysqli->query($sql);
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $nombre = $row['titulo_servicio'];
                            $id_servicio = $row['id_servicio'];
                            echo "<div class='col-md-3'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <input type='checkbox' name='$nombre' id='$id_servicio'>
                                            <label for='$nombre'>$nombre</label>
                                        </div>
                                    </div>
                                </div>";
                        }
                    } else {
                        echo "<div class='col-md-3'>
                                    <div class='card'>
                                        <div class='card-body'>
                                            <label >No hay servicios disponibles.</label>
                                        </div>
                                    </div>
                                </div>";
                    }
                    ?>
                </div>
                <!-- <div class="row">
                    <div class="col-md-12">
                        <input type="checkbox" name="electroestimulacion" id="electroestimulacion">
                        <label for="electroestimulacion">Electroestimulación</label>
                        <input type="checkbox" name="ultrasonido" id="ultrasonido">
                        <label for="ultrasonido">Ultrasonido</label>
                        <input type="checkbox" name="magnetoterapia" id="magnetoterapia">
                        <label for="magnetoterapia">Magnetoterapia</label>
                        <input type="checkbox" name="laserterapia" id="laserterapia">
                        <label for="laserterapia">Laserterapia</label>
                    </div>
                    <div class="col-md-12">
                        <input type="checkbox" name="termoterapia" id="termoterapia">
                        <label for="termoterapia">Termoterapia</label>
                        <input type="checkbox" name="masoterapia" id="masoterapia">
                        <label for="masoterapia">Masoterapia</label>
                        <input type="checkbox" name="crioterapia" id="crioterapia">
                        <label for="crioterapia">Crioterapia</label>
                        <input type="checkbox" name="malibre" id="malibre">
                        <label for="malibre">Movilidad Activa Libre</label>
                    </div>
                    <div class="col-md-12">
                        <input type="checkbox" name="maasistida" id="maasistida">
                        <label for="maasistida">Movilidad Activa Asistida</label>
                        <input type="checkbox" name="fmuscular" id="fmuscular">
                        <label for="fmuscular">Fortalecimiento Muscular</label>
                        <input type="checkbox" name="propiocepcion" id="propiocepcion">
                        <label for="propiocepcion">Propiocepción</label>
                        <input type="checkbox" name="epunta" id="epunta">
                        <label for="epunta">Eliminación de Punta</label>
                    </div>
                </div> -->

                <div class="row">
                    <div class="col-md-6">
                        <input type="hidden" id="id_cita" name="id_cita" value="<?php echo $id_cita; ?>" />
                    </div>
                    <div class="col-md-6">
                        <input class="btn btn-primary float-left" type="button" name="guardar_evaluacion" id="guardar_evaluacion" value="Guardar" />
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
        $('#guardar_evaluacion').on('click', function() {
            const id = $('#id_cita').val();
            actualizarEvaluacion(id);
        });


        function actualizarEvaluacion(consulta_fisio_id) {
            const FD = new FormData();
            FD.append('action', "actualizar_evaluacion");
            FD.append('consulta_fisio_id', consulta_fisio_id);
            FD.append('profesion', $("#profesion").val());
            FD.append('tipo_trabajo', $("#tipo_trabajo").val());
            FD.append('sedestacion_prolongada', $("#sedestacion_prolongada").val());
            FD.append('esfuerzo_fisico', $("#esfuerzo_fisico").val());
            FD.append('habitos', $("#habitos").val());
            FD.append('antecendentes_diagnostico', $("#antecendentes_diagnostico").val());
            FD.append('tratamientos_anteriores', $("#tratamientos_anteriores").val());
            FD.append('contracturas', $("#contracturas").val());
            FD.append('irradiacion', $("#irradiacion").val());
            FD.append('hacia_donde', $("#hacia_donde").val());
            FD.append('intensidad', $("#intensidad").val());
            FD.append('sensaciones', $("#sensaciones").val());
            FD.append('limitacion_movilidad', $("#limitacion_movilidad").val());

            FD.append('electroestimulacion', $("#electroestimulacion").is(":checked"));
            FD.append('ultrasonido', $("#ultrasonido").is(":checked"));
            FD.append('magnetoterapia', $("#magnetoterapia").is(":checked"));
            FD.append('laserterapia', $("#laserterapia").is(":checked"));
            FD.append('termoterapia', $("#termoterapia").is(":checked"));
            FD.append('masoterapia', $("#masoterapia").is(":checked"));
            FD.append('crioterapia', $("#crioterapia").is(":checked"));
            FD.append('malibre', $("#malibre").is(":checked"));
            FD.append('maasistida', $("maasistida").is(":checked"));
            FD.append('fmuscular', $("#fmuscular").is(":checked"));
            FD.append('propiocepcion', $("#propiocepcion").is(":checked"));
            FD.append('epunta', $("#epunta").is(":checked"));

            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    console.log(decodificado);
                    // var alertElement = document.getElementById('alert-success');
                    // alertElement.classList.remove('d-none');
                    // setTimeout(function() {
                    //     alertElement.classList.add('d-none');
                    // }, 3000);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }
    </script>
</body>