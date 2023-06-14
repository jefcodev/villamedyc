<?php
include 'header.php';
$pagina = PAGINAS::LISTA_PACIENTES;
$status = $_GET['status'];
$class = '';
$close = '';
if (isset($status)) {
    $close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';
    if ($status === 'OK') {
        $error = 'Paciente creado correctamente';
        $class = 'class="alert alert-success alert-dismissible fade show" role="alert"';
    } else {
        $error = 'Ocurrió un error al crear el paciente';
        $class = 'class="alert alert-danger alert-dismissible fade show" role="alert"';
    }
}
?>

<body>
    <div>
        <section class="cuerpo">
            <div class="d-flex justify-content-center">
                <div class="alert alert-primary d-none" id="alert-primary" role="alert">
                    Este es un mensaje de información.
                </div>

                <div class="alert alert-success d-none" id="alert-success" role="alert">
                    Cita creada correctamente.
                </div>

                <div class="alert alert-danger d-none" id="alert-danger" role="alert">
                    Este es un mensaje de error.
                </div>
            </div>
            <h1 class="text-center">Crear Paquete</h1><br>
            <div class="row">
                <div class="col-md-12">
                    <h4>Agregar Detalles del Paquete</h4>
                </div><br><br>
                <div class="col-md-6">
                    <input type="text" name="titulo_paquete" id="titulo_paquete" placeholder="Ingrese titulo del paquete">
                    <input type="number" name="numero_sesiones" id="numero_sesiones" placeholder="Ingrese el número de sesiones para este paquete">
                </div>
                <div class="col-md-6">
                    <select name="tipo_paquete" id="tipo_paquete">
                        <option value="0">Seleccione Tipo de Paquete</option>
                        <option value="1">Básico</option>
                        <option value="2">Plus</option>
                        <option value="3">Premium</option>
                        <option value="4">Empresas</option>
                        <option value="5">Convenios</option>
                    </select>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12">
                    <h4>Agregar Servicios</h4>
                </div><br><br>
                <div class="col-md-12">
                    <select name="servicios" id="servicios">
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
                    <input type="number" placeholder="Nº de sesiones" name="numero_sesion" id="numero_sesion" value="Ingrese numero de sesiones">
                    <input type="button" value="Agregar" id="agregar_servicio">
                </div>
                <div class="col-md-12">
                    <h4>Agregar Productos</h4>
                </div><br><br>
                <div class="col-md-12">
                    <select id="productos" name="productos" required>
                        <option value="" selected="" hidden="">Seleccione Producto</option>
                        <?php
                        $sql_traer_productos = "SELECT * FROM productos";
                        $consulta_traer_productos = $mysqli->query($sql_traer_productos);
                        while ($row = mysqli_fetch_array($consulta_traer_productos)) {
                            echo "<option data-cost='" . $row['precio_v'] . "'data-count='" . $row['stock'] . "' data-name='" . $row['nombre'] . "' value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                        }
                        ?>
                    </select>
                    <input type="number" placeholder="Ingrese Cantidad" name="cantidad_producto" id="cantidad_producto" min="1" value="Cantidad">
                    <input type="button" value="Agregar" id="agregar_producto">
                </div>
                <div class="col-md-12">
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
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="box mx-5">
                                <div>Total</div>
                                <div id="total_pago">00</div>
                            </div>
                        </div>
                    </div><br>
                    <div class="d-flex justify-content-center">
                        <input class="btn btn-primary" type="submit" name="btn_crear_paquete" id="crear_paquete" value="Crear Paquete" />
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- <script type="text/javascript">
        $('.select2').select2({});
    </script> -->
    <script language="javascript" src="../js/jquery.datetimepicker.full.min.js"></script>
    <script>
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
                    'amount': numeroSesiones,
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
                    'cost': selectedOption.getAttribute('data-cost'),
                    'amount': cantidad,
                    'total': selectedOption.getAttribute('data-cost') * cantidad
                }
                agregar(element);
            }
        });

        var listaPaquete = [];

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

        function validar() {
            let validator = true;
            var alertElement = document.getElementById('alert-danger');
            var duracion = 3000;
            if (listaPaquete.length === 0) {
                validator = false;
                $('#alert-danger').html('Seleccione Servicios o Productos');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            return validator;
        }

        function crear() {
            // if (validar()) {
                const FD = new FormData();
                FD.append('action', "crear_paquete");
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
                        var alertElement = document.getElementById('alert-success');
                        alertElement.classList.remove('d-none');
                        setTimeout(function() {
                            alertElement.classList.add('d-none');
                        }, 3000);
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            // }
        }

        $('#crear_paquete').on('click', function() {
            crear();
        });
    </script>
</body>

</html>