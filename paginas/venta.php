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

        <h1>Venta</h1>

        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="id_pacient">Pacientes:</label>
                    <select class="select2 form-control" data-rel="chosen" id='paciente' name='paciente'>
                        <option value="0">Seleccione el Paciente</option>
                        <?php
                        $sql = "SELECT * FROM `pacientes`";
                        $result = $mysqli->query($sql);
                        while ($fila = mysqli_fetch_array($result)) {
                            echo "<option value='" . $fila["id"] . "'>" . $fila['numero_identidad'] . " " .  $fila['nombres'] . "  " . $fila['apellidos'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="paquete">Paquetes:</label>
                    <select class="form-control" id="paquete" name="paquete">
                        <option value="0">Seleccione Paquete</option>
                        <?php
                        $sql_traer_doctor = "SELECT * FROM paquete_cabecera";
                        $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                        while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                            echo "<option value='" . $row['paquete_id'] . "' data-total='" . $row['total'] . "'  data-tipo='" . $row['tipo_paquete'] . "' data-sesiones='" . $row['numero_sesiones'] . "'>" . $row['titulo_paquete'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12">
                <div class="d-flex justify-content-center">
                    <div class="form-group">
                        <div class="d-flex">
                            <b>
                                <p class="p-1">Tipo paquete:</p>
                            </b>
                            <p id="tipo_paquete" class="p-1"></p>
                        </div>
                        <div class="d-flex">
                            <b>
                                <p class="p-1">Numero de sesiones:</p>
                            </b>
                            <p id="numero_sesiones" class="p-1"></p>
                        </div>
                        <div class="d-flex">
                            <b>
                                <p class="p-1">Precio paquete:</p>
                            </b>
                            <p id="total" class="p-1"></p>
                        </div>
                    </div>

                </div>

                <div class="col-md-12" id="tabla_productos"></div>
                <div class="d-flex justify-content-center">
                    <input class="btn btn-primary" type="button" name="crear_venta" id="crear_venta" value="Aceptar" />
                </div>
            </div>
    </section>
    <script type="text/javascript">
        $('.select2').select2({});
    </script>
    <?php
    include 'footer.php';
    ?>
    <script>
        $("#paquete").on("change", function() {
            var selectedValue = $(this).val();
            if (selectedValue != 0) {
                var selectedOption = $(this).find("option:selected");
                var tipo_paquete = selectedOption.attr("data-tipo");
                var numero_sesiones = selectedOption.attr("data-sesiones");
                var total = selectedOption.attr("data-total");

                let tipo = "";
                if (tipo_paquete === '1') {
                    tipo = "Básico";
                }
                if (tipo_paquete === '2') {
                    tipo = "Plus";
                }
                if (tipo_paquete === '3') {
                    tipo = "Premium";
                }
                if (tipo_paquete === '4') {
                    tipo = "Empresas";
                }
                if (tipo_paquete === '5') {
                    tipo = "Convenio";
                }
                $('#tipo_paquete').html(tipo);
                $('#numero_sesiones').html(numero_sesiones);
                $('#total').html(total);

                verificarStock();
            } else {
                $('#tipo_paquete').html("");
                $('#numero_sesiones').html("");
                $('#total').html("");
            }
        });

        let productos = [];
        let listaProdutos = [];

        function verificarStock() {
            limpiar();
            const FD = new FormData();
            FD.append('action', "ver_productos");
            FD.append('paquete_id', $('#paquete').val());
            fetch("ventas_ajax.php", {
                    method: 'POST',
                    body: FD
                }).then(respuesta => respuesta.text())
                .then(decodificado => {
                    // console.log(decodificado);
                    const data = JSON.parse(decodificado);
                    console.log(data);
                    let template = `<table class="table table-bordered table-hover">
                                            <thead class="tabla_cabecera">
                                                <tr>
                                                    <th>Nombre Producto</th>
                                                    <th>Cantidad</th>
                                                    <th>Stock Disponible</th>
                                                </tr>
                                            </thead>
                                            <tbody>`;
                    data.forEach(datos => {
                        if (Number(datos.stock) < Number(datos.cantidad)) {
                            productos.push(datos);
                            template += `<tr>
                                            <td> ${datos.nombre} </td>
                                            <td> ${datos.cantidad} </td>
                                            <td> ${datos.stock} </td>
                                        </tr>`;
                        }
                        listaProdutos.push(datos);
                    });
                    template += `</tbody>
                                    </table>`;
                    if (productos.length > 0) {
                        $('#tabla_productos').html(template);
                    }
                })
                .catch(function(error) {
                    console.log('Hubo un problema con la petición Fetch: ' + error.message);
                });
        }

        function limpiar() {
            productos = [];
            listaProdutos = [];
            $('#tabla_productos').html("");
        }

        $('#crear_venta').on('click', function() {
            if (validar()) {
                const FD = new FormData();
                FD.append('action', "crear_venta");
                FD.append('paciente_id', $('#paciente').val())
                FD.append('paquete_id', $('#paquete').val());
                FD.append('total', $('#total').text());
                FD.append('lista', JSON.stringify(listaProdutos))
                fetch("ventas_ajax.php", {
                        method: 'POST',
                        body: FD
                    }).then(respuesta => respuesta.text())
                    .then(decodificado => {
                        console.log(decodificado);
                        alert(decodificado);
                        // var alertElement = document.getElementById('alert-success');
                        // alertElement.classList.remove('d-none');
                        // setTimeout(function() {
                        //     alertElement.classList.add('d-none');
                        // }, 3000);
                        location.reload();
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
            if ($('#paciente').val() == 0) {
                validator = false;
                $('#alert-danger').html('Seleccione Paciente');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if ($('#paquete').val() < 1) {
                validator = false;
                $('#alert-danger').html('Seleccione Paquete');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if (productos.length > 0) {
                validator = false;
                $('#alert-danger').html('Productos sin stock');
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