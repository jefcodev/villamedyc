<?php
include 'header.php';
include '../conection/conection.php';

$pagina = PAGINAS::LISTA_USUARIOS;
if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
    header("location: ./inicio.php?status=AD");
    exit();
}

$id_consulta = $_GET['id_cita'];
$totalConsulta = 0;
$totalPaquete = 0;


$sql_paciente = "SELECT id_paciente, nombre_doctor, apellidos_doctor  from consultas_datos where id_consulta = $id_consulta";

$resultado = ($mysqli->query($sql_paciente)) ->fetch_assoc();

$id_paciente = $resultado['id_paciente'];
$user_doctor = $resultado['nombre_doctor'] .' '.$resultado['apellidos_doctor'];
$estado = false ;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    date_default_timezone_set('America/Guayaquil');
    $fecha_venta = date("Y-m-d H:i:s");
    // $id_paciente = $_POST['id_paciente'];
    //$usuario = $_POST['usuario'];
    $total = $_POST['total'];

    $detalles = $_POST['detalles'];

    // Insertar la cabecera de la venta
    $insert_cabecera_sql = "INSERT INTO ventas_cabecera (fecha_venta,id_consulta ,id_paciente, usuario, total) VALUES ('$fecha_venta','$id_consulta', '$id_paciente', '$user_doctor', $total)";
    $mysqli->query($insert_cabecera_sql);
    $venta_id = $mysqli->insert_id;

    $error = false; // Variable para rastrear errores

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
                    $error = true;
                    echo "Error en la actualización de stock: " . $mysqli->error;
                }
            } elseif ($tipo_item === 'paquete') {
                // Obtener los productos asociados a la cabecera del paquete
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

            // Inserción exitosa
        } else {
            $error = true;
            echo "Error en la inserción: " . $mysqli->error;
        }

    }

    $actualizarEstado = "UPDATE consultas SET estado = 'pagado' WHERE id = $id_consulta";
    $mysqli->query($actualizarEstado);
    if (!$error) {
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
                
               
                }
                else {
                    window.location.href = 'inicio.php';
                }
                
              }
            );
              
            
        </script>";


    
        
    exit; // Asegúrate de que no haya más salida después de la redirección

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
            <button class="btn btn-primary" type="button" id="addDetalle"> <i class="fa-solid fa-plus"></i> Agregar Item</button><br><br>
            <br>
            <label for="total">Total:</label>
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

           /*  function buscar_paciente() {
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
            } */

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
                <td><input type="number" step="1" name="detalles[cantidad][]" class="cantidad form-control" /></td>
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
                                option.textContent = "  (" + item.sesiones + ") - " + item.nombre + " - $" + item.total ;
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