<?php
include 'header.php';
include '../conection/conection.php';

$id_consulta = $_GET['id_cita'];
$totalConsulta = 0;
$totalPaquete = 0;

$sql_paciente = "SELECT id_paciente, id_doctor, nombre_doctor, apellidos_doctor FROM consultas_datos WHERE id_consulta = $id_consulta";

$resultado = ($mysqli->query($sql_paciente))->fetch_assoc();

$id_paciente = $resultado['id_paciente'];
$id_doctor = $resultado['id_doctor'];
$user_doctor = $resultado['nombre_doctor'] . ' ' . $resultado['apellidos_doctor'];
$estado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('America/Guayaquil');
    $fecha_venta = date("Y-m-d H:i:s");
    $total = $_POST['total'];
    $descuento = $_POST['descuento'];
    $tipo_pago = $_POST['tipo_pago'];
    $detalles = $_POST['detalles'];

    $error = false;

    // Iniciar una transacción
    $mysqli->begin_transaction();

    foreach ($detalles['tipo_item'] as $i => $tipo_item) {
        $item_id = $detalles['item_id'][$i];
        $cantidad = $detalles['cantidad'][$i];
        $precio_unitario = $detalles['precio'][$i];
        $subtotal = $cantidad * $precio_unitario;

        $insert_detalle_sql = "INSERT INTO ventas_detalle (venta_id, tipo_item, item_id, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insert_detalle_sql);
        $stmt->bind_param("issidd", $venta_id, $tipo_item, $item_id, $cantidad, $precio_unitario, $subtotal);

        if ($stmt->execute()) {
            if ($tipo_item === 'producto') {
                $update_stock_sql = "UPDATE productos SET stock = stock - $cantidad WHERE id = $item_id";

                if ($mysqli->query($update_stock_sql)) {
                    // Actualización de stock exitosa
                } else {
                    $error = true;
                    echo "Error en la actualización de stock: " . $mysqli->error;
                }
            } elseif ($tipo_item === 'paquete') {
                $num_sesiones = "SELECT numero_sesiones FROM paquete_cabecera WHERE paquete_id = $item_id";
                $num_result = $mysqli->query($num_sesiones);

                if ($num_result) {
                    if ($num_result->num_rows > 0) {
                        $row = $num_result->fetch_assoc();
                        $numero_sesiones = $row['numero_sesiones'];
                    } else {
                        echo "No se encontraron resultados.";
                    }
                }

                $insert_sesiones = "INSERT INTO consultas_fisioterapeuta (paciente_id, numero_sesiones, paquete_id, total_sesiones) VALUES (?, ?, ?, ?)";
                $stmt = $mysqli->prepare($insert_sesiones);
                $stmt->bind_param("iiii", $id_paciente, $numero_sesiones, $item_id, $numero_sesiones);
                $stmt->execute();

                $paquete_detalle_sql = "SELECT pro_ser_id, cantidad FROM paquete_detalle WHERE paquete_id = $item_id AND tipo = 'Producto'";
                $paquete_detalle_result = $mysqli->query($paquete_detalle_sql);

                if ($paquete_detalle_result) {
                    while ($row = $paquete_detalle_result->fetch_assoc()) {
                        $producto_id = $row['pro_ser_id'];
                        $cantidad_paquete = $row['cantidad'];

                        $update_stock_sql = "UPDATE productos SET stock = stock - ($cantidad * $cantidad_paquete) WHERE id = $producto_id";

                        if ($mysqli->query($update_stock_sql)) {
                            // Actualización de stock exitosa
                        } else {
                            $error = true;
                            echo "Error en la actualización de stock: " . $mysqli->error;
                        }
                    }
                } else {
                    $error = true;
                    echo "Error al obtener los detalles del paquete: " . $mysqli->error;
                }
            }
        } else {
            $error = true;
            echo "Error en la inserción de detalle: " . $mysqli->error;
            break; // Salir del bucle si hay un error
        }
    }

    $insert_cabecera_sql = "INSERT INTO ventas_cabecera (fecha_venta, id_consulta, id_paciente, usuario, total, descuento, id_user, tipo_pago) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare($insert_cabecera_sql);
    $stmt->bind_param("sisisiis", $fecha_venta, $id_consulta, $id_paciente, $user_doctor, $total, $descuento, $id_doctor, $tipo_pago);

    if ($stmt->execute()) {
        $venta_id = $stmt->insert_id; // Asignar el valor aquí

        if (!$error) {
            $actualizarEstado = "UPDATE consultas SET estado = 'pagado' WHERE id = $id_consulta";
            $mysqli->query($actualizarEstado);

            // Todas las operaciones fueron exitosas, confirmar la transacción
            $mysqli->commit();

            // Si no hubo errores, muestra la alerta de éxito
            echo "<script>
                Swal.fire({
                    title: 'Compra agregada',
                    text: 'Compra agregada correctamente!',
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Imprimir!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        var pdfUrl = 'comprobante_venta.php?venta_id=' + $venta_id;
                        var pdfWindow = window.open(pdfUrl, '_blank');
                        pdfWindow.onload = function() {
                            pdfWindow.print();
                            window.location.href = 'inicio.php';
                        };
                    } else {
                        window.location.href = 'inicio.php';
                    }
                });
            </script>";
            exit; // Asegúrate de que no haya más salida después de la redirección
        }
    } else {
        $error = true;
        echo "Error en la inserción de cabecera: " . $mysqli->error;
    }

    if ($error) {
        // Revertir la transacción en caso de error
        $mysqli->rollback();
    }
}
?>


<body>
    <section class="cuerpo">
        <h2>Nota de venta</h2>
        <br>
        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover" id="indexusuarios">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Fecha</th>
                            <th>Cédula</th>
                            <th>Cliente</th>
                            <th>Doctor</th>
                            <th>Descripción Consulta</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_ventas = "SELECT * FROM consultas_datos WHERE id_consulta = $id_consulta";
                        $result = $mysqli->query($sql_ventas);

                        while ($row = mysqli_fetch_array($result)) {
                            echo "<tr id='" . $row['id_consulta'] . "' >";
                            echo "<td>" . $row['fecha_hora'] . "</td>";
                            echo "<td>" . $row['numero_identidad'] . "</td>";
                            echo "<td>" . $row['nombres'] . ' ' . $row['apellidos'] . "</td>";
                            echo "<td>" . $row['nombre_doctor'] . ' ' . $row['apellidos_doctor'] . "</td>";
                            echo "<td>" . $row['descripcion_precio'] . "</td>";

                            echo "</tr>";
                            $totalConsulta += $row['precio'];
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2>Nueva Venta</h2>
        <form id="compraForm" method="POST">


            <br>
            <table class="table table-bordered table-hover" id="detalleTable">
                <thead class="tabla_cabecera">
                    <tr>
                        <th style="width: 15%">Tipo</th>
                        <th style="width: 45%">Item</th>
                        <th style="width: 10%">Cantidad</th>
                        <th style="width: 10%">Precio</th>
                        <th style="width: 10%">Subtotal</th>
                        <th style="width: 10%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas de detalle se agregarán aquí -->
                </tbody>
            </table>
            <button class="btn btn-primary" type="button" id="addDetalle"> <i class="fa-solid fa-plus"></i> Agregar Item</button><br><br>
            <br>
            <label for="descuento">Descuento:</label>
            <input class="form-control" style="width: 20%;" type="number" id="descuento" name="descuento"><br>
            <label for="total">Total:</label>
            <select class="form-control" style="width: 20%;" title="Género" id="tipo_pago" name="tipo_pago" required>
                        <option value="" selected="" hidden="">Tipo Pago</option>
                        <option value="efectivo">Efectivo</option>
                        <option value="transferencia">Tranferencia</option>
                        <option value="tarjeta">Tarjeta Débito o Crédito</option>
            </select>
            <input class="form-control" style="width: 20%;" type="text" id="total" name="total" readonly><br>
            <button class="btn btn-success" style="width: 20%;" type="submit"><i class="fa-regular fa-floppy-disk"></i> Guardar Venta</button>
        </form>

    </section>
    <?php
    include 'footer.php';
    ?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addDetalleButton = document.getElementById("addDetalle");
            const detalleTableBody = document.querySelector("#detalleTable tbody");
            const totalInput = document.getElementById("total");
            const descuentoInput = document.querySelector("#descuento");




            descuentoInput.addEventListener("input", () => {
                const descuentoValue = descuentoInput.value.trim(); // Obtener el valor del campo sin espacios en blanco

                if (descuentoValue === "") {
                    // Si el campo de descuento está vacío, establecer el valor del descuento en cero
                    descuentoInput.value = "0";
                } else {
                    // Si se ingresa un valor, convertirlo a número
                    const descuento = parseFloat(descuentoValue) || 0;
                    const total = calcularTotal() - descuento;
                    totalInput.value = total.toFixed(2);
                }
            });

            function calcularTotal() {
                let total = 0;
                const detalleRows = detalleTableBody.querySelectorAll("tr");
                detalleRows.forEach((row) => {
                    const precio = parseFloat(row.querySelector(".precio").value) || 0;
                    const cantidad = parseInt(row.querySelector(".cantidad").value) || 0;
                    const subtotal = precio * cantidad || 0;
                    row.querySelector(".subtotal").textContent = subtotal.toFixed(2);
                    total += subtotal;
                });
                return total;
            }

            // Llama a la función para calcular el total inicial
            totalInput.value = calcularTotal().toFixed(2);

            function updateSubtotal() {

                let total = 0;

                const detalleRows = detalleTableBody.querySelectorAll("tr");
                detalleRows.forEach((row) => {
                    const precio = parseFloat(row.querySelector(".precio").value) || 0;
                    const cantidad = parseInt(row.querySelector(".cantidad").value) || 0;
                    const subtotal = precio * cantidad || 0;
                    row.querySelector(".subtotal").textContent = subtotal.toFixed(2);
                    total += subtotal;
                });
                // Obtener el valor del descuento desde el campo de entrada

                totalInput.value = total.toFixed(2);
            }

            function handleDeleteRow(event) {
                if (event.target.classList.contains("deleteRow")) {
                    const row = event.target.closest("tr");
                    row.remove();
                    updateSubtotal();
                }
            }

            function obtenerPrecioDesdeBaseDeDatos(tipoItem, item_id, callback) {
                // Realizar una llamada AJAX para obtener el precio desde la base de datos
                fetch(`get_price.php?tipo_item=${tipoItem}&item_id=${item_id}`)
                    .then(response => response.json())
                    .then(data => {
                        const precio = parseFloat(data.precio);
                        callback(precio);
                    })
                    .catch(error => {
                        console.error("Error en la llamada AJAX: " + error);
                    });
            }


            addDetalleButton.addEventListener("click", () => {
                const newRow = document.createElement("tr");
                newRow.innerHTML = `
                <td>
                    <select name="detalles[tipo_item][]" class="tipo_item form-control">
                        <option value="">Seleccionar</option>
                        <option value="producto">Producto</option>
                        <option value="servicio">Servicio</option>
                        <option value="paquete">Paquete</option>
                    </select>
                </td>
                <td>
                    <select name="detalles[item_id][]" class="item_id form-control">
                    
                        <!-- Las opciones se cargarán dinámicamente aquí -->
                    </select>
                </td>
                <td><input type="number" step="1" min="1" name="detalles[cantidad][]" class="cantidad form-control" /></td>
                <td><input  name="detalles[precio][]" class="precio form-control" readonly/></td>
                <td><label  name="detalles[subtotal][]" class="subtotal form-control" readonly/> 0.00</td>
                
                <td><button type="button" class="btn btn-danger deleteRow"><i class="fas fa-trash-alt"></i></button></td>
            `;
                detalleTableBody.appendChild(newRow);

                detalleTableBody.addEventListener("change", (event) => {
                    const target = event.target;
                    if (target.classList.contains("tipo_item") || target.classList.contains("item_id")) {
                        const row = target.closest("tr");
                        const tipoItemSelect = row.querySelector(".tipo_item");
                        const precioInput = row.querySelector(".precio");
                        const itemSelect = row.querySelector(".item_id");

                        if (target.classList.contains("item_id")) {
                            const selectedType = tipoItemSelect.value;
                            const selectedItemId = itemSelect.value;

                            // Realizar la llamada AJAX para obtener el precio solo si se ha seleccionado un item_id
                            if (selectedItemId) {
                                obtenerPrecioDesdeBaseDeDatos(selectedType, selectedItemId, (precio) => {
                                    // Asignar el precio al input correspondiente
                                    precioInput.value = precio;

                                    // Actualizar subtotal y total
                                    updateSubtotal();
                                    console.log("Seleccion de id " + selectedItemId);
                                });
                            }
                        }

                        console.log("Seleccion de items " + tipoItemSelect.value);
                    }
                });


                // Configurar la función de cambio de tipo y actualización de subtotal
                const tipoItemSelectNew = newRow.querySelector(".tipo_item");
                const itemSelectNew = newRow.querySelector(".item_id");

                tipoItemSelectNew.addEventListener("change", function() {
                    itemSelectNew.innerHTML = "";
                    const selectedType = tipoItemSelectNew.value;

                    // Llamada AJAX para obtener los datos de la base de datos
                    fetch(`get_items.php?tipo_item=${selectedType}`)
                        .then((response) => response.json())
                        .then((data) => {
                            data.forEach((item) => {
                                const option = document.createElement("option");
                                option.value = item.id;
                                option.textContent = "  (" + item.sesiones + ") - " + item.nombre + " - $" + item.total;
                                itemSelectNew.appendChild(option);
                            });
                        });
                });

                newRow.addEventListener("input", updateSubtotal);
                newRow.addEventListener("click", handleDeleteRow);
            });

            detalleTableBody.addEventListener("input", updateSubtotal);
            detalleTableBody.addEventListener("click", handleDeleteRow);
        });
    </script>


</body>

</html>