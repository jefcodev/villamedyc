<!DOCTYPE html>
<html>
<?php
include 'header.php';
include '../conection/conection.php';
$class = '';
$close = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fecha = $_POST['fecha'];
    $proveedor = $_POST['proveedor'];
    $num_factura = $_POST['num_factura'];
    $total = $_POST['total'];
    $productos = $_POST['producto'];
    $precios = $_POST['precio'];
    $cantidades = $_POST['cantidad'];

    $insertCabecera = "INSERT INTO compra_cabecera (fecha, proveedor, num_factura, total) VALUES ('$fecha', '$proveedor', '$num_factura', $total)";
    $mysqli->query($insertCabecera);
    $cabeceraId = $mysqli->insert_id;

    for ($i = 0; $i < count($productos); $i++) {
        $productoId = $productos[$i];
        $precio = $precios[$i];
        $cantidad = $cantidades[$i];
        $insertDetalle = "INSERT INTO compra_detalle (cabecera_id, producto_codigo, precio_c, cantidad) VALUES ($cabeceraId, $productoId, $precio, $cantidad)";
        $mysqli->query($insertDetalle);

        // Actualizar el stock del producto
        $updateStock = "UPDATE productos SET stock = stock + $cantidad WHERE id = $productoId";
        $mysqli->query($updateStock);
    }

    echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'Compra Agregada',
                text: 'La compra ha sido registrada exitosamente.',
                showConfirmButton: false,
                timer: 1500
            });
        </script>";
}
?>

<body>
    <section class="cuerpo">
        <h2>Nueva Compra</h2>
        <form id="compraForm" method="POST">
            <div class="row">
                <div class="col-md-4">
                    <label for="fecha">Fecha:</label>
                    <input class="form-control" type="date" id="fecha" name="fecha" required />
                </div>
                <div class="col-md-4">
                    <label for="proveedor">Proveedor:</label>
                    <input class="form-control" placeholder="Proveedor" id="proveedor" name="proveedor" required />
                </div>
                <div class="col-md-4">
                    <label for="num_factura">Número de comprobante:</label>
                    <input class="form-control" placeholder="Número de comprobante" id="num_factura" name="num_factura" required />
                </div>
            </div>
           
            <br>
            <table class="table table-bordered table-hover" id="detalleTable">
                <thead class="tabla_cabecera">
                    <tr>
                        <th style="width: 40%">Producto</th>
                        <th style="width: 20%">Precio Compra</th>
                        <th style="width: 20%">Cantidad</th>
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
    
    <?php
    include 'footer.php';
    ?>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const addDetalleButton = document.getElementById('addDetalle');
            const detalleTableBody = document.querySelector('#detalleTable tbody');
            const totalInput = document.getElementById('total');
            let total = 0;

            addDetalleButton.addEventListener('click', () => {
                const newRow = document.createElement('tr');
                newRow.innerHTML = `
            <td>
                <select class=" form-control producto" name="producto[]">
                    <!-- Opciones de productos se agregarán aquí -->
                </select>
            </td>
            <td><input type="number" step="0.01" class="form-control precio" name="precio[]"></td>
            <td><input type="number" step="0.01" class="form-control cantidad" name="cantidad[]"></td>
            <td class="subtotal">0.00</td>
            <td><button type="button" class="btn btn-danger deleteRow"><i class="fa-solid fa-trash-can"></i></button></td>
        `;
                detalleTableBody.appendChild(newRow);

                // Cargar opciones de productos
                const productosSelect = newRow.querySelector('.producto');
                <?php
                include '../conection/conection.php';
                $query = "SELECT id, codigo, nombre, descripcion FROM productos";
                $result = $mysqli->query($query);
                while ($row = $result->fetch_assoc()) {
                    echo "productosSelect.innerHTML += '<option value=\"{$row['id']}\">{$row['nombre']} - ({$row['codigo']}) - {$row['descripcion']} </option>';\n";
                }
                ?>
            });

            detalleTableBody.addEventListener('input', updateSubtotal);
            detalleTableBody.addEventListener('click', handleDeleteRow);

            function updateSubtotal() {
                total = 0;
                const detalleRows = detalleTableBody.querySelectorAll('tr');
                detalleRows.forEach(row => {
                    const precio = parseFloat(row.querySelector('.precio').value);
                    const cantidad = parseInt(row.querySelector('.cantidad').value);
                    const subtotal = precio * cantidad;
                    row.querySelector('.subtotal').textContent = subtotal.toFixed(2);
                    total += subtotal;
                });
                totalInput.value = total.toFixed(2);
            }

            function handleDeleteRow(event) {
                if (event.target.classList.contains('deleteRow')) {
                    const row = event.target.closest('tr');
                    row.remove();
                    updateSubtotal();
                }
            }
        });
    </script>

</body>
</html>