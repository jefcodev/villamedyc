<?php
include 'header.php';
include '../conection/conection.php';
$estado = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('America/Guayaquil');
    $fecha_venta = date("Y-m-d H:i:s");
    $id_paciente = $_POST['id_paciente'];
    $total = $_POST['total'];
    $descuento = $_POST['descuento'];
    $detalles = $_POST['detalles'];
    $tipo_pago = $_POST['tipo_pago'];
    // Iniciar una transacción
    $mysqli->begin_transaction();

    $error = false; // Variable para rastrear errores

    foreach ($detalles['tipo_item'] as $i => $tipo_item) {
        $item_id = $detalles['item_id'][$i];
        $cantidad = $detalles['cantidad'][$i];

        if ($tipo_item === 'producto') {
            // Consultar el stock actual
            $consulta_stock = "SELECT stock FROM productos WHERE id = ?";
            $stmt = $mysqli->prepare($consulta_stock);
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $stmt->bind_result($stock_actual);
            $stmt->fetch();
            $stmt->close();

            if ($cantidad > $stock_actual) {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Stock no disponible.',
                        showConfirmButton: false,
                        timer: 1500
                    });
                </script>";
                $error = true;
                break; // Salir del bucle si el stock no es suficiente
            }
        }
    }
    $id_user = $_SESSION['id'];
    $estado_pago = 'pendiente';
    if (!$error) {
        // Insertar la cabecera de la venta
        // Define el estado de pago (por ejemplo, 'pendiente')

        $insert_cabecera_sql = "INSERT INTO ventas_cabecera (fecha_venta, id_paciente, total, usuario, descuento, id_user, tipo_pago, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $mysqli->prepare($insert_cabecera_sql);
        $stmt->bind_param("ssisiiss", $fecha_venta, $id_paciente, $total, $usuario, $descuento, $id_user, $tipo_pago, $estado_pago);

        if ($stmt->execute()) {
            $venta_id = $mysqli->insert_id;

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
                        // Obtener los productos asociados a la cabecera del paquete
                        $num_sesiones = "SELECT numero_sesiones FROM paquete_cabecera WHERE paquete_id = $item_id";
                        $num_result = $mysqli->query($num_sesiones);

                        if ($num_result) {
                            // Verificar si se encontraron filas
                            if ($num_result->num_rows > 0) {
                                // Obtener la primera fila como un array asociativo
                                $row = $num_result->fetch_assoc();

                                // Extraer el valor de 'numero_sesiones' en una variable
                                $numero_sesiones = $row['numero_sesiones'] * $cantidad;

                                // Ahora $numero_sesiones contiene el valor de 'numero_sesiones'
                                // y puedes usarlo en tu código
                                echo "Número de Sesiones: " . $numero_sesiones;
                            } else {
                                echo "No se encontraron resultados.";
                            }
                        }

                        $insert_sesiones = "INSERT INTO consultas_fisioterapeuta(paciente_id, numero_sesiones, paquete_id, total_sesiones, estado, id_veta) values($id_paciente,$numero_sesiones, $item_id, $numero_sesiones, 'pendiente', $venta_id)";
                        $mysqli->query($insert_sesiones);

                        $paquete_detalle_sql = "SELECT pro_ser_id, cantidad FROM paquete_detalle WHERE paquete_id = $item_id and  tipo = 'Producto' ";
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

            if (!$error) {
                // Todas las operaciones fueron exitosas, confirmar la transacción
                $mysqli->commit();


                echo "<script>
                        
                    Swal.fire({
                        title: 'Compra generada',
                        text: 'Compra generada correctamente!',
                        icon: 'success',
                    }).then((result) => {
                        if (result.isConfirmed) {
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
    }

    if ($error) {
        // Revertir la transacción en caso de error
        $mysqli->rollback();
    }
}


?>
<html>

<body>
    <section class="cuerpo">
        <h2>Nueva Venta</h2>
        <form id="compraForm" method="POST">
            <div class="row">
                <!-- <div class="col-md-4">
                    <label for="fecha_venta">Fecha:</label>
                    <input class="form-control" type="date" id="fecha_venta" name="fecha_venta" required />
                </div> -->
                <div class="col-md-8">
                    <label for="id_paciente">Paciente:</label>

                    <?php

                    $sql = "SELECT * FROM `pacientes`";

                    $result = $mysqli->query($sql);

                    ?>
                    <select class="select2 form-control" data-rel="chosen" id='id_paciente' name='id_paciente' required>
                        <option value="" selected="" hidden="">Seleccione el Paciente</option>
                        <?php
                        if ($result) {
                            while ($fila = mysqli_fetch_array($result)) {
                                $selected = '';
                                if (isset($_GET['id_paciente']) && $_GET['id_paciente'] == $fila["id"]) {
                                    $selected = 'selected';
                                }
                                echo "<option value='{$fila["id"]}' {$selected}>{$fila["numero_identidad"]}  {$fila["nombres"]}  {$fila["apellidos"]}</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <!-- <div class="col-md-4">
                    <label for="proveedor">Usuario:</label>
                    <input type="text" name="usuario" id="usuario" required>
                </div> -->


            </div>


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
            <select class="form-control" style="width: 20%;" title="Género" id="tipo_pago" name="tipo_pago" required>
                <option value="" selected="" hidden="">Tipo Pago</option>
                <option value="efectivo">Efectivo</option>
                <option value="transferencia">Tranferencia</option>
                <option value="tarjeta">Tarjeta Débito o Crédito</option>
            </select>
            <label for="total">Total:</label>
            <input class="form-control" style="width: 20%;" type="text" id="total" name="total" readonly><br>
            <button class="btn btn-success" style="width: 20%;" type="submit"><i class="fa-regular fa-floppy-disk"></i> Guardar Venta</button>
        </form>

    </section>
    <?php
    include 'footer.php';
    ?>

    <script type="text/javascript">
        $('.select2').select2({});
    </script>
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

            function buscar_paciente() {
                var numero_id = $("#numero_identidad").val();
                $.ajax({
                    url: 'buscar_paciente.php',
                    type: 'post',
                    data: {
                        numero_identidad: numero_id
                    },
                    success: function(response) {
                        $("#resultado_paciente").html(response);
                        $('input[name="id_paciente"]').val($("#id_paciente_resultado").val());
                    }
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
            <select class="select2 form-control item_id" data-rel="chosen" name="detalles[item_id][]">
                <option value="" selected>Seleccionar</option>
            </select>
        </td>
        <td><input type="number" step="1" min="1" name="detalles[cantidad][]" class="cantidad form-control" /></td>
        <td><input name="detalles[precio][]" class="precio form-control" readonly/></td>
        <td><label name="detalles[subtotal][]" class="subtotal form-control" readonly/> 0.00</td>
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

                        if (target.classList.contains("tipo_item")) {
                            // Si cambia el tipo_item, restablecer la opción seleccionada en item_id
                            itemSelect.value = ""; // Establecer como "Seleccionar producto"
                        }

                        if (target.classList.contains("item_id")) {
                            const selectedType = tipoItemSelect.value;
                            const selectedItemId = itemSelect.value;

                            if (selectedItemId) {
                                obtenerPrecioDesdeBaseDeDatos(selectedType, selectedItemId, (precio) => {
                                    precioInput.value = precio;
                                    updateSubtotal();
                                });
                            }
                        }
                    }
                });




                // Configurar la función de cambio de tipo y actualización de subtotal
                const tipoItemSelectNew = newRow.querySelector(".tipo_item");
                const itemSelectNew = newRow.querySelector(".item_id");

                tipoItemSelectNew.addEventListener("change", function() {
                    itemSelectNew.innerHTML = "";

                    // Agrega una opción "Seleccionar producto" que esté seleccionada por defecto
                    const defaultOption = document.createElement("option");
                    defaultOption.value = "";
                    defaultOption.text = "Seleccionar";
                    defaultOption.selected = true;
                    itemSelectNew.appendChild(defaultOption);
                    const selectedType = tipoItemSelectNew.value;

                    // Llamada AJAX para obtener los datos de la base de datos
                    fetch(`get_items.php?tipo_item=${selectedType}`)
                        .then((response) => response.json())
                        .then((data) => {
                            data.forEach((item) => {
                                const option = document.createElement("option");
                                option.value = item.id;
                                option.textContent = `(${item.sesiones}) - ${item.nombre} - $${item.total}`;
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