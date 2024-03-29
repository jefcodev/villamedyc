<!DOCTYPE html>
<html><?php
        include 'header.php';
        $pagina = PAGINAS::LISTA_USUARIOS;
        if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
            header("location:./inicio.php?status=AD");
        }
        ?>


<body>
    <section class="cuerpo">
        <div class="d-flex justify-content-center">
            <div class="alert alert-primary d-none floating-alert" id="alert-primary" role="alert">
                Este es un mensaje de información.
            </div>

            <div class="alert alert-success d-none floating-alert" id="alert-success" role="alert">
                Paquete acutilizado correctamente.
                Por favor actualice la página para ver los cambios.
                <button onclick="location.reload()">Actualizar</button>
            </div>

            <div class="alert alert-danger d-none floating-alert" id="alert-danger" role="alert">
                Este es un mensaje de error.
            </div>
        </div>
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
                            <?php
                            $sql_traer_servicios = "SELECT * FROM servicios";
                            $consulta_traer_servicios = $mysqli->query($sql_traer_servicios);
                            while ($row = mysqli_fetch_array($consulta_traer_servicios)) {
                                echo "<option data-cost='" . $row['total'] . "' data-name='" . $row['titulo_servicio'] . "' value='" . $row['id_servicio'] . "'>" . $row['titulo_servicio'] . "</option>";
                            }
                            ?>
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
    <?php
    include 'footer.php';
    ?>

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
                // var service = servicios.filter(function(item) {
                //     return item.id === Number(idA);
                // });
                var selectedOption = $('#servicios').find('option:selected');
                //var idA = selectedOption.val();
                var nombre = selectedOption.text();
                var precio = parseFloat(selectedOption.data('cost'));

                var element = {
                    'id': idA,
                    'name': nombre,
                    'type': "Servicio",
                    'cost': precio,
                    'amount': Number(numeroSesiones),
                    'total': precio * numeroSesiones
                }
                agregar(element);
                const FD = new FormData();
                FD.append('action', "buscar_productos_servicio");
                FD.append('id_servicio', idA);
                fetch("paquete_ajax.php", {
                        method: 'POST',
                        body: FD
                    }).then(respuesta => respuesta.text())
                    .then(decodificado => {
                        console.log(decodificado);
                        const data = JSON.parse(decodificado);
                        data.forEach(datos => {
                            var element = {
                                'id': Number(datos.id_producto),
                                'name': datos.nombre,
                                'type': "Producto",
                                'cost': Number(datos.precio),
                                'amount': Number(datos.cantidad),
                                'total': Number(datos.subtotal)
                            }
                            agregar(element);
                        });
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
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
            } else {
                var alertElement = document.getElementById('alert-danger');
                var duracion = 3000;
                $('#alert-danger').html('Cantidad no disponible en stock');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
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
            } else {
                var alertElement = document.getElementById('alert-danger');
                var duracion = 3000;
                $('#alert-danger').html('Servicio/Producto ya agregado');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
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

        function validar() {
            let validator = true;
            var alertElement = document.getElementById('alert-danger');
            var duracion = 3000;
            if ($('#titulo_paquete').val() == '') {
                validator = false;
                $('#alert-danger').html('Ingrese titulo del paquete');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if ($('#numero_sesiones').val() < 1) {
                validator = false;
                $('#alert-danger').html('Ingrese numero de sesiones correctas');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if ($('#tipo_paquete').val() == 0) {
                validator = false;
                $('#alert-danger').html('Seleccione tipo de paquete');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if (listaPaquete.length == 0) {
                validator = false;
                $('#alert-danger').html('Agregue pòr lo menos un servicio');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            return validator;
        }

        $('#actualizar_paquete').on('click', function() {
            const id = Number($('#paquete_id').attr('data-id'));
            actualizar(id);
        });

        function actualizar(paquete_id) {
            if (validar()) {
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
                        var alertElement = document.getElementById('alert-success');
                        alertElement.classList.remove('d-none');
                        setTimeout(function() {
                            alertElement.classList.add('d-none');
                        }, 3000);
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            }
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