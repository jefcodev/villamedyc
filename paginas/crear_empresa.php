<?php
include 'header.php';
include '../conection/conection.php';
$class = '';

?>

<body>
    <section class="cuerpo">
        <div class="row">
            <div class="col-md-6">
                <h2>Crear Empresa</h2><br>
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">


                    <input class="form-control" title="Nombre" placeholder="Nombre" id="nombre" name="nombre" required />

                    <input class="btn btn-primary" type="submit" value="Crear Empresa">
                    <a class="btn btn-danger" href="lista_pacientes.php">Cancelar</a>




                </form>
           
            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                $nombre = $_POST['nombre'];
                $sql = "INSERT INTO empresa (nombre) VALUES ('$nombre')";

                if ($mysqli->query($sql)) {
                    echo '<div class="alert alert-success" role="alert">Empresa creada correctamente.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error al crear la empresa: ' . $mysqli->error . '</div>';
                }
            }
            ?>
             </div>
            <div class="col-md-6">
                <h2>Listado de Empresas</h2>

                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta para obtener las empresas
                        $query = "SELECT id, nombre FROM empresa";
                        $result = $mysqli->query($query);

                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>';
                            echo '<td>' . $row['id'] . '</td>';
                            echo '<td>' . $row['nombre'] . '</td>';
                            echo '</tr>';
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
</body>