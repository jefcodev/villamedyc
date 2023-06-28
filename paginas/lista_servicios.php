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
            "total" => $row["total"],
            "valor_adicional" => $row["valor_adicional"],
        ];
    }
} else {
    echo "No hay servicios.";
}
?>

<head>
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />
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
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Total</th>
                    <th>Valor Adicional</th>
                    <th>Opciones</th>
                </tr>
            </thead>
            <tbody id="tabla_productos_body">
                <?php foreach ($servicios as $id => $servicio) { ?>
                    <tr>
                        <td><?php echo $id; ?></td>
                        <td><?php echo $servicio['total']; ?></td>
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
    <div id="editarServicioModal" class="modal" style="width: 100%">
        <div class="modal-dialog">

            <div class="modal-header">
                <h5 class="modal-title">Editar Servicio</h5>
                <button type="button" class="close" data-modal-close>&times;</button>
            </div>
            <div class="modal-body">
                <form id="editarServicioForm">
                    <input type="hidden" id="editarServicioId" value="">
                    <div class="form-group">
                        <label for="editarServicioTotal">Total</label>
                        <input type="text" class="form-control" id="editarServicioTotal" name="total" value="">
                    </div>
                    <div class="form-group">
                        <label for="editarServicioValorAdicional">Valor Adicional</label>
                        <input type="text" class="form-control" id="editarServicioValorAdicional" name="valor_adicional" value="">
                    </div>
                    <h5>Detalle del servicio:</h5>
                    <table class="table" id="editarServicioDetalleTable">
                        <thead>
                            <tr>
                                <th>ID Detalle</th>
                                <th>ID Producto</th>
                                <th>Nombre</th>
                                <th>Precio</th>
                                <th>Cantidad</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-modal-close>Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarCambiosServicio()">Guardar cambios</button>
            </div>

        </div>
    </div>


    <script>
        $(document).ready(function() {
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
        });

        function obtenerDetalleServicio(servicioId) {
            // Realizar una consulta a la base de datos para obtener el detalle del servicio
            // Utiliza el servicioId para filtrar los registros de la tabla "deatelle_servicio"
            // y devuelve los resultados como un arreglo

            // Realizar la consulta SQL para obtener los detalles del servicio
            <?php
            try {
                //code...
                $detalleSql = "SELECT * FROM deatelle_servicio WHERE id_servicio = ?";


                $detalleStmt = $conn->prepare($detalleSql);
                $detalleStmt->bind_param("i", $servicioId);
                $detalleStmt->execute();
                if (!$detalleStmt->execute()) {
                    echo "Error enasas la consulta: " . $detalleStmt->error;
                    return;
                }

                $detalleResult = $detalleStmt->get_result();

                $detalleServicio = [];
                if ($detalleResult->num_rows > 0) {
                    while ($detalleRow = $detalleResult->fetch_assoc()) {
                        $detalleServicio[] = [
                            "id_detalle_servicio" => $detalleRow["id_det_servicio"],
                            "id_producto" => $detalleRow["id_producto"],
                            "nombre" => $detalleRow["nombre"],
                            "precio" => $detalleRow["precio"],
                            "cantidad" => $detalleRow["cantidad"],
                            "subtotal" => $detalleRow["subtotal"],
                        ];
                    }
                }

                // Convertir los detalles del servicio en formato PHP a JSON para utilizarlo en JavaScript
                $detalleServicioJson = json_encode($detalleServicio);
            } catch (\Throwable $th) {
                echo $th;

                throw $th;
            }

            ?>

            var detalleServicio = <?php echo $detalleServicioJson; ?>;
            mostrarDetalleServicio(detalleServicio);
        }

        function mostrarDetalleServicio(detalleServicio) {
            var detalleTableId = "#editarServicioDetalleTable";
            var detalleTableBody = $(detalleTableId + " tbody");
            detalleTableBody.empty();

            detalleServicio.forEach(function(detalle) {
                var detalleRow = "<tr>" +
                    "<td>" + detalle.id_detalle_servicio + "</td>" +
                    "<td>" + detalle.id_producto + "</td>" +
                    "<td>" + detalle.nombre + "</td>" +
                    "<td>" + detalle.precio + "</td>" +
                    "<td>" + detalle.cantidad + "</td>" +
                    "<td>" + detalle.subtotal + "</td>" +
                    "</tr>";
                detalleTableBody.append(detalleRow);
            });
        }

        function llenarFormularioEdicion(servicioId, servicio) {
            $("#editarServicioId").val(servicioId);
            $("#editarServicioTotal").val(servicio.total);
            $("#editarServicioValorAdicional").val(servicio.valor_adicional);

            // Agregar el ID seleccionado al campo correspondiente en el modal
            $("#editarServicioId").text(servicioId);
        }

        function guardarCambiosServicio() {
            // Obtener los valores del formulario de edición
            var servicioId = $("#editarServicioId").val();
            var total = $("#editarServicioTotal").val();
            var valorAdicional = $("#editarServicioValorAdicional").val();

            console.log(servicioId)

            console.log(total)

            console.log(valorAdicional)

            // Realizar una petición AJAX para guardar los cambios en la base de datos

            // Cerrar el modal de edición

        }
    </script>
</body>

</html>