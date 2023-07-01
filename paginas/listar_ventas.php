<?php
include 'header.php';
$pagina = PAGINAS::LISTA_USUARIOS;
if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
    header("location:./inicio.php?status=AD");
}
if (isset($status)) {
    $close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>';
    if ($status === 'OK') {
        $error = 'Producto creado correctamente';
        $class = 'class="alert alert-success alert-dismissible fade show" role="alert"';
    } else {
        $error = 'Ocurrió un error al crear el producto';
        $class = 'class="alert alert-danger alert-dismissible fade show" role="alert"';
    }
}
?>

<head>
    <title>Lista de Ventas</title>
    <link href="../css/search.min.css" rel="stylesheet">
</head>

<body>
    <section class="cuerpo">
        <h1>Listado de Ventas
            <a href="reporte_ventas.php?fecha_inicio=<?php echo $_GET['fecha_inicio']; ?>&fecha_fin=<?php echo $_GET['fecha_fin']; ?>" target="_blank" class="btn btn-primary float-right">
                <i class="fas fa-file-pdf"></i> Crear reporte
            </a>

        </h1>

        <div id="mensajes" <?php echo $class; ?>>
            <?php echo isset($error) ? $error : ''; ?>
            <?php echo $close; ?>
        </div>

        <form method="GET" action="">
            <div class="row">
                <div class="col-md-3">
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" name="fecha_inicio" class="form-control" required value="<?php echo isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <label for="fecha_fin">Fecha de fin:</label>
                    <input type="date" name="fecha_fin" class="form-control" required value="<?php echo isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : ''; ?>">
                </div>
                <div class="col-md-3">
                    <br>
                    <button type="submit" class="btn btn-primary">Filtrar</button>
                </div>
            </div>
        </form>

        <div class="row">
            <div class="col-md-12">
                <table class="table table-bordered table-hover" id="indexpacientes">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Fecha Venta</th>
                            <th>Cédula</th>
                            <th>Paciente</th>
                            <th>Doctor</th>
                            <th>Precio Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
                            $fechaInicio = $_GET['fecha_inicio'];
                            $fechaFin = $_GET['fecha_fin'];

                            $sql_citas = "SELECT * FROM consulta_ventas WHERE fecha_hora BETWEEN '$fechaInicio' AND '$fechaFin'";
                            $result_citas = $mysqli->query($sql_citas);

                            while ($row = mysqli_fetch_array($result_citas)) {
                                echo "<tr>";
                                echo "<td>" . $row['fecha_hora'] . "</td>";
                                echo "<td>" . $row['cedula'] . "</td>";
                                echo "<td>" . $row['apellidos_pacientes'].' '.$row['nombre_paciente'] .  "</td>";
                                echo "<td>" . $row['nombre_doctor'].' ' .$row['apellido_doctor'] ."</td>";
                                echo "<td>" . $row['valor_pagado'] . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>
    <br>

    <?php
    include 'footer.php';
    ?>

    <script src="../js/jquerysearch.js"></script>
    <script>
        $(document).ready(function() {
            $('#indexpacientes').DataTable({
                language: {
                    sProcessing: "Procesando...",
                    sLengthMenu: "Mostrar _MENU_ registros",
                    sZeroRecords: "No se encontraron resultados",
                    sEmptyTable: "Ningún dato disponible en esta tabla",
                    sInfo: "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
                    sInfoEmpty: "Mostrando registros del 0 al 0 de un total de 0 registros",
                    sInfoFiltered: "(filtrado de un total de _MAX_ registros)",
                    sInfoPostFix: "",
                    sSearch: "Buscar:",
                    sUrl: "",
                    sInfoThousands: ",",
                    sLoadingRecords: "Cargando...",
                    oPaginate: {
                        sFirst: "Primero",
                        sLast: "Último",
                        sNext: "Siguiente",
                        sPrevious: "Anterior"
                    },
                    oAria: {
                        sSortAscending: ": Activar para ordenar la columna de manera ascendente",
                        sSortDescending: ": Activar para ordenar la columna de manera descendente"
                    }
                }
            });
        });

        $(document).ready(function() {
            setTimeout(function() {
                $("#mensajes").fadeOut(1500);
            }, 2500);
        });
    </script>
</body>

</html>