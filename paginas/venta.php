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

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP MySQL Select2 Example</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <title>Clínica</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <!--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">-->
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
    <style>
        .floating-alert {
            position: fixed;
            top: 100px;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>

</head>

<body>
    <div class="row mt-5">
        <section class="cuerpo">
            <div id="mensajes" <?php echo $class; ?>>
                <?php echo isset($error) ? $error : ''; ?>
            </div>

            <div class="d-flex justify-content-center">
                <div class="alert alert-primary d-none floating-alert" id="alert-primary" role="alert">
                    Este es un mensaje de información.
                </div>

                <div class="alert alert-success d-none floating-alert" id="alert-success" role="alert">
                    Venta realizada correctamente.
                </div>

                <div class="alert alert-danger d-none floating-alert" id="alert-danger" role="alert">
                    Este es un mensaje de error.
                </div>
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
                                    echo "<div id='numero_historia'>VM-001-1</div>";
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
                                <option value="0">Seleccione el Paciente</option>
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
                            <select class="form-control" id="doctor" name="doctor">
                                <option value="0">Seleccione Paquete</option>
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
    <script type="text/javascript">
        $('.select2').select2({});
    </script>
    <?php
    include 'footer.php';
    ?>
    <script>
        $('#crear_venta').on('click', function() {
            if (validar()) {
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
                        var alertElement = document.getElementById('alert-success');
                        alertElement.classList.remove('d-none');
                        setTimeout(function() {
                            alertElement.classList.add('d-none');
                        }, 3000);
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            }
        });

        function validar() {
            let validator = true;
            var alertElement = document.getElementById('alert-danger');
            var duracion = 3000;
            if ($('#id_paciente').val() == 0) {
                validator = false;
                $('#alert-danger').html('Seleccione Paciente');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if ($('#doctor').val() < 1) {
                validator = false;
                $('#alert-danger').html('Seleccione Paquete');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            return validator;
        }
    </script>
</body>

</html>