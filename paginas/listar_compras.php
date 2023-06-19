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
</head>
<body>
   
    <section class="cuerpo">
         <h1>Listado de Compras
        
        </h1>
        
        
        <div id="mensajes" <?php echo $class; ?> >
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
                            <th>Fecha</th>
                            <th>Proveedor</th>
                            <th>Precio Total</th>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_citas = "SELECT * from compra_cabecera";
                        $result_citas = $mysqli->query($sql_citas);

                        while ($row = mysqli_fetch_array($result_citas)) {
                            echo "<tr>";
                            echo "<td>" . $row['fecha'] . "</td>";
                            echo "<td>" . $row['proveedor'] . "</td>";
                            echo "<td>" . $row['total'] . "</td>";}
                            
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
        $(document).ready(function () {
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
        $(document).ready(function () {
            setTimeout(function () {
                $("#mensajes").fadeOut(1500);
            }, 2500);
        });
    </script>
</body>
