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
            <h1>Crear Paquete</h1><br>
            <div class="row">

                <div class="col-md-12">
                    <form action="adm_citas.php" method="post">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <b style="color: #28a745">Seleccione Servicios</b><br><br>
                                    </div>
                                </div>

                                <select class="select2 form-control" data-rel="chosen" name="" id="servicios">
                                    <!-- <option value="" selected="" hidden="">Agregar Servicios</option> -->
                                    <option value="1">Displacia de cadera</option>
                                    <option value="2">Pie plano</option>
                                    <optgroup label="Tumores óseos">
                                        <option value="3">Artrosis de rodilla</option>
                                        <option value="4">Hernias discales</option>
                                        <option value="5">Tratamientos con células madre</option>
                                        <option value="6">Tratamientos para artrosis, enfermedades degenerativas, osteomusculares y osteoarticulares</option>
                                        <option value="7">Reemplazo Articulares</option>
                                        <option value="8">Tratamientos para el adulto</option>
                                        <option value="9">Esguinces</option>
                                        <option value="10">Fracturas</option>
                                        <option value="11">Artrosis</option>
                                        <option value="12">Dolores crónicos</option>
                                        <option value="13">Fortalecimiento pulmonar</option>
                                        <option value="14">Enfermedades crónicas respiratorias</option>
                                        <option value="15">Pacientes post-COVID</option>
                                        <option value="16">Mejora de calidad del sueño</option>
                                        <option value="17">Ayuda a la concentración y memoria</option>
                                        <option value="18">Elimina contracturas musculares</option>
                                        <option value="19">Incontinecia urinaria</option>
                                        <option value="20">Prolapsos</option>
                                        <option value="21">Dolores en relaciones sexuales</option>
                                        <option value="22">Eyaculación precoz</option>
                                        <option value="23">Problemas de erección</option>
                                        <option value="24">Programa de hidroterapia personalizado</option>
                                    </optgroup>
                                    <option value="25">Elimina contracturas musculares</option>
                                </select>

                                <br><br>

                                <div class="row">
                                    <div class="col-md-12">
                                        <b style="color: #28a745">Seleccione Productos</b><br><br>
                                    </div>
                                </div>

                                <select class="form-control" id="doctor" name="doctor" required>
                                    <option value="" selected="" hidden="">Seleccione Producto</option>
                                    <?php
                                    $sql_traer_doctor = "SELECT * FROM usuarios WHERE rol = 'doc' OR rol = 'fis'";
                                    $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                                    while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                                        echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . ' ' . $row['apellidos'] . "</option>";
                                    }
                                    ?>
                                </select>

                                <!-- <input type='hidden' id='id_paciente' name='id_paciente'/> -->
                                <!-- <input class="form-control" type="text" autocomplete="off" placeholder="Fecha y hora de la cita" id="fecha_cita" name="fecha_cita" required /><br> -->
                                <div class="row">
                                    <div class="col-md-12">
                                        <b style="color: #28a745">Ingrese el numero de sesiones</b><br><br>
                                    </div>
                                </div>
                                <input type="number" name="" id="" value="Ingrese numero de sesiones">
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <b style="color: #28a745">Servicios</b><br><br>
                                <table class="table table-bordered table-hover" id="table">
                                    <thead class="tabla_cabecera">
                                        <tr>
                                            <th>Servicio</th>
                                            <th>Costo</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="indexconsultas">

                                    </tbody>
                                </table>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <b style="color: #28a745">Productos</b><br><br>
                                <table class="table table-bordered table-hover">
                                    <thead class="tabla_cabecera">
                                        <tr>
                                            <th>Id</th>
                                            <th>Producto</th>
                                            <th>Costo</th>
                                            <th>Accion</th>
                                        </tr>
                                    </thead>
                                    <tbody id="indexconsultas">

                                    </tbody>
                                </table>
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
        var servicios = [{
                'id': '1',
                'name': 'Displacia de cadera',
                'cost': 0
            },
            {
                'id': '2',
                'name': 'Pie plano',
                'cost': 0
            },
            {
                'id': '3',
                'name': 'Artrosis de rodilla',
                'cost': 0
            },
            {
                'id': '4',
                'name': 'Hernias discales',
                'cost': 0
            },
            {
                'id': '5',
                'name': 'Tratamientos con células madre',
                'cost': 0
            },
            {
                'id': '6',
                'name': 'Tratamientos para artrosis, enfermedades degenerativas, osteomusculares y osteoarticulares',
                'cost': 0
            },
            {
                'id': '7',
                'name': 'Reemplazo Articulares',
                'cost': 0
            },
            {
                'id': '8',
                'name': 'Tratamientos para el adulto',
                'cost': 0
            },
            {
                'id': '9',
                'name': 'Esguinces',
                'cost': 0
            },
            {
                'id': '10',
                'name': 'Fracturas',
                'cost': 0
            },
            {
                'id': '11',
                'name': 'Artrosis',
                'cost': 0
            },
            {
                'id': '12',
                'name': 'Dolores crónicos',
                'cost': 0
            },
            {
                'id': '13',
                'name': 'Fortalecimiento pulmonar',
                'cost': 0
            },
            {
                'id': '14',
                'name': 'Enfermedades crónicas respiratorias',
                'cost': 0
            },
            {
                'id': '15',
                'name': 'Pacientes post-COVID',
                'cost': 0
            },
            {
                'id': '16',
                'name': 'Mejora de calidad del sueño',
                'cost': 0
            },
            {
                'id': '17',
                'name': 'Ayuda a la concentración y memoria',
                'cost': 0
            },
            {
                'id': '18',
                'name': 'Elimina contracturas musculares',
                'cost': 0
            },
            {
                'id': '19',
                'name': 'Incontinecia urinaria',
                'cost': 0
            },
            {
                'id': '20',
                'name': 'Prolapsos',
                'cost': 0
            },
            {
                'id': '21',
                'name': 'Dolores en relaciones sexuales',
                'cost': 0
            },
            {
                'id': '22',
                'name': 'Eyaculación precoz',
                'cost': 0
            },
            {
                'id': '23',
                'name': 'Problemas de erección',
                'cost': 0
            },
            {
                'id': '24',
                'name': 'Programa de hidroterapia personalizado',
                'cost': 0
            },
            {
                'id': '25',
                'name': 'Elimina contracturas musculares',
                'cost': 0
            }
        ];

        $('#servicios').on('change', function() {
            let idA = $('#servicios').val();
            agregar(idA);
        });

        var arrayServicios = [];

        function agregar(idA) {

            var servicioaa = servicios.filter(function(item) {
                return item.id === idA;
            });

            var validacion = arrayServicios.filter(function(item) {
                return item.id === idA;
            });

            if (validacion.length === 0) {
                arrayServicios.push(servicioaa[0]);
            }
            mostrarTabla();
        }

        function mostrarTabla() {
            var foo = arrayServicios.map(function(item) {
                let template = `<tr id='${item.id}'>
                                    <td> ${item.name} </td>
                                    <td> ${item.cost} </td>
                                    <td><button id='eliminar'>Eliminar</button></td>
                                </tr>`;
                return template;
            });
            $('#indexconsultas').html(foo);
            const del = document.querySelectorAll("#eliminar");
            del.forEach((card, i) => {
                card.addEventListener('click', () => {
                    var id = card.parentElement.parentElement.id;
                    eliminar(id);
                });
            });
        }

        function eliminar(id) {
            var index = arrayServicios.findIndex(function(o) {
                return o.id === id;
            });

            if (index !== -1) arrayServicios.splice(index, 1);

            mostrarTabla();
        }

        // $(document).on('click', '#eliminar', function() {
        //     let element = $('#eliminar')[0].parentElement.parentElement;
        //     let id = $(element).attr('id');
        //     console.log(id);
        // });
    </script>
</body>

</html>