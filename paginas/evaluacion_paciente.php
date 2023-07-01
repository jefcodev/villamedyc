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
        <div style="padding: 1% 2% 1% 2%; border: 2px solid rgba(0, 0, 0, 0.2); border-radius: 8px; box-shadow: 0 0 15px 10px rgba(0, 0, 0, 0.2)">
            <b style="font-size: 18px">Datos de consulta</b><br>
            <div class="row" id="edicion_paciente">
                <div class="col-md-4">
                    <div class="form-group">
                        <b>Historia Clinica:</b>
                        <p><?php echo $rowscita['numero_historia'] ?></p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <b>Número Identidad:</b>
                        <div><?php echo $rowscita['numero_identidad'] ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <b>Nombres:</b>
                        <div><?php echo $rowscita['nombres_paciente'] ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <b>Paquete:</b>
                        <div><?php echo $rowscita['titulo_paquete'] ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <b>Numero de Sesiones:</b>
                        <div id="numero_sesiones"><?php echo $rowscita['numero_sesiones'] ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <b>Estado:</b>
                        <div id="estado"><?php echo $rowscita['estado_atencion'] ?></div>
                    </div>
                </div>
            </div>
        </div><br>
        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8" id="crear_consulta">
            Evaluación
            <input type="button" class="btn btn-primary" value="Evaluar" data-toggle='modal' data-target='#exampleModal'>
            <input type="button" class="btn btn-primary" value="Ver Evaluación" data-toggle='modal' data-target='#resumenModal' id="ver_evaluacion">
        </div><br>

        <div id="procedimientos">

        </div>

        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8" id="nueva_sesion">
            Nueva Sesión
            <input type="button" class="btn btn-primary" value="Agregar" data-toggle='modal' data-target='#procedimientoModal' data-id="" id="nuevo_procedimiento">
        </div><br>
        <div class="d-flex justify-content-center">
            <input type="button" class="btn btn-primary" value="Finalizar" id="finalizar">
        </div>

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
                                <textarea class="form-control" title="Antecedentes del diagnóstico" placeholder="Antecedentes del diagnóstico" id="antecedentes_diagnostico" name="antecedentes_diagnostico"></textarea>
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
                                    <option value="2">No</option>
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
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="electroestimulacion">
                                        <label for="electroestimulacion">Electroestimulación</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="ultrasonido">
                                        <label for="ultrasonido">Ultrasonido</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="magnetoterapia">
                                        <label for="magnetoterapia">Magnetoterapia</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="laserterapia">
                                        <label for="laserterapia">Laserterapia</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="termoterapia">
                                        <label for="termoterapia">Termoterapia</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="masoterapia">
                                        <label for="masoterapia">Masoterapia</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="crioterapia">
                                        <label for="crioterapia">Crioterapia</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="malibre">
                                        <label for="malibre">Movilidad Activa Libre</label>
                                    </div>
                                </label>
                            </div>

                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="maasistida">
                                        <label for="maasistida">Movilidad Activa Asistida</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="fmuscular">
                                        <label for="fmuscular">Fortalecimiento Muscular</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="propiocepcion">
                                        <label for="propiocepcion">Propiocepción</label>
                                    </div>
                                </label>
                            </div>
                            <div class='col-md-6'>
                                <label class='card'>
                                    <div class='card-body'>
                                        <input type="checkbox" name="servicios" id="epunta">
                                        <label for="epunta">Eliminación de puntos gatillo</label>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" id="guardar_procedimiento">Guardar Procedimiento</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="modal fade" id="resumenModal" tabindex="-1" role="dialog" aria-labelledby="resumenModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="resumenModalLabel" data-id="" data-proceso="">Resumen de Atencion al Cliente</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="modal-body-resume">


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
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
        $("#finalizar").hide();

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
                    $("#antecedentes_diagnostico").val(data[0].antecendetes_diagnostico);
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
                    console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // console.log(data);
                    let template = "";
                    let cont = 1;
                    data.forEach(element => {
                        template += `<div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8" id="crear_consulta">
                                        Sesión No. ${cont}
                                        <input type="button" class="btn btn-primary" value="Editar" data-toggle='modal' data-target='#procedimientoModal' data-id='${element.consulta_fisio_detalle_id}' id='editar_procedimiento'>
                                        <input type="button" class="btn btn-primary" value="Ver Sesión" data-toggle='modal' data-target='#resumenModal' data-id='${element.consulta_fisio_detalle_id}' data-sesion='${cont}' id="ver_sesion">
                                    </div><br>`;
                        cont++;
                    });
                    // console.log(cont);
                    // $('#nuevo_procedimiento').attr("data-id", cont);
                    const numero_sesiones = Number($('#numero_sesiones').text());
                    const estado = $('#estado').text();

                    if (cont > numero_sesiones) {
                        $("#nueva_sesion").hide();
                        if (estado == "Por Atender") {
                            $("#finalizar").show();
                        }
                    }

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

                    const verSesion = document.querySelectorAll("#ver_sesion");
                    verSesion.forEach((card, i) => {
                        card.addEventListener('click', () => {
                            const id_sesion = card.getAttribute("data-id");
                            const sesion = card.getAttribute("data-sesion");
                            resumenSesion(id_sesion, sesion);
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
            const FD = new FormData();
            FD.append('action', "ver_servicios");
            FD.append('consulta_fisio_detalle_id', id_sesion);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // console.log(data);
                    data.forEach(element => {
                        let checkboxes = document.querySelector("input[id='electroestimulacion']");
                        checkboxes.checked = Number(element.electroestimulacion);
                        checkboxes = document.querySelector("input[id='ultrasonido']");
                        checkboxes.checked = Number(element.ultrasonido);
                        checkboxes = document.querySelector("input[id='magnetoterapia']");
                        checkboxes.checked = Number(element.magnetoterapia);
                        checkboxes = document.querySelector("input[id='laserterapia']");
                        checkboxes.checked = Number(element.laserterapia);
                        checkboxes = document.querySelector("input[id='termoterapia']");
                        checkboxes.checked = Number(element.termoterapia);
                        checkboxes = document.querySelector("input[id='masoterapia']");
                        checkboxes.checked = Number(element.masoterapia);
                        checkboxes = document.querySelector("input[id='crioterapia']");
                        checkboxes.checked = Number(element.crioterapia);
                        checkboxes = document.querySelector("input[id='malibre']");
                        checkboxes.checked = Number(element.malibre);
                        checkboxes = document.querySelector("input[id='maasistida']");
                        checkboxes.checked = Number(element.maasistida);
                        checkboxes = document.querySelector("input[id='fmuscular']");
                        checkboxes.checked = Number(element.fmuscular);
                        checkboxes = document.querySelector("input[id='propiocepcion']");
                        checkboxes.checked = Number(element.propiocepcion);
                        checkboxes = document.querySelector("input[id='epunta']");
                        checkboxes.checked = Number(element.epunta);
                    });
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        $('#nuevo_procedimiento').on('click', function() {
            // const id_sesion = $('#nuevo_procedimiento').attr("data-id");
            // $('#procedimientoModalLabel').attr("data-id", id_sesion);
            $('#procedimientoModalLabel').attr("data-proceso", "nuevo");
            limpiarCheckBoxs();
        });

        $('#guardar_procedimiento').on('click', function() {
            guardarProcedimiento();
        });

        function guardarProcedimiento() {
            const consulta_fisio_id = $('#id_consulta_fisio').attr("data-id");
            const consulta_fisio_detalle_id = $('#procedimientoModalLabel').attr("data-id");
            const proceso = $('#procedimientoModalLabel').attr("data-proceso");
            let action = "crear_procedimiento";
            if (proceso == "editar") {
                action = "actualizar_procedimiento";
            }

            var checkboxes = document.querySelectorAll('input[name="servicios"]:checked');
            if (checkboxes.length > 0) {
                const FD = new FormData();
                FD.append('action', action);
                FD.append('consulta_fisio_id', consulta_fisio_id);
                FD.append('consulta_fisio_detalle_id', consulta_fisio_detalle_id);
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
                        alert(decodificado);
                        location.reload();
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            } else {
                alert("Seleccione por lo menos un servicio");
            }
        }

        $('#guardar_evaluacion').on('click', function() {
            const id = $('#id_consulta_fisio').attr("data-id");
            actualizarEvaluacion(id);
        });

        $("#finalizar").on('click', function() {
            const id = $('#id_consulta_fisio').attr("data-id");
            actualizarEstado(id);
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
            FD.append('antecedentes_diagnostico', $("#antecedentes_diagnostico").val());
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
                    location.reload();
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        function actualizarEstado(consulta_fisio_id) {
            const FD = new FormData();
            FD.append('action', "actualizar_estado");
            FD.append('consulta_fisio_id', consulta_fisio_id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    console.log(decodificado);
                    alert(decodificado);
                    location.reload();
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        $('#ver_evaluacion').on('click', function() {
            const consulta_fisio_id = $('#id_consulta_fisio').attr("data-id");
            resumenEvaluacion(consulta_fisio_id);
        });

        function resumenEvaluacion(consulta_fisio_id) {
            const FD = new FormData();
            FD.append('action', "ver_evaluacion");
            FD.append('consulta_fisio_id', consulta_fisio_id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    console.log(data);
                    let sedestacion = "";
                    let esfuerzo = "";
                    let irradiacion = "";
                    let limitacion = "";

                    if (data[0].sedestacion_prolongada == "1") {
                        sedestacion = "Si";
                    }
                    if (data[0].sedestacion_prolongada == "2") {
                        sedestacion = "No";
                    }

                    if (data[0].esfuerzo_fisico == "1") {
                        esfuerzo = "Bajo";
                    }
                    if (data[0].esfuerzo_fisico == "2") {
                        esfuerzo = "Medio";
                    }
                    if (data[0].esfuerzo_fisico == "3") {
                        esfuerzo = "Alto";
                    }

                    if (data[0].irradiacion == "1") {
                        irradiacion = "Si";
                    }
                    if (data[0].irradiacion == "2") {
                        irradiacion = "No";
                    }

                    if (data[0].limitacion_movilidad == "1") {
                        limitacion = "Crujidos";
                    }
                    if (data[0].limitacion_movilidad == "2") {
                        limitacion = "Topes Articulares";
                    }
                    if (data[0].limitacion_movilidad == "3") {
                        limitacion = "Músculo Tendioso";
                    }

                    let template = `<div class="row m-1 d-flex justify-content-center">
                                        <div class='col-md-12 m-4' style='border-bottom: 1px solid #444;'><b>Factores Ocupacionales</b></div><br><br>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                                <b>Profesión</b>
                                                <p>${data[0].profesion}</p>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Tipo Trabajo</b>
                                                <p>${data[0].tipo_trabajo}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Sedestación Prolongada</b>
                                                <p>${sedestacion}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Esfuerzo Fisico</b>
                                                <p>${esfuerzo}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Hábitos</b>
                                                <p>${data[0].habitos}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-12 m-4' style='border-bottom: 1px solid #444;' ><b>Diagnóstico</b></div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Antecedentes del Diagnóstico</b>
                                                <p>${data[0].antecedentes_diagnostico}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Tratamientos Anteriores</b>
                                                <p>${data[0].tratamientos_anteriores}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-12 m-4' style='border-bottom: 1px solid #444;' ><b>Palpación y Dolor</b></div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Contracturas</b>
                                                <p>${data[0].contracturas}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Irradiación</b>
                                                <p>${irradiacion}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Hacia Donde?</b>
                                                <p>${data[0].hacia_donde}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Intensidad</b>
                                                <p>${data[0].intensidad}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Sensaciones</b>
                                                <p>${data[0].sensaciones}</p>
                                            </div>
                                        </div>
                                        <div class='col-md-12 m-4' style='border-bottom: 1px solid #444;' ><b>Limitación de la Movilidad</b></div>
                                        <div class='col-md-5 m-1' style='border: 1px solid #c1c1c1;'>
                                            <div class='form-group'>
                                                <b>Limitacion Movilidad</b>
                                                <p>${limitacion}</p>
                                            </div>
                                        </div>
                                    </div>`;
                    $('#modal-body-resume').html(template);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        function resumenSesion(consulta_fisio_detalle_id, sesion) {
            const FD = new FormData();
            FD.append('action', "ver_sesion");
            FD.append('consulta_fisio_detalle_id', consulta_fisio_detalle_id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    console.log(data);
                    let lista = "";
                    if (data[0].electroestimulacion == "1") {
                        lista += "<li>Electroestimulación</li>";
                    }
                    if (data[0].ultrasonido == "1") {
                        lista += "<li>Ultrasonido</li>";
                    }
                    if (data[0].magnetoterapia == "1") {
                        lista += "<li>Magnetoterapia</li>";
                    }
                    if (data[0].laserterapia == "1") {
                        lista += "<li>Laserterapia</li>";
                    }
                    if (data[0].termoterapia == "1") {
                        lista += "<li>Termoterapia</li>";
                    }
                    if (data[0].masoterapia == "1") {
                        lista += "<li>Masoterapia</li>";
                    }
                    if (data[0].crioterapia == "1") {
                        lista += "<li>Crioterapia</li>";
                    }
                    if (data[0].malibre == "1") {
                        lista += "<li>Movilidad Activa Libre</li>";
                    }
                    if (data[0].maasistida == "1") {
                        lista += "<li>Movilidad Activa Asistida</li>";
                    }
                    if (data[0].fmuscular == "1") {
                        lista += "<li>Fortalecimiento Muscular</li>";
                    }
                    if (data[0].propiocepcion == "1") {
                        lista += "<li>Propiocepción</li>";
                    }
                    if (data[0].epunta == "1") {
                        lista += "<li>Eliminación de puntos gatillo</li>";
                    }

                    let template = `<div class="row m-1" style="border: 1px solid #c1c1c1; ">
                                        <div class='col-md-4' style="background-color: #D8D8D8">
                                            <label class='form-group'>
                                                <b>Número de Sesión</b>
                                                <p>${sesion}</p>
                                            </label>
                                        </div>
                                        <div class='col-md-4' style="background-color: #D8D8D8">
                                            <label class='form-group'>
                                                <b>Atendido Por</b>
                                                <p>${data[0].nombres}</p>
                                            </label>
                                        </div>
                                        <div class='col-md-4' style="background-color: #D8D8D8">
                                            <label class='form-group'>
                                                <b>Fecha de Atención</b>
                                                <p>${data[0].fecha}</p>
                                            </label>
                                        </div>
                                        <div class='col-md-12'>
                                            <b>Procedimientos realizados</b>
                                        </div>
                                        <div class='col-md-3'>
                                            <div class='form-group'>
                                                <ul>
                                                    ${lista}
                                                </ul>
                                            </div>
                                        </div>
                                    </div>`;
                    $('#modal-body-resume').html(template);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }
    </script>
</body>