<?php
include 'header.php';
$pagina = PAGINAS::LISTA_USUARIOS;
if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
    header("location:./inicio.php?status=AD");
}
?>

<body>
    <style>
        .floating-alert {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>
    <section class="cuerpo">
        <div class="d-flex justify-content-center">
            <div class="alert alert-primary d-none floating-alert" id="alert-primary" role="alert">
                Este es un mensaje de información.
            </div>

            <div class="alert alert-success d-none floating-alert" id="alert-success" role="alert">
                Cita Asignada/Actualizada
                <button onclick="location.reload()">Actualizar</button>
            </div>

            <div class="alert alert-danger d-none floating-alert" id="alert-danger" role="alert">
                Este es un mensaje de error.
            </div>
        </div>
        <h1>Listado de Ventas</h1>
        <div class="row">
            <div class="col-md-12">
                <a href="venta.php" class="btn btn-success float-right"> Crear nueva venta</a>
                <br><br>
                <table class="table table-bordered table-hover" id="indexusuarios">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Numero Historia</th>
                            <th>Cédula</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Paquete</th>
                            <th>Nº Sesiones</th>
                            <th>Total</th>
                            <th style="width: 125px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_ventas = "SELECT cf.consulta_fisio_id, cf.numero_historia, cf.estado_atencion, pc.titulo_paquete, pc.numero_sesiones, pc.total, p.numero_identidad, CONCAT(p.nombres, ' ', p.apellidos) as nombres 
                                            FROM consultas_fisioterapeuta cf, pacientes p, paquete_cabecera pc
                                            WHERE cf.paciente_id = p.id AND pc.paquete_id = cf.paquete_id";
                        $result = $mysqli->query($sql_ventas);

                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr id='" . $row['consulta_fisio_id'] . "' >";
                            echo "<td>" . $row['numero_historia'] . "</td>";
                            echo "<td>" . $row['numero_identidad'] . "</td>";
                            echo "<td>" . $row['nombres'] . "</td>";
                            echo "<td>" . $row['estado_atencion'] . "</td>";
                            echo "<td>" . $row['titulo_paquete'] . "</td>";
                            echo "<td>" . $row['numero_sesiones'] . "</td>";
                            echo "<td>" . $row['total'] . "</td>";
                            echo "<td>
                                    <a class='btn btn-primary btn-sm ml-1' href='historia_clinica.php?idusuario=" . $row['id'] . "' data-toggle='modal' data-target='#exampleModal' id='ver_resumen'><i style='font-size:18px' class='fas fa-eye'></i></a>
                                    <!--<a class='btn btn-success btn-sm ml-1' data-toggle='modal' data-target='#editModal' id='crear_cita' ><i class='fas fa-edit table-icon'></i></a> -->
                                </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Detalles</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="modal-body">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        <!-- <button type="button" class="btn btn-primary">Crear Paquete</button> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Agendar Cita</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 id="paquete_id" data-id="">Detalles</h5>
                        <select class="form-control" id="doctor" name="doctor" required>
                            <option value="" selected="" hidden="">Seleccione Fisioterapeuta</option>
                            <?php
                            $sql_traer_doctor = "SELECT * FROM usuarios WHERE rol = 'fis'";
                            $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                            while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                                echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . ' ' . $row['apellidos'] . "</option>";
                            }
                            ?>
                        </select>
                        <input class="form-control" type="text" autocomplete="off" placeholder="Fecha y hora de la cita" id="fecha_cita" name="fecha_cita" required /><br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="actualizar_paquete">Crear/Actualizar Cita</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <br>
    <?php
    include 'footer.php';
    ?>
    <script src="../js/jquerysearch.js"></script>

    <script language="javascript" src="../js/jquery.datetimepicker.full.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#indexusuarios').DataTable({
                language: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar:",
                    sUrl: "",
                    sInfoThousands: ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "Siguiente",
                        sPrevious: "Anterior"
                    },
                    oAria: {
                        sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });

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

        var servicios = [{
                'id': 1,
                'name': 'Displacia de cadera',
                'cost': 10
            },
            {
                'id': 2,
                'name': 'Pie plano',
                'cost': 20
            },
            {
                'id': 3,
                'name': 'Artrosis de rodilla',
                'cost': 30
            },
            {
                'id': 4,
                'name': 'Hernias discales',
                'cost': 20
            },
            {
                'id': 5,
                'name': 'Tratamientos con células madre',
                'cost': 30
            },
            {
                'id': 6,
                'name': 'Tratamientos para artrosis, enfermedades degenerativas, osteomusculares y osteoarticulares',
                'cost': 20
            },
            {
                'id': 7,
                'name': 'Reemplazo Articulares',
                'cost': 10
            },
            {
                'id': 8,
                'name': 'Tratamientos para el adulto',
                'cost': 40
            },
            {
                'id': 9,
                'name': 'Esguinces',
                'cost': 50
            },
            {
                'id': 10,
                'name': 'Fracturas',
                'cost': 30
            },
            {
                'id': 11,
                'name': 'Artrosis',
                'cost': 20
            },
            {
                'id': 12,
                'name': 'Dolores crónicos',
                'cost': 30
            },
            {
                'id': 13,
                'name': 'Fortalecimiento pulmonar',
                'cost': 20
            },
            {
                'id': 14,
                'name': 'Enfermedades crónicas respiratorias',
                'cost': 30
            },
            {
                'id': 15,
                'name': 'Pacientes post-COVID',
                'cost': 40
            },
            {
                'id': 16,
                'name': 'Mejora de calidad del sueño',
                'cost': 20
            },
            {
                'id': 17,
                'name': 'Ayuda a la concentración y memoria',
                'cost': 10
            },
            {
                'id': 18,
                'name': 'Elimina contracturas musculares',
                'cost': 20
            },
            {
                'id': 19,
                'name': 'Incontinecia urinaria',
                'cost': 20
            },
            {
                'id': 20,
                'name': 'Prolapsos',
                'cost': 30
            },
            {
                'id': 21,
                'name': 'Dolores en relaciones sexuales',
                'cost': 40
            },
            {
                'id': 22,
                'name': 'Eyaculación precoz',
                'cost': 50
            },
            {
                'id': 23,
                'name': 'Problemas de erección',
                'cost': 30
            },
            {
                'id': 24,
                'name': 'Programa de hidroterapia personalizado',
                'cost': 20
            },
            {
                'id': 25,
                'name': 'Elimina contracturas musculares',
                'cost': 20
            }
        ];

        /**
         * VER PAQUETE
         */

        const verResumen = document.querySelectorAll("#ver_resumen");
        verResumen.forEach((card, i) => {
            card.addEventListener('click', () => {
                var id = card.parentElement.parentElement.id;
                mostrar(id);
            });
        });

        function mostrar(id) {
            const FD = new FormData();
            FD.append('action', "ver_venta");
            FD.append("consulta_fisio_id", id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    console.log(data);
                    let tipo = "";
                    if (data[0].tipo_paquete === '1') {
                        tipo = "Básico";
                    }
                    if (data[0].tipo_paquete === '2') {
                        tipo = "Plus";
                    }
                    if (data[0].tipo_paquete === '3') {
                        tipo = "Premium";
                    }
                    if (data[0].tipo_paquete === '4') {
                        tipo = "Empresas";
                    }
                    if (data[0].tipo_paquete === '5') {
                        tipo = "Convenio";
                    }
                    let doc = "No Asignado";
                    let fec = "No Asignada";
                    if (data[0].usuario_id !== "2") {
                        doc = data[0].nombres_doc;
                    }
                    if (data[0].fecha !== "0000-00-00 00:00:00") {
                        fec = data[0].fecha;
                    }
                    let template = `<div>${data[0].numero_historia}</div>
                                    <div>Paciente: ${data[0].nombres}</div>
                                    <div>Doctor: ${doc}</div>
                                    <div>Fecha Cita: ${fec}</div>
                                    <div>Titulo Paquete: ${data[0].titulo_paquete}</div>
                                    <div>Tipo de Paquete: ${tipo}</div>
                                    <div>Nº de Sesiones: ${data[0].numero_sesiones}</div>
                                    <table class="table table-bordered table-hover">
                                        <thead class="tabla_cabecera">
                                            <tr>
                                                <th>Nombre</th>
                                                <th>Tipo</th>
                                                <th>Valor</th>
                                                <th>Nº Sesiones/Cantidad</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table_producto">
                                    `;
                    data.forEach(paquete => {
                        template += `<tr>
                                        <td> ${paquete.nombre} </td>
                                        <td> ${paquete.tipo} </td>
                                        <td> ${paquete.costo} </td>
                                        <td> ${paquete.cantidad} </td>
                                        <td> ${paquete.total} </td>
                                    </tr>`;
                    });

                    template += `   </tbody>
                                </table>
                                <div>Total: ${data[0].total_paquete}</div>`;
                    $('#modal-body').html(template);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        /**
         * CREAR CITA
         */

        const editar = document.querySelectorAll("#crear_cita");
        editar.forEach((card, i) => {
            card.addEventListener('click', () => {
                var id = card.parentElement.parentElement.id;
                $('#paquete_id').attr("data-id", id);
                mostrarCita(id);
            });
        });

        function mostrarCita(id) {
            const FD = new FormData();
            FD.append('action', "ver_fecha_cita");
            FD.append("consulta_fisio_id", id);
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // if (data[0].estado_atencion !== 'Por Asignar Cita') {
                        $('#doctor').val(Number(data[0].usuario_id));
                        $("#fecha_cita").val(data[0].fecha);
                    // }
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        $('#actualizar_paquete').on('click', function() {
            const id = Number($('#paquete_id').attr('data-id'));
            crearCita(id);
        });

        function crearCita(consulta_cita_id) {
            // if (validar()) {
            const FD = new FormData();
            FD.append('action', "crear_cita");
            FD.append('consulta_cita_id', consulta_cita_id)
            FD.append('doctor_id', $('#doctor').val());
            FD.append('fecha', $("#fecha_cita").val());
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    console.log(decodificado);
                    var alertElement = document.getElementById('alert-success');
                    alertElement.classList.remove('d-none');
                    setTimeout(function() {
                        alertElement.classList.add('d-none');
                    }, 5000);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
            // }
        }

        function eliminar(id, type) {
            var index = listaPaquete.findIndex(function(o) {
                return (o.id === Number(id) && o.type === type);
            });
            if (index !== -1) listaPaquete.splice(index, 1);
            mostrarTabla();
            totalizarPago();
        }

        function totalizarPago() {
            var total = listaPaquete.reduce((acumulador, servicio) => acumulador + servicio.total, 0);
            $('#total_pago').html(total);
        }

        /**
         * ELIMINAR PAQUETE
         */

        const eliminarP = document.querySelectorAll("#eliminar_paquete");
        eliminarP.forEach((card, i) => {
            card.addEventListener('click', () => {
                var id = card.parentElement.parentElement.id;
                eliminarPaquete(id);
            });
        });

        function eliminarPaquete(id) {
            const FD = new FormData();
            FD.append('action', "eliminar_paquete");
            FD.append("paquete_id", id);
            fetch("paquete_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    console.log(decodificado);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }
    </script>
</body>