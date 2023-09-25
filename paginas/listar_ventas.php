<?php
include '../conection/conection.php';
include 'header.php';
$pagina = PAGINAS::LISTA_USUARIOS;
if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
    header("location:./inicio.php?status=AD");
}

?>

<head>
    <title>Lista de Ventas</title>
    <link href="../css/search.min.css" rel="stylesheet">
</head>

<body>
    <section class="cuerpo">
        <h1>Listado de Ventas
            <a href="reporte_ventas.php?fecha_inicio=<?php echo $_GET['fecha_inicio']; ?>&fecha_fin=<?php echo $_GET['fecha_fin']; ?>&id_user=<?php echo $_GET['id_user']; ?>" target="_blank" class="btn btn-primary float-right">
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
                    <label for="id_user">Usuario:</label>
                    <select name="id_user" class="form-control">
                        <option value="">Todos</option> <!-- Opción para mostrar todos los usuarios -->
                        <?php
                        // Consulta para obtener la lista de usuarios
                        $sql_usuarios = "SELECT id, nombre, apellidos  FROM usuarios";
                        $result_usuarios = $mysqli->query($sql_usuarios);

                        while ($row_usuario = mysqli_fetch_array($result_usuarios)) {
                            $selected = ($_GET['id_user'] == $row_usuario['id']) ? 'selected' : '';
                            echo "<option value='" . $row_usuario['id'] . "' $selected>" . $row_usuario['nombre'] . ' ' . $row_usuario['apellidos'] . "</option>";
                        }
                        ?>
                    </select>
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
                            <th>Usuario</th>
                            <th>Descuento</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (isset($_GET['fecha_inicio']) && isset($_GET['fecha_fin'])) {
                            $fechaInicio = $_GET['fecha_inicio'];
                            $fechaFin = $_GET['fecha_fin'];
                            $usuarioFiltro = $_GET['id_user'];

                            $sql_citas = "SELECT * FROM ventas_cabecera WHERE fecha_venta BETWEEN '$fechaInicio' AND '$fechaFin'";

                            // Agregar condición para filtrar por usuario si se selecciona uno
                            if (!empty($usuarioFiltro)) {
                                $sql_citas .= " AND id_user = '$usuarioFiltro'";
                            }

                            $result_citas = $mysqli->query($sql_citas);

                            while ($row = mysqli_fetch_array($result_citas)) {
                                echo "<tr>";
                                echo "<td>" . $row['fecha_venta'] . "</td>";
                                echo "<td>" . $row['id_user'] .  "</td>";
                                echo "<td>" . $row['descuento'] . ' ' . $row['apellido_doctor'] . "</td>";
                                echo "<td>" . $row['total'] . "</td>";
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