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

// Insertar los datos en la base de datos al enviar el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tituloServicio = $_POST["titulo_servicio"];
    $productosSeleccionados = $_POST["productos_seleccionados"];
    $totalPago = $_POST["total_pago"];
    $valorAdicional = $_POST["valor_adicional"];

    // Insertar en la tabla servicios
    $sqlServicio = "INSERT INTO servicios (total, valor_adicional) VALUES ($totalPago, $valorAdicional)";
    if ($conn->query($sqlServicio) === TRUE) {
        $idServicio = $conn->insert_id;

        // Insertar en la tabla detalle_servicio
        foreach ($productosSeleccionados as $producto) {
            $idProducto = $producto["id"];
            $nombreProducto = $producto["nombre"];
            $precioProducto = $producto["precio"];
            $cantidadProducto = $producto["cantidad"];
            $subtotal = $precioProducto * $cantidadProducto;

            $sqlDetalle = "INSERT INTO deatelle_servicio (id_servicio, id_producto, nombre, precio, cantidad, subtotal) VALUES ($idServicio, $idProducto, '$nombreProducto', $precioProducto, $cantidadProducto, $subtotal)";
            $conn->query($sqlDetalle);
        }

        echo "El servicio se ha creado correctamente.";
    } else {
        echo "Error al crear el servicio: " . $conn->error;
    }
}
?>

<head>
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            var productosSeleccionados = [];
            var totalPago = 0;

            function actualizarTablaProductos() {
                var tablaProductosBody = $('#tabla_productos_body');
                tablaProductosBody.empty();

                productosSeleccionados.forEach(function(producto, index) {
                    var fila = $('<tr>');
                    fila.append($('<td>').text(producto.id));
                    fila.append($('<td>').text(producto.nombre));
                    fila.append($('<td>').text(producto.precio.toFixed(2)));
                    fila.append($('<td>').text(producto.cantidad));
                    fila.append($('<td>').text(producto.subtotal.toFixed(2)));
                    fila.append($('<td>').html('<button class="eliminar-producto" data-index="' + index + '">Eliminar</button>'));

                    tablaProductosBody.append(fila);
                });
            }

            function actualizarTotalPago() {
                $('#total_pago').text(totalPago.toFixed(2));
            }

            $('#agregar_producto').click(function() {
                var selectedOption = $('#producto').find('option:selected');
                var id = selectedOption.val();
                var nombre = selectedOption.text();
                var precio = parseFloat(selectedOption.data('precio'));
                var cantidad = parseInt($('#cantidad').val());

                if (id && nombre && precio && cantidad) {
                    // Verificar si el producto ya está en la lista
                    var productoExistente = productosSeleccionados.find(function(producto) {
                        return producto.id == id;
                    });

                    if (productoExistente) {
                        alert('El producto ya se encuentra en la lista.');
                    } else {
                        var producto = {
                            id: id,
                            nombre: nombre,
                            precio: precio,
                            cantidad: cantidad,
                            subtotal: precio * cantidad
                        };

                        productosSeleccionados.push(producto);
                        totalPago += producto.subtotal;

                        actualizarTablaProductos();
                        actualizarTotalPago();
                    }
                }
            });

            $('#tabla_productos_body').on('click', '.eliminar-producto', function() {
                var index = $(this).data('index');
                var producto = productosSeleccionados[index];
                productosSeleccionados.splice(index, 1);
                totalPago -= producto.subtotal;

                actualizarTablaProductos();
                actualizarTotalPago();
            });

            $('#crear_paquete').click(function() {
                var tituloServicio = $('#titulo_servicio').val();
                var valorAdicional = parseFloat($('#valor_adicional').val());

                if (tituloServicio && productosSeleccionados.length > 0) {
                    $.ajax({
                        url: 'crear_servicio.php',
                        method: 'POST',
                        data: {
                            titulo_servicio: tituloServicio,
                            productos_seleccionados: productosSeleccionados,
                            total_pago: totalPago,
                            valor_adicional: valorAdicional
                        },
                        success: function(response) {
                            alert('El servicio se ha creado correctamente');

                            // Limpiar la tabla y los campos
                            productosSeleccionados = [];
                            totalPago = 0;
                            actualizarTablaProductos();
                            actualizarTotalPago();
                            $('#titulo_servicio').val('');
                            $('#valor_adicional').val(0);
                        },
                        error: function() {
                            alert('Ha ocurrido un error al crear el servicio');
                        }
                    });
                } else {
                    alert('Debe ingresar un título de servicio y seleccionar al menos un producto');
                }
            });

        });
    </script>
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
        <h1 class="text-center">Crear Servicio</h1><br>
        <div class="col-10">
            <div class="col-md-12">
                <h4>Agregar Detalles del servicio</h4>
            </div><br><br>
            <div class="col-md-6">
                <input type="text" name="titulo_servicio" id="titulo_servicio" placeholder="Ingrese nombre del servicio" class="form-control">
            </div>
            <div class="col-md-6">
                <label for="producto">Producto:</label>
                <select id="producto" name="producto" class="select2 form-control">
                    <option value="">Seleccionar</option>
                    <?php foreach ($productos as $codigo => $producto) { ?>
                        <option value="<?php echo $codigo; ?>" data-precio="<?php echo $producto['precio']; ?>"><?php echo $producto['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" value="1" min="1" class="form-control">
            </div>
            <div class="col-md-12">
                <button id="agregar_producto" class="btn btn-primary">Agregar</button>
            </div>
        </div>
        <br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Cantidad</th>
                    <th>Subtotal</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tabla_productos_body"></tbody>
        </table>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h4>Detalle del Pago</h4>
            </div><br><br>
            <div class="col-md-6">
                <label for="total_pago">Total a pagar:</label>
                <span id="total_pago">0.00</span>
            </div>
            <div class="col-md-6">
                <label for="valor_adicional">Valor Adicional:</label>
                <input type="number" id="valor_adicional" name="valor_adicional" step="0.01" min="0" value="0" class="form-control">
            </div>
            <div class="col-md-12">
                <button id="crear_paquete" class="btn btn-primary">Crear Servicio</button>
            </div>
        </div>
    </section>

    <script type="text/javascript">
        $('.select2').select2({});
    </script>
</body>

</html>