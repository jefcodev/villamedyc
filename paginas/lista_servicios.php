<?php
include 'header.php';
include '../conection/conection.php';
$conn = $mysqli;

// Obtener la lista de servicios para mostrar en la tabla
$sql = "SELECT * FROM servicios";
$result = $conn->query($sql);
$servicios = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $servicios[$row["id_servicio"]] = [
            "titulo_servicio" => $row["titulo_servicio"],
            "total" => $row["total"],
            "valor_adicional" => $row["valor_adicional"],
        ];
    }
} else {
    echo "No hay servicios.";
}
?>


<head>
    <title>Lista de servicios</title>
    <link href="../css/search.min.css" rel="stylesheet">
</head>
<body>
    <section class="cuerpo">
        <div class="d-flex justify-content-center">
            <div class="alert alert-primary d-none floating-alert" id="alert-primary" role="alert">
                Este es un mensaje de información.
            </div>
            <div class="alert alert-success d-none floating-alert" id="alert-success" role="alert">
                Cita creada correctamente.
            </div>
            <div class="alert alert-danger d-none floating-alert" id="alert-danger" role="alert">
                Este es un mensaje de error.
            </div>
        </div>
        <h1 class="text-center">Lista servicios</h1><br>
        <br>
        <table class="table table-bordered table-hover" id="indexhistoriah">
            <thead class="tabla_cabecera">
                <tr>
                    <th>Id</th>
                    <th>Nombre Servicio</th>
                    <th>Total</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody id="tabla_productos_body">
                <?php foreach ($servicios as $id => $servicio) { ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $servicio['titulo_servicio']; ?></td>
                        <td><?php echo $servicio['valor_adicional']; ?></td>
                        <td>
                            <button class="btn btn-primary btn-sm editarServicioBtn" data-id="<?php echo $id; ?>">Editar</button>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
    </section>

    <!-- Modal de Edición de Servicio -->
    <div id="editarServicioModal" class="modal fade" role="dialog" tabindex="-1" aria-labelledby="editarServicioModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarServicioModalLabel">Editar Servicio</h5>
                    <button type="button" class="close" data-modal-close>&times;</button>
                </div>
                <div class="modal-body">
                    <div class="col-10">
                        <div class="col-md-6">
                            <input type="text" name="titulo_servicio" id="titulo_servicio" placeholder="Ingrese nombre del servicio" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="producto">Producto:</label>
                            <select id="producto" name="producto" class="select2 form-control">
                                <option value="">Seleccionar</option>
                                <?php
                                $sql = "SELECT id, nombre, precio_c, stock FROM productos";
                                $result = $conn->query($sql);
                                $productos = [];
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $productos[$row["id"]] = [
                                            "nombre" => $row["nombre"],
                                            "precio" => $row["precio_c"],
                                            "stock" => $row["stock"]
                                        ];
                                    }
                                } else {
                                    echo "No hay productos disponibles.";
                                }
                                foreach ($productos as $codigo => $producto) { ?>
                                    <option value="<?php echo $codigo; ?>" data-precio="<?php echo $producto['precio']; ?>"><?php echo $producto['nombre']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="cantidad">Cantidad:</label>
                            <input type="number" id="cantidad" name="cantidad" value="1" min="1" class="form-control" disabled>
                        </div>
                        <div class="col-md-12">
                            <button id="agregar_producto" class="btn btn-primary">Agregar</button>
                        </div>

                    </div>
                    <input type="hidden" id="editarServicioId" value="">

                    <h5>Detalle del servicio:</h5>
                    <table class="table" id="editarServicioDetalleTable">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="tabla_body"></tbody>
                    </table>
                    <div class="col-md-6">
                        <label for="total_pago">Sub Total</label>
                        <span id="total_pago">0.00</span>
                    </div>
                    <div class="col-md-6">
                        <label for="valor_adicional">Total:</label>
                        <input type="number" id="valor_adicional" name="valor_adicional" step="0.01" min="0" value="0" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-modal-close>Cerrar</button>
                    <button type="button" class="btn btn-primary" onclick="guardarCambiosServicio()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <?php
    include 'footer.php';
    ?>

    <script type="text/javascript">
        $('.select2').select2({});
    </script>


    <script>
        // $(document).ready(function() {

        // });

        var productosSeleccionados = [];

        // Evento de clic en el botón de editar servicio
        $(".editarServicioBtn").click(function() {
            var servicioId = $(this).data("id");
            var servicio = <?php echo json_encode($servicios); ?>;
            obtenerDetalleServicio(servicioId); // Llama a la función para obtener los detalles del servicio
            llenarFormularioEdicion(servicioId, servicio[servicioId]);
            $("#editarServicioModal").modal("show");
        });

        // Evento de clic en el botón de cerrar modal
        $("[data-modal-close]").click(function() {
            $(this).closest(".modal").modal("hide");
        });

        function obtenerDetalleServicio(servicioId) {
            const FD = new FormData();
            FD.append('action', "ver_servicio");
            FD.append("servicio_id", servicioId);
            fetch("servicio.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    // console.log(data);
                    var detalleServicio = data;
                    mostrarDetalleServicio(detalleServicio);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        function mostrarDetalleServicio(detalleServicio) {
            var detalleTableId = "#editarServicioDetalleTable";
            var detalleTableBody = $(detalleTableId + " tbody");
            detalleTableBody.empty();
            productosSeleccionados = detalleServicio;
            productosSeleccionados.forEach(function(detalle, index) {
                var detalleRow = "<tr>" +
                    "<td>" + detalle.id_producto + "</td>" +
                    "<td>" + detalle.nombre + "</td>" +
                    "<td>" + detalle.precio + "</td>" +
                    "<td>" + detalle.cantidad + "</td>" +
                    "<td>" + detalle.subtotal + "</td>" +
                    "<td><button class='eliminar-producto' data-index='" + index + "'>Eliminar</button></td>" +
                    "</tr>";
                detalleTableBody.append(detalleRow);
            });
        }

        function llenarFormularioEdicion(servicioId, servicio) {
            $("#titulo_servicio").val(servicio.titulo_servicio);
            $("#editarServicioId").val(servicioId);
            $("#total_pago").text(servicio.total);
            $("#valor_adicional").val(servicio.valor_adicional);

            // Agregar el ID seleccionado al campo correspondiente en el modal
            $("#editarServicioId").text(servicioId);
        }

        function guardarCambiosServicio() {
            // Obtener los valores del formulario de edición
            var servicioId = $("#editarServicioId").val();
            var tituloServicio = $("#titulo_servicio").val();
            var total = $("#total_pago").text();
            var valorAdicional = $("#valor_adicional").val();

           /*  if (tituloServicio && productosSeleccionados.length > 0) { */
                // console.log(servicioId)
                // console.log(total)
                // console.log(valorAdicional)
                const FD = new FormData();
                FD.append('action', "actualizar_servicio");
                FD.append("servicio_id", servicioId);
                FD.append("titulo_servicio", tituloServicio);
                FD.append("productos", JSON.stringify(productosSeleccionados));
                FD.append("total_pago", total);
                FD.append("valor_adicional", valorAdicional);
                fetch("servicio.php", {
                        method: 'POST',
                        body: FD
                    }).then(respuesta => respuesta.text())
                    .then(decodificado => {
                        console.log(decodificado);
                        // const data = JSON.parse(decodificado);
                        // console.log(data);
                        location.reload();
                        alert(decodificado);

                    
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
           /*  } else {
                alert('Debe ingresar un título de servicio y seleccionar al menos un producto');
            } */

            // Realizar una petición AJAX para guardar los cambios en la base de datos

            // Cerrar el modal de edición

        }

        function actualizarTablaProductos() {
            var tablaProductosBody = $('#tabla_body');
            tablaProductosBody.empty();

            productosSeleccionados.forEach(function(detalle, index) {
                var fila = "<tr>" +
                    "<td>" + detalle.id_producto + "</td>" +
                    "<td>" + detalle.nombre + "</td>" +
                    "<td>" + detalle.precio + "</td>" +
                    "<td>" + detalle.cantidad + "</td>" +
                    "<td>" + detalle.subtotal + "</td>" +
                    "<td><button class='eliminar-producto' data-index='" + index + "'>Eliminar</button></td>" +
                    "</tr>";
                tablaProductosBody.append(fila);
            });
        }

        function actualizarTotalPago() {
            var total = productosSeleccionados.reduce((acumulador, producto) => acumulador + Number(producto.subtotal), 0);
            $('#total_pago').text(total);
        }

        $('#agregar_producto').click(function() {
            console.log(productosSeleccionados);
            var selectedOption = $('#producto').find('option:selected');
            var id = selectedOption.val();
            var nombre = selectedOption.text();
            var precio = parseFloat(selectedOption.data('precio'));
            var cantidad = parseInt($('#cantidad').val());

            if (id && nombre && precio && cantidad) {
                // Verificar si el producto ya está en la lista
                var productoExistente = productosSeleccionados.find(function(producto) {
                    return producto.id_producto == id;
                });

                if (productoExistente) {
                    alert('El producto ya se encuentra en la lista.');
                } else {
                    var producto = {
                        id_producto: id,
                        nombre: nombre,
                        precio: precio,
                        cantidad: cantidad,
                        subtotal: precio * cantidad
                    };

                    productosSeleccionados.push(producto);
                    actualizarTablaProductos();
                    actualizarTotalPago();
                }
            }
        });

        $('#tabla_body').on('click', '.eliminar-producto', function() {
            var index = $(this).data('index');
            var producto = productosSeleccionados[index];
            productosSeleccionados.splice(index, 1);
            actualizarTablaProductos();
            actualizarTotalPago();
        });
    </script>
    <script src="../js/jquerysearch.js"></script>
    <script>
        $(document).ready(function () {
            $('#indexhistoriah').DataTable({
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
    </script>
</body>

</html>