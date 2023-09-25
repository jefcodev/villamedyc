<!DOCTYPE html>
<html>

<?php
include 'header.php';
include '../conection/conection.php';


$conn = $mysqli;
// Obtener la lista de productos para mostrar en el formulario
$sql = "SELECT id, codigo, nombre, descripcion, precio_c, precio_v, stock FROM productos";
$result = $conn->query($sql);
$productos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productos[$row["id"]] = [
            "codigo_1" => $row["codigo"],
            "nombre" => $row["nombre"],
            "precio" => $row["precio_v"],
            "stock" => $row["stock"],
			"descripcion" => $row["descripcion"]
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
    
        $sqlServicio = "INSERT INTO servicios (titulo_servicio, total, valor_adicional) VALUES ('$tituloServicio', $totalPago, $valorAdicional)";
    

    // Insertar en la tabla servicios
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

<body>
    <section class="cuerpo">
        <h1 class="text-center">Crear Servicio</h1><br>
        <div class="row">
            <div class="col-md-4">
                <h4>Agregar Detalles del servicio</h4>
                <input type="text" name="titulo_servicio" id="titulo_servicio" placeholder="Ingrese nombre del servicio" class="form-control">
            </div>
            <!-- <div class="col-md-4">
                <label>
                    <input type="checkbox" id="asociar_sesiones" name="asociar_sesiones">
                </label>
                <label for="sesiones">Asociar Sesiones:</label>
                <input type="number" id="valor_sesiones" name="valor_sesiones" min="1" class="form-control" disabled>
            </div>
            <div class="col-md-4">
            </div> -->
        </div>
        <div class="row">
            <div class="col-md-6">
                <label>
                    <input type="checkbox" id="asociar_producto" name="asociar_producto">
                    Asociar productos:
                </label>
                <select id="producto" name="producto" class="select2 form-control" disabled>
                    <option value="">Seleccionar</option>
                    <?php foreach ($productos as $codigo => $producto) { ?>
                        <option value="<?php echo $codigo; ?>" data-precio="<?php echo $producto['precio']; ?>"><?php echo "(" . $producto['codigo_1'] . ") " . $producto['nombre'] .  ' - '.$producto['descripcion']. " - $" . $producto['precio']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="cl-md-4">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" min="1" class="form-control" disabled>
            </div>
           
            <div class="cl-md-4"  >
                <br>
                
                
                <button id="agregar_producto" class="btn btn-primary">Agregar</button>
            </div>

        </div>

        <br>
        <table class="table">
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
            <tbody id="tabla_productos_body"></tbody>
        </table>
        <br>
        <div class="row">
            <div class="col-md-12">
                <h4>Detalle del Pago</h4>
            </div><br><br>
            <div class="col-md-6">
                <label for="total_pago">Total:</label>
                <span id="total_pago">0.00</span>
            </div>
            <div class="col-md-6">
                <label for="valor_adicional">Precio Total:</label>
                <input type="number" id="valor_adicional" name="valor_adicional" step="0.01" min="1" class="form-control" require>
            </div>
            <div class="col-md-12">
                <button id="crear_paquete" class="btn btn-primary">Crear Servicio</button>
            </div>
        </div>
    </section>
    <?php
    include 'footer.php';
    ?>

    <script type="text/javascript">
        $('.select2').select2({});

        $('#asociar_producto').on('change', function() {
            if ($(this).prop('checked')) {
                $('#producto').prop('disabled', false);
                $('#cantidad').prop('disabled', false);
            } else {
                $('#producto').prop('disabled', true);
                $('#cantidad').prop('disabled', true);
            }
        });

       /*  $('#asociar_sesiones').on('change', function() {
            if ($(this).prop('checked')) {
                $('#valor_sesiones').prop('disabled', false);
            } else {
                $('#valor_sesiones').prop('disabled', true);
            }
        }); */
    </script>

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
                        Swal.fire({
                            icon: 'info',
                            title: 'Oops...',
                            text: 'El producto ya se encuentra en la lista!'
                        })

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

                if ($.trim(tituloServicio) !== '' && !isNaN(valorAdicional)) {
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

                            Swal.fire({
                                icon: 'success',
                                title: 'Servicio creado',
                                text: 'El servicio se creo correctamente!'
                            })


                            // Limpiar la tabla y los campos
                            productosSeleccionados = [];
                            totalPago = 0;
                            actualizarTablaProductos();
                            actualizarTotalPago();

                            $('#titulo_servicio').val('');
                            $('#cantidad').val('');
                            $('#valor_adicional').val('');
                        },
                        error: function() {
                            alert('Ha ocurrido un error al crear el servicio');
                        }
                    });

                } else {

                    Swal.fire({
                        icon: 'error',
                        title: 'Oops..',
                        text: 'Ingrese el nombre o el valor total!'
                    })
                }



            });

        });
    </script>

</body>

</html>