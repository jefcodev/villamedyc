<!DOCTYPE html>
<html><?php
        include 'header.php';
        $pagina = PAGINAS::LISTA_USUARIOS;
        if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
            header("location:./inicio.php?status=AD");
        }
        ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP MySQL Select2 Example</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <title>Clínica</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>


</head>

<body>
    <section class="cuerpo">
        <h1>Listado de Paquetes</h1>
        <div class="row">
            <div class="col-md-12">
                <a href="crear_paquetes.php" class="btn btn-success float-right"> Crear nuevo paquete</a>
                <br><br>
                <table class="table table-bordered table-hover" id="indexusuarios">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Nombre Paquete</th>
                            <th>Tipo Paquete</th>
                            <th>Nº Sesiones</th>
                            <th>Total</th>
                            <th style="width: 125px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_usuarios = "SELECT * from paquete_cabecera";
                        $result_usuarios = $mysqli->query($sql_usuarios);

                        while ($row = mysqli_fetch_array($result_usuarios)) {
                            $type = "";
                            if ($row['tipo_paquete'] == 1) {
                                $type = "Básico";
                            }
                            if ($row['tipo_paquete'] == 2) {
                                $type = "Plus";
                            }
                            if ($row['tipo_paquete'] == 3) {
                                $type = "Premium";
                            }
                            if ($row['tipo_paquete'] == 4) {
                                $type = "Empresas";
                            }
                            if ($row['tipo_paquete'] == 5) {
                                $type = "Convenios";
                            }
                            echo "<tr id='" . $row['paquete_id'] . "' >";
                            echo "<td>" . $row['titulo_paquete'] . "</td>";
                            echo "<td>" . $type . "</td>";
                            echo "<td>" . $row['numero_sesiones'] . "</td>";
                            echo "<td>" . $row['total'] . "</td>";
                            echo "<td>
                                    <a class='btn btn-primary btn-sm ml-1' href='historia_clinica.php?idusuario=" . $row['id'] . "' data-toggle='modal' data-target='#exampleModal' id='ver_resumen'><i style='font-size:18px' class='fas fa-eye'></i></a>
                                    <a class='btn btn-success btn-sm ml-1' data-toggle='modal' data-target='#editModal' id='editar_paquete' ><i class='fas fa-edit table-icon'></i></a>
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
                        <h5 class="modal-title" id="exampleModalLabel">Detalle Paquete</h5>
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
                        <h5 class="modal-title" id="exampleModalLabel">Editar Paquete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h5 id="paquete_id" data-id="">Detalles del Paquete</h5>
                        <input type="text" name="titulo_paquete" id="titulo_paquete" placeholder="Ingrese titulo del paquete">
                        <input type="number" name="numero_sesiones" id="numero_sesiones" placeholder="Ingrese el número de sesiones para este paquete" min="1">

                        <select name="tipo_paquete" id="tipo_paquete">
                            <option value="0">Seleccione Tipo de Paquete</option>
                            <option value="1">Básico</option>
                            <option value="2">Plus</option>
                            <option value="3">Premium</option>
                            <option value="4">Empresas</option>
                            <option value="5">Convenios</option>
                        </select>
                        <h5>Servicios</h5>
                        <select class="select2 form-control" data-rel="chosen" name="servicios" id="servicios">
                            <option value="0">Seleccione Servicio</option>
                            <option value="1">Displacia de cadera</option>
                            <option value="2">Pie plano</option>
                            <optgroup label="Tumores óseos">
                                <option value="3">Artrosis de rodilla</option>
                                <option value="4">Hernias discales</option>
                                <option value="5">Tratamientos con células madre</option>
                                <option value="6">Tratamientos para artrosis, enfermedades degenerativas, osteomusculares y osteoarticulares</option>
                                <option value="7">Reemplazo Articulares</option>
                                <option value="8">Tratamientos para el adulto</option>
                                <option value="9">Esguinces</option>
                                <option value="10">Fracturas</option>
                                <option value="11">Artrosis</option>
                                <option value="12">Dolores crónicos</option>
                                <option value="13">Fortalecimiento pulmonar</option>
                                <option value="14">Enfermedades crónicas respiratorias</option>
                                <option value="15">Pacientes post-COVID</option>
                                <option value="16">Mejora de calidad del sueño</option>
                                <option value="17">Ayuda a la concentración y memoria</option>
                                <option value="18">Elimina contracturas musculares</option>
                                <option value="19">Incontinecia urinaria</option>
                                <option value="20">Prolapsos</option>
                                <option value="21">Dolores en relaciones sexuales</option>
                                <option value="22">Eyaculación precoz</option>
                                <option value="23">Problemas de erección</option>
                                <option value="24">Programa de hidroterapia personalizado</option>
                            </optgroup>
                            <option value="25">Elimina contracturas musculares</option>
                        </select>
                        <input type="number" placeholder="Nº de sesiones" name="numero_sesion" id="numero_sesion" min="1">
                        <input type="button" value="Agregar" id="agregar_servicio">
                        <h5>Productos</h5>
                        <select class="select2 form-control" data-rel="chosen" id="productos" name="productos" required>
                            <option value="" selected="" hidden="">Seleccione Producto</option>
                            <?php
                            $sql_traer_productos = "SELECT * FROM productos";
                            $consulta_traer_productos = $mysqli->query($sql_traer_productos);
                            while ($row = mysqli_fetch_array($consulta_traer_productos)) {
                                echo "<option data-cost='" . $row['precio_v'] . "'data-count='" . $row['stock'] . "' data-name='" . $row['nombre'] . "' value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                            }
                            ?>
                        </select>
                        <input type="number" placeholder="Ingrese Cantidad" name="cantidad_producto" id="cantidad_producto" min="1">
                        <input type="button" value="Agregar" id="agregar_producto">
                        <table class="table table-bordered table-hover" id="table">
                            <thead class="tabla_cabecera">
                                <tr>
                                    <th>Nombre</th>
                                    <th>Tipo</th>
                                    <th>Valor</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="table_detalle">

                            </tbody>
                        </table>
                        <div class="text-center">
                            <div class="d-flex justify-content-center">
                                <div class="box mx-5">
                                    <div>Total</div>
                                    <div id="total_pago">00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" id="actualizar_paquete">Actualizar Paquete</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script type="text/javascript">
        $('.select2').select2({});
    </script>
    <script src="../js/jquerysearch.js"></script>
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
            FD.append('action', "ver_paquete");
            FD.append("paquete_id", id);
            console.log(id);
            fetch("paquete_ajax.php", {
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
                    let template = `<div>Titulo Paquete: ${data[0].titulo_paquete}</div>
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
         * EDITAR PAQUETE
         */

        const editar = document.querySelectorAll("#editar_paquete");
        editar.forEach((card, i) => {
            card.addEventListener('click', () => {
                var id = card.parentElement.parentElement.id;
                editarPaquete(id);
            });
        });

        var listaPaquete = [];

        function editarPaquete(id) {
            listaPaquete = [];
            const FD = new FormData();
            FD.append('action', "ver_paquete");
            FD.append("paquete_id", id);
            fetch("paquete_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // console.log(data);
                    $('#paquete_id').attr("data-id", data[0].paquete_id);
                    $('#titulo_paquete').val(data[0].titulo_paquete);
                    $('#numero_sesiones').val(data[0].numero_sesiones);
                    $('#tipo_paquete').val(data[0].tipo_paquete);
                    $('#total_pago').text(data[0].total_paquete);

                    data.forEach(datos => {
                        var element = {
                            'id': Number(datos.pro_ser_id),
                            'name': datos.nombre,
                            'type': datos.tipo,
                            'cost': Number(datos.costo),
                            'amount': Number(datos.cantidad),
                            'total': Number(datos.total)
                        }
                        console.log(element);
                        listaPaquete.push(element);
                    });
                    mostrarTabla();
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        $('#agregar_servicio').on('click', function() {
            let idA = $('#servicios').val();
            let numeroSesiones = $('#numero_sesion').val();
            if (idA > 0 && numeroSesiones != undefined && numeroSesiones != 0) {
                var service = servicios.filter(function(item) {
                    return item.id === Number(idA);
                });
                var element = {
                    'id': service[0].id,
                    'name': service[0].name,
                    'type': "Servicio",
                    'cost': service[0].cost,
                    'amount': Number(numeroSesiones),
                    'total': service[0].cost * numeroSesiones
                }
                agregar(element);
            }
        });

        $('#agregar_producto').on('click', function() {
            let idA = $('#productos').val();
            let cantidad = Number($('#cantidad_producto').val());
            var selectElement = document.querySelector('#productos');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var stock = selectedOption.getAttribute('data-count');
            if (idA > 0 && cantidad > 0 && cantidad <= stock) {
                var element = {
                    'id': Number(idA),
                    'name': selectedOption.getAttribute('data-name'),
                    'type': "Producto",
                    'cost': Number(selectedOption.getAttribute('data-cost')),
                    'amount': cantidad,
                    'total': selectedOption.getAttribute('data-cost') * cantidad
                }
                agregar(element);
            }
        });

        function agregar(elemento) {
            var validacion = listaPaquete.filter(function(item) {
                return (item.id === elemento.id && item.type === elemento.type);
            });

            if (validacion.length === 0) {
                listaPaquete.push(elemento);
                mostrarTabla();
                totalizarPago();
            }
        }

        function mostrarTabla() {
            var foo = listaPaquete.map(function(item) {
                let template = `<tr id='${item.id}' data-type='${item.type}'>
                                    <td> ${item.name} </td>
                                    <td> ${item.type} </td>
                                    <td> ${item.cost} </td>
                                    <td> ${item.amount} </td>
                                    <td> ${item.total} </td>
                                    <td><a class='btn btn-danger btn-sm ml-1' id='eliminar_servicio'><i class='fas fa-trash-alt table-icon'></i></a></td>
                                </tr>`;
                return template;
            });
            $('#table_detalle').html(foo);
            const del = document.querySelectorAll("#eliminar_servicio");
            del.forEach((card, i) => {
                card.addEventListener('click', () => {
                    var id = card.parentElement.parentElement.id;
                    var type = card.parentElement.parentElement.getAttribute('data-type');
                    eliminar(id, type);
                });
            });
        }

        $('#actualizar_paquete').on('click', function() {
            const id = Number($('#paquete_id').attr('data-id'));
            actualizar(id);
        });

        function actualizar(paquete_id) {
            // if (validar()) {
            const FD = new FormData();
            FD.append('action', "actualizar_paquete");
            FD.append('paquete_id', paquete_id)
            FD.append('titulo_paquete', $('#titulo_paquete').val());
            FD.append('tipo_paquete', Number($("#tipo_paquete").val()));
            FD.append('numero_sesiones', Number($('#numero_sesiones').val()));
            FD.append('total', Number($('#total_pago').text()));
            FD.append('lista', JSON.stringify(listaPaquete));
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