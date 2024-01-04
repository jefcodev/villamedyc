<?php
include 'header.php';
include '../conection/conection.php';

$id_venta = $_GET['id_venta'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Manejar la actualización de la venta principal si es necesario
    // ...

    // Luego, manejar la actualización de los detalles o la adición de nuevos detalles
    // ...

    // Actualizar el estado de pago a "pagado" en la cabecera de la venta
    $update_query = "UPDATE ventas_cabecera SET estado = 'pagado' WHERE id = $id_venta";
    $update_result = $mysqli->query($update_query);
    $update_query_up = "UPDATE consultas_fisioterapeuta SET estado = 'pagado' WHERE id_veta = $id_venta";
    $mysqli->query($update_query_up);

    if ($update_result) {
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
             
             var pdfUrl = 'comprobante_venta.php?venta_id=' + $id_venta; 

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

        exit();
    } else {
        // La actualización falló, puedes mostrar un mensaje de error
        echo "Error al actualizar el estado de pago.";
    }
}


// Consultar la información actual de la venta y sus detalles
$venta_query = "  SELECT vc.*, p.nombres, p.apellidos
FROM ventas_cabecera vc
LEFT JOIN pacientes p ON vc.id_paciente = p.id
WHERE vc.id = $id_venta";



$venta_result = $mysqli->query($venta_query);
$venta = mysqli_fetch_assoc($venta_result);

$detalles_query = "SELECT * FROM ventas_detalle WHERE venta_id = $id_venta";
$detalles_result = $mysqli->query($detalles_query);
?>

<html>

<head>
    <title>Editar Venta</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <section class="cuerpo">
        
       

        <!-- Muestra la lista de detalles actuales -->
        <div class="detalles">
            <h3>Detalles de la Venta</h3>
            <table class="table table-bordered table-hover" id="detalleTable">
                <thead class="tabla_cabecera">
                    <tr>
                        <th>Tipo</th>
                        <th>Item</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($detalle = mysqli_fetch_assoc($detalles_result)) {
                        $tipo_item = $detalle['tipo_item'];
                        $id_item =  $detalle['item_id'];

                        echo '<tr>';
                        echo '<td>' . $tipo_item . '</td>';

                        
                        if ($tipo_item === 'producto') {
                            $sql_item = "SELECT id, CONCAT (nombre , ' - ' , descripcion)  as nombre , codigo as sesiones , CONCAT (precio_v, ' - Stock ', stock) as total  FROM productos WHERE id = $id_item";
                        } elseif ($tipo_item === 'servicio') {
                            $sql_item = "SELECT id_servicio as id, titulo_servicio as nombre , sesiones, valor_adicional as total FROM servicios WHERE id_servicio = $id_item";
                        } elseif ($tipo_item === 'paquete') {
                            $sql_item = "SELECT paquete_id as id, titulo_paquete as nombre, numero_sesiones as sesiones, ahorra as total FROM paquete_cabecera WHERE paquete_id = $id_item";
                        }

                        $result_item = $mysqli->query($sql_item);

                     
                        $row_item = mysqli_fetch_assoc($result_item);

                        echo '<td>' . $row_item['nombre']. '</td>';
                        echo '<td>' . $detalle['cantidad'] . '</td>';
                        echo '<td>' . $detalle['precio_unitario'] . '</td>';
                        echo '<td>' . $detalle['subtotal'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

         <!-- Muestra la información principal de la venta -->
        
         <div class="venta-info">
            <p>Fecha de Venta: <?php echo $venta['fecha_venta']; ?></p>
            <p>Cliente: <?php echo $venta['nombres']. ' '. $venta['apellidos']; ?></p>
            <p>Total: $<?php echo $venta['total']; ?></p>
            <p>Descuento: $<?php echo $venta['descuento']; ?></p>
            <p>Tipo de Pago: <?php echo $venta['tipo_pago']; ?></p>
        </div>

        <!-- Formulario para editar o agregar detalles -->
        <form id="compraForm" method="POST">
            <!-- Campos para editar o agregar detalles -->
            <!-- ... -->

            <!-- Botón de guardar cambios -->
            <button class="btn btn-primary" type="submit">Cobrar Cambios</button>
            
        </form>
        <br>
        <button class="btn btn-danger" id="cancelarVenta">Cancelar Venta</button>


    </section>
    <?php
    include 'footer.php';
    ?>

    <!-- JavaScript para manejar la edición o adición de detalles -->
    <script>
        $(document).ready(function () {
    // Agregar un evento de clic al botón de cancelar venta
    $('#cancelarVenta').click(function () {
        // Puedes mostrar un cuadro de confirmación para confirmar la cancelación
        Swal.fire({
            title: 'Cancelar Venta',
            text: '¿Estás seguro de que deseas cancelar la venta?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, Cancelar',
            cancelButtonText: 'No',
        }).then((result) => {
            if (result.isConfirmed) {
                // Realizar la cancelación de la venta y la actualización del stock
                $.ajax({
                    type: 'POST',
                    url: 'cancelar_venta.php', // Crea un archivo PHP para manejar la cancelación y actualización del stock
                    data: { id_venta: <?php echo $id_venta; ?> }, // Envía el ID de la venta
                    success: function (response) {
                        // Procesar la respuesta del servidor (puedes mostrar un mensaje de éxito, por ejemplo)
                        Swal.fire({
                            title: 'Venta Cancelada',
                            text: 'La venta ha sido cancelada',
                            icon: 'success'
                        });
                        // Puedes redirigir o realizar otras acciones después de la cancelación
                    },
                    error: function () {
                        // Manejar errores en la solicitud AJAX
                        Swal.fire({
                            title: 'Error',
                            text: 'Ha ocurrido un error al cancelar la venta.',
                            icon: 'error'
                        });
                    }
                });
            }
        });
    });
});

    </script>
</body>

</html>
