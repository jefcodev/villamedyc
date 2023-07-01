<?php
include 'header.php';
$id_cita = $_GET['consulta_fisio_id'];
?>
<link rel="stylesheet" href="../css/bootstrap_select.min.css">

<body>
    <section class="cuerpo">
        <h1 id="id_consulta_fisio" data-id="">Atención al paciente</h1><br>
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
        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8" id="crear_consulta">
            Evaluación
            <input type="button" class="btn btn-primary" value="Evaluar" data-toggle='modal' data-target='#exampleModal'>
        </div><br>

        <div id="procedimientos">

        </div>

        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8" id="crear_consulta">
            Nuevo Procedimiento
            <input type="button" class="btn btn-primary" value="Agregar" data-toggle='modal' data-target='#procedimientoModal' data-id="" id="nuevo_procedimiento">
        </div><br>

        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Evaluación</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">
                        <b style="font-size: 18px">Factores Ocupacionales</b><br><br>
                        <div class="row">
                            <div class="col-md-6">
                                <textarea class="form-control" title="Profesión" placeholder="Profesión" id="profesion" name="profesion"></textarea>
                                <textarea class="form-control" title="Tipo de trabajo" placeholder="Tipo de trabajo" id="tipo_trabajo" name="tipo_trabajo"></textarea>
                                <select class="form-control" id="sedestacion_prolongada" name="sedestacion_prolongada" title="Sedestación Prolongada">
                                    <option value="0">Sedestacion Prolongada</option>
                                    <option value="1">Si</option>
                                    <option value="2">No</option>
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
                                    <option value="0">Irradiación</option>
                                    <option value="1">Si</option>
                                    <option value="3">No</option>
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
                                    <option value="0">Seleccione</option>
                                    <option value="1">Crujidos</option>
                                    <optgroup label="Osteoarticular">
                                        <option value="2">Topes articulares</option>
                                        <option value="3">Musculo Tendinosos</option>
                                    </optgroup>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" name="guardar_evaluacion" id="guardar_evaluacion">Guardar Datos</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="procedimientoModal" tabindex="-1" role="dialog" aria-labelledby="procedimientoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="procedimientoModalLabel" data-id="" data-proceso="">Procedimiento Realizado</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">
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
                                            <input type='checkbox' name='servicios' id='$id_servicio' data-name='$nombre'>
                                            <label>$nombre</label>
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
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="guardar_procedimiento">Guardar Procedimiento</button>
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
        cargarDatos();

        function cargarDatos() {
            // Obtener la URL actual
            var url = new URL(window.location.href);
            // Obtener los parámetros de la URL
            var params = new URLSearchParams(url.search);
            // Obtener el valor de un parámetro específico
            var id = params.get('consulta_fisio_id');

            const FD = new FormData();
            FD.append('action', "ver_cita");
            FD.append('consulta_fisio_id', id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // console.log(data);
                    $("#profesion").val(data[0].profesion);
                    $("#tipo_trabajo").val(data[0].tipo_trabajo);
                    $("#sedestacion_prolongada").val(data[0].sedestacion_prolongada);
                    $("#esfuerzo_fisico").val(data[0].esfuerzo_fisico);
                    $("#habitos").val(data[0].habitos);
                    $("#antecendentes_diagnostico").val(data[0].antecendentes_diagnostico);
                    $("#tratamientos_anteriores").val(data[0].tratamientos_anteriores);
                    $("#contracturas").val(data[0].contracturas);
                    $("#irradiacion").val(data[0].irradiacion);
                    $("#hacia_donde").val(data[0].hacia_donde);
                    $("#intensidad").val(data[0].intensidad);
                    $("#sensaciones").val(data[0].sensaciones);
                    $("#limitacion_movilidad").val(data[0].limitacion_movilidad);

                    $('#id_consulta_fisio').attr("data-id", data[0].consulta_fisio_id);
                    cargarProcedimientos(data[0].consulta_fisio_id);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        function cargarProcedimientos(id) {
            const FD = new FormData();
            FD.append('action', "ver_procedimientos");
            FD.append('consulta_fisio_id', id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    console.log(data);
                    let template = "";
                    let cont = 1;
                    data.forEach(element => {
                        template += `<div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8" id="crear_consulta">
                                        Procedimiento ${element.numero_sesion}
                                        <input type="button" class="btn btn-primary" value="Editar" data-toggle='modal' data-target='#procedimientoModal' data-id='${element.numero_sesion}' id='editar_procedimiento'>
                                    </div><br>`;
                        cont++;
                    });
                    // console.log(cont);
                    $('#nuevo_procedimiento').attr("data-id", cont);
                    $('#procedimientos').html(template);
                    const editarProcedimientos = document.querySelectorAll("#editar_procedimiento");
                    editarProcedimientos.forEach((card, i) => {
                        card.addEventListener('click', () => {
                            const id_sesion = card.getAttribute("data-id");
                            $('#procedimientoModalLabel').attr("data-id", id_sesion);
                            $('#procedimientoModalLabel').attr("data-proceso", "editar");
                            limpiarCheckBoxs();
                            cargarServiciosProcedimiento(id_sesion);
                        });
                    });
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        function limpiarCheckBoxs() {
            var checkboxes = document.querySelectorAll('input[name="servicios"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = false;
            });
        }

        function cargarServiciosProcedimiento(id_sesion) {
            const consulta_fisio_id = $('#id_consulta_fisio').attr("data-id");
            const FD = new FormData();
            FD.append('action', "ver_servicios");
            FD.append('consulta_fisio_id', consulta_fisio_id);
            FD.append('numero_sesion', id_sesion);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // console.log(data);
                    data.forEach(element => {
                        let template = "input[id='" + element.id_servicio + "']"
                        var checkboxes = document.querySelector(template);
                        checkboxes.checked = true;
                    });
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        $('#nuevo_procedimiento').on('click', function() {
            const id_sesion = $('#nuevo_procedimiento').attr("data-id");
            $('#procedimientoModalLabel').attr("data-id", id_sesion);
            $('#procedimientoModalLabel').attr("data-proceso", "nuevo");
            limpiarCheckBoxs();
        });

        $('#guardar_procedimiento').on('click', function() {
            guardarProcedimiento();
        });

        function guardarProcedimiento() {
            const consulta_fisio_id = $('#id_consulta_fisio').attr("data-id");
            const id_sesion = $('#procedimientoModalLabel').attr("data-id");
            const proceso = $('#procedimientoModalLabel').attr("data-proceso");
            let action = "crear_procedimiento";
            if (proceso == "editar") {
                action = "actualizar_procedimiento";
            }

            var checkboxes = document.querySelectorAll('input[name="servicios"]:checked');
            var servicios = [];

            checkboxes.forEach(function(checkbox) {
                const servicio = {
                    'id_servicio': checkbox.getAttribute("id"),
                    'titulo_servicio': checkbox.getAttribute("data-name")
                }
                servicios.push(servicio);
            });

            if (servicios.length > 0) {
                const FD = new FormData();
                FD.append('action', action);
                FD.append('consulta_fisio_id', consulta_fisio_id);
                FD.append('numero_sesion', id_sesion);
                FD.append('servicios', JSON.stringify(servicios));

                fetch("ventas_ajax.php", {
                        method: 'POST',
                        body: FD
                    }).then(respuesta => respuesta.text())
                    .then(decodificado => {
                        console.log(decodificado);
                        alert(decodificado);
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            } else {
                alert("Seleccione por lo menos un servicio");
            }
        }

        $('#guardar_evaluacion').on('click', function() {
            const id = $('#id_consulta_fisio').attr("data-id");;
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

            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    console.log(decodificado);
                    alert(decodificado);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }
    </script>
</body>