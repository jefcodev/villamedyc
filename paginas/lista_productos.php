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
    <title>Lista de productos</title>
    <link href="../css/search.min.css" rel="stylesheet">
    <style>
        .clearfix {
            clear: both;
            margin-bottom: 10px;
            /* Ajusta el valor del margen según tus necesidades */
        }
    </style>
</head>

<body>

    <section class="cuerpo">
        <h1>Listado de Productos</h1>
        <a href="reporte_stock.php" target="_blank" class="btn btn-primary float-right">
            <i class="fas fa-file-pdf"></i> Crear reporte
        </a>
        <?php
        $permisoCrear = Seguridad::tiene_permiso($rol, $pagina, ACCIONES::CREAR);
        if ($permisoCrear) {
            echo '<div class="clearfix"></div>'; // Agregamos un div vacío para limpiar el float
            echo '<a href="crear_producto.php" class="btn btn-success float-right"> Crear nuevo producto</a><br><br>';
        } else {
            echo '<div class="clearfix"></div>'; // Agregamos un div vacío para limpiar el float
            echo '<a href="crear_producto.php" class="btn btn-success float-right"> Crear nuevo producto</a><br><br>';
        }
        ?>



        <div id="mensajes" <?php echo $class; ?>>
            <?php echo isset($error) ? $error : ''; ?>
            <?php echo $close; ?>
        </div>
        <div class="row">

            <div class="col-md-12">

                <!--<a href="crear_paciente.php" class="btn btn-success float-right"> Crear nuevo paciente</a><br><br>
                -->




                <table class="table table-bordered table-hover" id="indexpacientes">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Código</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Precio compra</th>
                            <th>Precio venta</th>
                            <th>Stock</th>
                            <th style="width: 93px">Acciones</th>

                            <?php
                            /*  if ($permisoEdicion) {
                                echo '<th style="width: 93px">Acciones</th>';
                            } */
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_citas = "SELECT * from productos";
                        $result_citas = $mysqli->query($sql_citas);

                        while ($row = mysqli_fetch_array($result_citas)) {
                            echo "<tr>";
                            echo "<td>" . $row['codigo'] . "</td>";
                            echo "<td>" . $row['nombre'] . "</td>";
                            echo "<td>" . $row['descripcion'] . "</td>";
                            echo "<td>" . $row['precio_c'] . "</td>";
                            echo "<td>" . $row['precio_v'] . "</td>";
                            echo "<td>" . $row['stock'] . "</td>";
                            echo "<td><a class='btn btn-success btn-sm' href='editar_producto.php?id_producto=" . $row['id'] . "'><i class='fas fa-edit table-icon'></i></a></td>";


                            //if ($permisoEdicion) {
                            //  echo "<td><a class='btn btn-success btn-sm' //href='editar_paciente.php?id_paciente=" . //$row['id'] . "'><i class='fas fa-edit table//-icon'></i></a></td>";
                            //}
                            //echo "</tr>";
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