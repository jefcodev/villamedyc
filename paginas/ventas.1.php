<?php
//include 'header.php';
include '../conection/conection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha_venta = $_POST['fecha_venta'];
    $id_paciente = $_POST['id_paciente'];
    $usuario = $_POST['usuario'];
    $total = $_POST['total'];

    $detalles = $_POST['detalles'];

    // Insertar la cabecera de la venta
    $insert_cabecera_sql = "INSERT INTO ventas_cabecera (fecha_venta, id_paciente, usuario, total) VALUES ('$fecha_venta', '$id_paciente', '$usuario', $total)";
    $mysqli->query($insert_cabecera_sql);
    $venta_id = $mysqli->insert_id;

    foreach ($detalles['tipo_item'] as $i => $tipo_item) {
        $item_id = $detalles['item_id'][$i];
        $cantidad = $detalles['cantidad'][$i];
        $precio_unitario = $detalles['precio'][$i];
        $subtotal = $cantidad * $precio_unitario;

        $insert_detalle_sql = "INSERT INTO ventas_detalle (venta_id, tipo_item, item_id, cantidad, precio_unitario, subtotal)
        VALUES ($venta_id, '$tipo_item', $item_id, $cantidad, $precio_unitario, $subtotal)";

        if ($mysqli->query($insert_detalle_sql)) {

            if ($tipo_item === 'productos') {
                $update_stock_sql = "UPDATE productos SET stock = stock - $cantidad WHERE id = $item_id";

                if ($mysqli->query($update_stock_sql)) {
                    // Actualización de stock exitosa
                } else {
                    echo "Error en la actualización de stock: " . $mysqli->error;
                }
            } 
            elseif ($tipo_item === 'paquete') {
                // Obtener los productos asociados a la cabecera del paquete
                $paquete_detalle_sql = "SELECT pro_ser_id, cantidad FROM paquete_detalle WHERE paquete_id = $item_id and  tipo = 'Producto' " ;
                $paquete_detalle_result = $mysqli->query($paquete_detalle_sql);

                if ($paquete_detalle_result) {
                    while ($row = $paquete_detalle_result->fetch_assoc()) {
                        $producto_id = $row['pro_ser_id'];
                        $cantidad_paquete = $row['cantidad'];

                        $update_stock_sql = "UPDATE productos SET stock = stock - ($cantidad * $cantidad_paquete) WHERE id = $producto_id";

                        if ($mysqli->query($update_stock_sql)) {
                            // Actualización de stock exitosa
                        } else {
                            echo "Error en la actualización de stock: " . $mysqli->error;
                        }
                    }
                } else {
                    echo "Error al obtener los detalles del paquete: " . $mysqli->error;
                }
            }

            // Inserción exitosa
        } else {
            echo "Error en la inserción: " . $mysqli->error;
        }
    }


    echo json_encode(array('success' => true, 'message' => 'Venta guardada exitosamente.'));
    exit;
}
?>

<html>

<head>
    <title>Maestro-Detalle Compras</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <section class="cuerpo">
        <h2>Nueva Compra</h2>
        <form id="compraForm" method="POST">
            <div class="row">
                <div class="col-md-4">
                    <label for="fecha_venta">Fecha:</label>
                    <input class="form-control" type="date" id="fecha_venta" name="fecha_venta" required />
                </div>
                <div class="col-md-4">
                    <label for="id_paciente">Paciente:</label>
                    <select name="id_paciente" id="id_paciente">
                        <option value="">Seleccionar paquete</option>
                        <?php
                        $sql_paciente = "SELECT id, nombres, apellidos FROM pacientes";
                        $result_paciente = $mysqli->query($sql_paciente);

                        while ($row_paciente = mysqli_fetch_array($result_paciente)) {
                            echo "<option value='" . $row_paciente['id'] . "'>" . $row_paciente['nombres'] . '  $' . $row_paciente['apellidos'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="proveedor">Usuario:</label>
                    <input type="text" name="usuario" id="usuario" required>
                </div>


            </div>


            <br>
            <table class="table table-bordered table-hover" id="detalleTable">
                <thead class="tabla_cabecera">
                    <tr>
                        <th style="width: 20%">Tipo</th>
                        <th style="width: 30%">Item</th>
                        <th style="width: 20%">Cantidad</th>
                        <th style="width: 10%">Precio</th>
                        <th style="width: 10%">Subtotal</th>
                        <th style="width: 10%">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filas de detalle se agregarán aquí -->
                </tbody>
            </table>
            <button class="btn btn-primary" type="button" id="addDetalle"> <i class="fa-solid fa-plus"></i> Agregar Producto</button><br><br>
            <br>
            <label for="total">Total:</label>
            <input class="form-control" style="width: 20%;" type="text" id="total" name="total" readonly><br>
            <button class="btn btn-success" style="width: 20%;" type="submit"><i class="fa-regular fa-floppy-disk"></i> Guardar Compra</button>
        </form>

    </section>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addDetalleButton = document.getElementById("addDetalle");
            const detalleTableBody = document.querySelector("#detalleTable tbody");
            const totalInput = document.getElementById("total");


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

            addDetalleButton.addEventListener("click", () => {
                const newRow = document.createElement("tr");
                newRow.innerHTML = `
                <td>
                    <select name="detalles[tipo_item][]" class="tipo_item">
                        <option value="">Seleccionar</option>
                        <option value="producto">Producto</option>
                        <option value="servicio">Servicio</option>
                        <option value="paquete">Paquete</option>
                    </select>
                </td>
                <td>
                    <select  name="detalles[item_id][]" class="item_id">
                    
                        <!-- Las opciones se cargarán dinámicamente aquí -->
                    </select>
                </td>
                <td><input type="number" step="1" name="detalles[cantidad][]" class="cantidad" /></td>
                <td><input type="number" step="0.01"  name="detalles[precio][]" class="precio" readonly/></td>
                <td name="detalles[subtotal][]" class="subtotal">0.00</td>
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
                                option.textContent = item.nombre;
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