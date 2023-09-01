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
?>

<body>
    <section class="cuerpo">
        <h2>Agregar Paquete</h2>
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
        <div class="row">
            <div class="col-md-6">
                <h4>Listado de Paquetes</h4>
                <select class="form-control" id="lista-paquetes">
                    <option value="">Seleccionar paquete</option>
                    <?php
                    $sql_paquetes = "SELECT paquete_id, titulo_paquete, total FROM paquete_cabecera";
                    $result_paquetes = $mysqli->query($sql_paquetes);

                    while ($row_paquete = mysqli_fetch_array($result_paquetes)) {
                        echo "<option value='" . $row_paquete['paquete_id'] . "'>" . $row_paquete['titulo_paquete'] . '  $' . $row_paquete['total'] . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <h4>Acciones</h4>
                <a class='btn btn-success btn-sm cobrar-btn' href='' data-id-consulta='<?php echo $id_consulta; ?>'>Cobrar Paquete</a>
                <a class='btn btn-success btn-sm' href='inicio.php'>Cancelar</a>
            </div>
        </div>



        <div class="row">
            <div class="col-md-6">
                <table class="table table-bordered table-hover" id="tabla-paquete">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Nombre</th>
                            <th>Sesiones</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody id="detalles-paquete">
                        <!-- Aquí se mostrarán los detalles del paquete seleccionado -->
                    </tbody>
                </table>
                <h5>Total del paquete más consultas: $<span id="total-paquete"></span></h5>
            </div>
            <div class="col-md-6">
                <table class="table table-bordered table-hover" id="tabla-detalle">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody id="detalles-paquete-items">
                        <!-- Aquí se mostrarán los detalles del paquete seleccionado -->
                    </tbody>
                </table>
            </div>
        </div>

    </section>

    <br>
    <?php include 'footer.php'; ?>

    <script>
        $(document).ready(function() {
            $('#lista-paquetes').change(function() {
                var paqueteId = $(this).val();

                // Realizar una petición AJAX para obtener los detalles del paquete y el valor total
                $.ajax({
                    url: 'obtener_detalles_paquete.php',
                    type: 'POST',
                    data: {
                        paqueteId: paqueteId
                    },
                    success: function(response) {
                        var data = JSON.parse(response);
                        var precioPaquete = parseFloat(data.precio_paquete);
                        //var valorTotal = parseFloat(data.valor_total);
                        var sumaTotal = precioPaquete;

                        // Mostrar la tabla de detalles del paquete
                        $('#tabla-paquete').show();

                        // Actualizar el contenido de los detalles del paquete
                        $('#detalles-paquete').html(data.detalles_paquete);

                        // Mostrar la tabla de detalles del paquete
                        $('#tabla-detalle').show();

                        // Actualizar el contenido de los detalles del paquete
                        $('#detalles-paquete-items').empty().append(data.detalles_paquete_items);

                        // Actualizar el valor total del paquete más consultas
                        var total = sumaTotal + <?php echo $totalConsulta; ?>;
                        $('#total-paquete').text(total);
                    }
                });
            });

            $('.cobrar-btn').click(function(e) {
                e.preventDefault();
               

                // Obtener el ID de la consulta y el valor total del paquete más consultas
                var idConsulta = $(this).data('id-consulta');
                var totalPaquete = parseFloat($('#total-paquete').text());
                var paqueteId = parseInt($('#lista-paquetes').val()); // Obtener el valor del paquete seleccionado


                // Realizar una petición AJAX para insertar los datos en la tabla de ventas
                $.ajax({
                    url: 'insertar_venta.php',
                    type: 'POST',
                    data: {
                        idConsulta: idConsulta,
                        totalPaquete: totalPaquete,
                        paqueteId: paqueteId

                    },
                    success: function(response) {
                

                        // Aquí puedes manejar la respuesta de la inserción de la venta si es necesario
                    }
                });
                window.location.href = 'inicio.php';
            });
        });
    </script>
</body>