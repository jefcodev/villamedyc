<!DOCTYPE html>
<html>
<?php
include 'header.php';
include '../conection/conection.php';
$conn = $mysqli;

// Obtener la lista de productos para mostrar en el formulario
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

// Procesar el formulario de compra
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $fecha = $_POST["fecha"];
    $proveedor = $_POST["proveedor"];
    $detalle = $_POST["detalle"];

    // Insertar la cabecera de la compra
    $sql = "INSERT INTO compra_cabecera (fecha, proveedor) VALUES ('$fecha', '$proveedor')";
    if ($conn->query($sql) === TRUE) {
        $cabecera_id = $conn->insert_id;

        // Insertar el detalle de la compra
        $precioTotal = 0;

        for ($i = 0; $i < count($detalle['producto']); $i++) {
            $producto = $detalle['producto'][$i];
            $cantidad = $detalle['cantidad'][$i];

            // Verificar si el producto está eliminado
            if (!isset($productos[$producto])) {
                continue;
            }

            // Obtener el precio y stock del producto
            $precio = $productos[$producto]['precio'];
            $stock_actual = $productos[$producto]['stock'];

            // Calcular el subtotal
            $subtotal = $cantidad * $precio;
            $precioTotal += $subtotal;

            // Actualizar el stock del producto
            $nuevo_stock = $stock_actual + $cantidad;
            $sql = "UPDATE productos SET stock = $nuevo_stock WHERE id = '$producto'";
            $conn->query($sql);

            // Insertar el detalle de la compra
            $sql = "INSERT INTO compra_detalle (cabecera_id, producto_codigo, cantidad, precio) VALUES ($cabecera_id, '$producto', $cantidad, $precio)";
            $conn->query($sql);
        }

        // Actualizar el precio total de la compra
        $sql = "UPDATE compra_cabecera SET total = $precioTotal WHERE id = $cabecera_id";
        $conn->query($sql);

        echo '<div class="alert alert-success">Compra registrada correctamente</div>';
    } else {
        echo "Error al registrar la compra: " . $conn->error;
    }
}

// Cerrar la conexión
$conn->close();
?>

<body>
    <section class="cuerpo">
        <h1>Registrar Compra</h1>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="fecha">Fecha:</label>
            <input type="date" id="fecha" name="fecha" required><br><br>

            <label for="proveedor">Proveedor:</label>
            <input type="text" id="proveedor" name="proveedor" required><br><br>

            <label for="producto">Producto:</label>
            <select id="producto" name="producto">
                <option value="">Seleccionar</option>
                <?php foreach ($productos as $codigo => $producto) { ?>
                    <option value="<?php echo $codigo; ?>" data-precio="<?php echo $producto['precio']; ?>"><?php echo $producto['nombre']; ?></option>
                <?php } ?>
            </select><br><br>

            <label for="cantidad">Cantidad:</label>
            <input type="number" id="cantidad" name="cantidad[]" min="0" value="0"><br><br>

            <button type="button" onclick="agregarProducto()">Agregar Producto</button><br><br>

            <h2>Detalle de la Compra</h2>
            <table class="table table-bordered" id="tablaDetalle">
                <tr class="tabla_cabecera">
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                    <th>Subtotal</th>
                    <th>Acción</th>
                </tr>
            </table><br>

            <label for="precioTotal">Precio Total:</label>
            <input type="text" id="precioTotal" name="precioTotal" readonly value="0.00"><br><br>

            <input type="submit" value="Registrar Compra">
        </form>
    </section>
    <script>
        function agregarProducto() {
            var productoSelect = document.getElementById("producto");
            var cantidadInput = document.getElementById("cantidad");

            if (productoSelect.value && cantidadInput.value > 0) {
                var tablaDetalle = document.getElementById("tablaDetalle");
                var row = tablaDetalle.insertRow(-1);
                var cellProducto = row.insertCell(0);
                var cellCantidad = row.insertCell(1);
                var cellPrecio = row.insertCell(2);
                var cellSubtotal = row.insertCell(3);

                var productoOption = productoSelect.options[productoSelect.selectedIndex];
                var precio = parseFloat(productoOption.getAttribute("data-precio"));
                var cantidad = parseInt(cantidadInput.value);
                var subtotal = precio * cantidad;

                cellProducto.innerHTML = productoOption.text;
                cellCantidad.innerHTML = cantidad;
                cellPrecio.innerHTML = precio.toFixed(2);
                cellSubtotal.innerHTML = subtotal.toFixed(2);

                var eliminarButton = document.createElement("button");
                eliminarButton.type = "button";
                eliminarButton.innerText = "Eliminar";
                eliminarButton.onclick = function() {
                    eliminarProducto(row);
                };
                row.insertCell(4).appendChild(eliminarButton);

                var inputHiddenProducto = document.createElement("input");
                inputHiddenProducto.type = "hidden";
                inputHiddenProducto.name = "detalle[producto][]";
                inputHiddenProducto.value = productoSelect.value;

                var inputHiddenCantidad = document.createElement("input");
                inputHiddenCantidad.type = "hidden";
                inputHiddenCantidad.name = "detalle[cantidad][]";
                inputHiddenCantidad.value = cantidad;

                row.appendChild(inputHiddenProducto);
                row.appendChild(inputHiddenCantidad);

                actualizarPrecioTotal(subtotal);

                productoSelect.value = "";
                cantidadInput.value = "0";
            }
        }

        function eliminarProducto(row) {
            var tablaDetalle = document.getElementById("tablaDetalle");
            var subtotal = parseFloat(row.cells[3].innerHTML);
            tablaDetalle.deleteRow(row.rowIndex);
            actualizarPrecioTotal(-subtotal);
        }

        function actualizarPrecioTotal(subtotal) {
            var precioTotalInput = document.getElementById("precioTotal");
            var precioTotal = parseFloat(precioTotalInput.value);
            precioTotal += subtotal;
            precioTotalInput.value = precioTotal.toFixed(2);
        }
    </script>
</body>
</html>
