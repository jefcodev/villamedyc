<!DOCTYPE html>
<html>
<?php
include 'header.php';
$status = $_GET['status'];
$class = '';
if (isset($status)) {
    if ($status === 'OK') {
        $error = 'Cita creada correctamente';
        $class = 'class="alert alert-success"';
    } else {
        $error = 'Ocurrió un error al crear la cita';
        $class = 'class="alert alert-danger"';
    }
}
?>

<body>
    <div class="row mt-5">
        <section class="cuerpo">
            <div id="mensajes" <?php echo $class; ?>>
                <?php echo isset($error) ? $error : ''; ?>
            </div>
            <h1>Venta</h1><br>
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div>
                            <div>
                                Historia Clinica
                            </div>
                            <?php
                            $sql = "SELECT MAX(consulta_fisio_id) as id FROM `consultas_fisioterapeuta`";
                            $result = $mysqli->query($sql);
                            if ($result) {
                                $fila = mysqli_fetch_array($result);
                                if (!isset($fila[0])) {
                                    echo "<div id='numero_historia'>VM-001-001</div>";
                                } else {
                                    echo "<div id='numero_historia'>VM-001-" . $fila[0] + 1 . "</div>";
                                }
                            }
                            ?>
                        </div>
                        <div class="col-md-12">
                            <?php
                            $sql = "SELECT * FROM `pacientes`";
                            $result = $mysqli->query($sql);
                            ?>
                            <select class="select2 form-control" data-rel="chosen" id='id_paciente' name='id_paciente'>
                                <option value="" selected="" hidden="">Seleccione el Paciente</option>
                                <?php
                                if ($result) {
                                    while ($fila = mysqli_fetch_array($result)) {
                                ?>
                                        <option value="<?php echo $fila["id"] ?>"><?php echo    $fila["numero_identidad"] . "  " .  $fila["nombres"] . "  " . $fila["apellidos"] ?></option>
                                <?php
                                    }
                                }

                                ?>
                            </select><br>
                            <select class="form-control" id="doctor" name="doctor" required>
                                <option value="" selected="" hidden="">Seleccione Paquete</option>
                                <?php
                                $sql_traer_doctor = "SELECT * FROM paquete_cabecera";
                                $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                                while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                                    echo "<option value='" . $row['paquete_id'] . "'>" . $row['titulo_paquete'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input class="btn btn-primary" type="button" name="crear_venta" id="crear_venta" value="Aceptar" />
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-8">
                    <div id="miDiv" class="alert alert-danger" role="alert" style="display: none"></div>
                </div>
            </div>
        </section>
    </div>
    <?php
    include 'footer.php';
    ?>
    <script language="javascript" src="../js/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('#crear_venta').on('click', function() {
            const FD = new FormData();
            FD.append('action', "crear_venta");
            FD.append('paciente_id', $('#id_paciente').val())
            FD.append('paquete_id', $('#doctor').val());
            FD.append('numero_historia', $('#numero_historia').text());
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    console.log(decodificado);
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
            // }
        });
    </script>
</body>

</html>