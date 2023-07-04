<?php
include 'header.php';
$pagina = PAGINAS::LISTA_HISTORIAS;
?>

<head>
    <title>Lista de historias</title>
    <link href="../css/search.min.css" rel="stylesheet">
</head>

<body>
    <section class="cuerpo">
        <h1>Listado de Historias Clínicas</h1>
        <div class="row">
            <div class="col-md-12"><br><br>
                <?php
                $permisoVer = Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER);
                ?>
                <table class="table table-bordered table-hover" id="indexhistoriah">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Nº Historia</th>
                            <th>Identificación</th>
                            <th>Nombres</th>
                            <th>Estado</th>
                            <th>Paquete</th>
                            <th>Nº Sesiones</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_citas = "SELECT cf.consulta_fisio_id, p.id as paciente_id, cf.estado_atencion, pc.titulo_paquete, pc.numero_sesiones, pc.total, p.numero_identidad, CONCAT(p.nombres, ' ', p.apellidos) as nombres 
                                        FROM consultas_fisioterapeuta cf, pacientes p, paquete_cabecera pc
                                        WHERE cf.paciente_id = p.id AND pc.paquete_id = cf.paquete_id";
                        $result_citas = $mysqli->query($sql_citas);

                        while ($row = mysqli_fetch_array($result_citas)) {
                            echo "<tr>";
                            echo "<td>VM-001-" . $row['paciente_id'] . "</td>";
                            echo "<td>" . $row['numero_identidad'] . "</td>";
                            echo "<td>" . $row['nombres'] . "</td>";
                            echo "<td>" . $row['estado_atencion'] . "</td>";
                            echo "<td>" . $row['titulo_paquete'] . "</td>";
                            echo "<td>" . $row['numero_sesiones'] . "</td>";
                            echo "<td>
                                    <a class='btn btn-success btn-sm ml-1' href='evaluacion_paciente.php?consulta_fisio_id=" . $row['consulta_fisio_id'] . "' ><i class='fas fa-edit table-icon'></i></a>
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
            $('#indexhistoriah').DataTable({
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