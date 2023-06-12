<?php
include 'header.php';
$pagina = PAGINAS::LISTA_USUARIOS;
if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
    header("location:./inicio.php?status=AD");
}
?>

<head>
    <title>Lista de usuarios</title>
    <link href="../css/search.min.css" rel="stylesheet">
</head>

<body>
    <section class="cuerpo">
        <h1>Listado de Paquetes</h1>
        <div class="row">
            <div class="col-md-12">
                <a href="crear_paquetes.php" class="btn btn-success float-right"> Crear nuevo paquete</a>
                <br><br>
                <table class="table table-bordered table-hover" id="indexusuarios">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Nombre Paquete</th>
                            <th>Tipo Paquete</th>
                            <th>Nº Sesiones</th>
                            <th>Total</th>
                            <th style="width: 125px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_usuarios = "SELECT * from paquete_cabecera";
                        $result_usuarios = $mysqli->query($sql_usuarios);
                        while ($row = mysqli_fetch_array($result_usuarios)) {
                            echo "<tr>";
                            echo "<td>" . $row['CABECERA_ID'] . "</td>";
                            echo "<td>" . $row['USUARIO_ID'] . " " . $row['apellidos'] . "</td>";
                            echo "<td>" . $row['NUMERO_HISTORIA'] . "</td>";
                            echo "<td>" . $row['TOTAL'] . "</td>";
                            echo "<td>
                            <a class='btn btn-primary btn-sm ml-1' href='historia_clinica.php?idusuario=" . $row['id'] . "'><i style='font-size:18px' class='fas fa-eye'></i></a>
                                    <a class='btn btn-success btn-sm ml-1' href='editar_usuario.php?idusuario=" . $row['id'] . "'><i class='fas fa-edit table-icon'></i></a>
                                    <a class='btn btn-danger btn-sm ml-1'><i class='fas fa-trash-alt table-icon'></i></a>
                                </td>";
                            echo "</tr>";
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
            $('#indexusuarios').DataTable({
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
    </script>
</body>