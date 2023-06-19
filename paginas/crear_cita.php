<!DOCTYPE html>
<html>
<?php
include 'header.php';
include '../conection/conection.php';
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha256-aAr2Zpq8MZ+YA/D6JtRD3xtrwpEz2IqOS+pWD/7XKIw=" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha256-OFRAJNoaD8L3Br5lglV7VyLRf0itmoBzWUoM+Sji4/8=" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
</head>
<header class="encabezado">
            <div class="container">
                <nav class="navbar navbar-expand-md navbar-light fixed-top">
                    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="inicio.php"><img src="../img/logo.png" width="150"></a>
                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                        <ul class="navbar-nav mr-auto mt-2 mt-md-0">
                            <li class="nav-item">
                                <a class="nav-link" href="inicio.php">INICIO</a>
                            </li>
                            <li class="nav-item"><a class="nav-link" href="lista_pacientes.php">PACIENTES</a></li>                       
                            <li class="nav-item"><a class="nav-link" href="lista_citas.php">CITAS</a></li>                     
                            <li class="nav-item"><a class="nav-link" href="lista_consultas.php">CONSULTAS</a></li> 
                            <li class="nav-item"><a class="nav-link" href="lista_historias.php">HISTORIAS</a></li>   
                            <?php if ($rol == 'adm') { ?>
                                <li class="nav-item"><a class="nav-link" href="lista_usuarios.php">USUARIOS</a></li> 
                            <?php } ?>
                        </ul>
                        <ul class="navbar-nav mt-md-0 margen-float-right"> 
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?php echo $usuario; ?>
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="editar_cuenta_usuario.php">Editar mis datos</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="logout.php">Salir</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </nav>                    
            </div>                
        </header>
<body>
    <div class="row mt-5">






        <section class="cuerpo">
            <div id="mensajes" <?php echo $class; ?>>
                <?php echo isset($error) ? $error : ''; ?>
            </div>
            <h1>Crear Cita</h1><br>
            <div class="row">
                <div class="col-md-12">
                    <b style="color: #28a745">Buscar un paciente por CI, Nombre o Apellido </b><br><br>
                </div>
                
            </div>
            <div class="row">

                <div class="col-md-12">
                    <form action="adm_citas.php" method="post">
                        <div class="row">
                            <div class="col-md-12">


                                <?php

                                $sql = "SELECT * FROM `pacientes`";

                                $result = $mysqli->query($sql);

                                ?>
                                <select class="select2 form-control" data-rel="chosen" id='id_paciente' name='id_paciente'>
                                    <option value="" selected="" hidden="">Seleccione el Paciente</option>
                                    <?php
                                    // $resultado = "";
                                    // while ($row = $result->fetch_assoc()) {
                                    //     echo "<option>"
                                    //         . "CI: " . $row["numero_identidad"] . "   " . $row["nombres"] .
                                    //         " " . $row["apellidos"] .

                                    //         "</option>";
                                    //     $id_paciente = ["id_paciente"];
                                    //     $resultado = $resultado . "<input type='hidden' id='id_paciente_resultado' name='id_paciente_resultado' value='" . $row['id'] . "'/>";
                                    // }
                                    if ($result) {
                                        while ($fila = mysqli_fetch_array($result)) {
                                    ?>
                                            <option value="<?php echo $fila["id"] ?>"><?php echo    $fila["numero_identidad"] . "  " .  $fila["nombres"] . "  " . $fila["apellidos"] ?></option>
                                    <?php
                                        }
                                    }

                                    ?>
                                </select>
                                    <br><br>
                               
                                <select class="form-control" id="doctor" name="doctor" required>
                                    <option value="" selected="" hidden="">Seleccione el Doctor</option>
                                    <?php
                                    $sql_traer_doctor = "SELECT * FROM usuarios WHERE rol = 'doc'";
                                    $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                                    while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . ' ' . $row['apellidos'] . "</option>";
                                    }
                                    ?>
                                </select>

                                <!-- <input type='hidden' id='id_paciente' name='id_paciente'/> -->
                                <input class="form-control" type="text" autocomplete="off" placeholder="Fecha y hora de la cita" id="fecha_cita" name="fecha_cita" required /><br>
                            </div>
                            
                        </div>
                        
                        <div class="row">
                            <div class="col-md-4">
                                <input class="btn btn-primary" type="submit" name="btn_crear_cita" id="btn_crear_cita" value="Aceptar" />
                            </div>
                        </div>
                    </form>
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
    <script language="javascript" src="../js/jquery.datetimepicker.full.min.js"></script>
    <script>
        $('#fecha_cita').datetimepicker({
            startDate: new Date(),
            value: new Date(),
            step: 15,
            minDate: 0,
            minTime: '06:00',
            maxTime: '20:00',
            dayOfWeekStart: 0,
            disabledWeekDays: [0, 6],
            closeOnDateSelect: false,
            closeOnTimeSelect: true,
            onSelectTime: function(ct) {
                validarDisponibilidadHorarios();
            }
        });
        $.datetimepicker.setLocale('es');
        $(document).ready(function() {
            setTimeout(function() {
                $("#mensajes").fadeOut(1500);
            }, 2500);
        });

        function buscar_paciente() {
            var numero_id = $("#numero_identidad").val();
            $.ajax({
                url: 'buscar_paciente.php',
                type: 'post',
                data: {
                    numero_identidad: numero_id
                },
                success: function(response) {
                    $("#resultado_paciente").html(response);
                    $('input[name="id_paciente"]').val($("#id_paciente_resultado").val());
                }
            });
        }

        function validar() {
            var numero_identidad = document.getElementById('numero_identidad').value;
            var miDiv = document.getElementById('miDiv');
            var html = "";
            if (numero_identidad === "") {
                document.getElementById("miDiv").style.display = 'block';
                miDiv.innerHTML = ""; //innerHTML te añade código a lo que ya haya por eso primero lo ponemos en blanco.
                html = "No puede dejar el campo Cédula o Pasaporte vacío, debe antes de crear la cita buscar al paciente.";
                miDiv.innerHTML = html;
                return false;
            }
        }
        $('#doctor').change(function() {
            validarDisponibilidadHorarios();
        });

        function validarDisponibilidadHorarios() {
            var fecha_cita = $("#fecha_cita").val();
            var doctor = $("#doctor").val();
            $.ajax({
                url: 'consultar_disponibilidad_horarios.php',
                type: 'post',
                data: {
                    fecha_cita: fecha_cita,
                    doctor: doctor,
                },
                success: function(response) {
                    if ('' === response) {
                        var miDiv = document.getElementById('miDiv');
                        var html = "";
                        document.getElementById("miDiv").style.display = 'block';
                        miDiv.innerHTML = ""; //innerHTML te añade código a lo que ya haya por eso primero lo ponemos en blanco.
                        html = "La fecha seleccionada no está disponible, por favor seleccione otro horario.";
                        miDiv.innerHTML = html;
                        $("#fecha_cita").datetimepicker('show')
                            .datetimepicker('reset');
                    }
                }
            });
        }
    </script>
</body>

</html>