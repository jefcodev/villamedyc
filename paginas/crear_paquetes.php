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
    <div>
        <section class="cuerpo">
            <div class="d-flex justify-content-center">
                <div class="alert alert-primary d-none" id="alert-primary" role="alert">
                    Este es un mensaje de información.
                </div>

                <div class="alert alert-success d-none" id="alert-success" role="alert">
                    Cita creada correctamente.
                </div>

                <div class="alert alert-danger d-none" id="alert-danger" role="alert">
                    Este es un mensaje de error.
                </div>
            </div>
            <h1 class="text-center">Crear Cita Fisioterapeuta</h1><br>
            <?php
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "villame5_bb0";

            $conn = new mysqli($servername, $username, $password, $dbname);
            $query = "SELECT MAX(CABECERA_ID) AS id FROM `paquete_cabecera`";
            $result = $conn->query($query);
            while ($row = mysqli_fetch_array($result)) {
                $id_historia = (int)$row['id'];
                if ($id_historia == NULL) {
                    echo "<div id='id_paquete' class='d-none' data-paquete-id='1'>1</div>";
                } else {
                    $id_historia = $id_historia + 1;
                    echo "<div id='id_paquete' data-paquete-id='" . $id_historia . "'>" . $id_historia . "</div>";
                }
            }
            ?>

            <div class="row">
                <div class="col-md-6">
                    <?php
                    $sql = "SELECT * FROM `pacientes`";
                    $result = $conn->query($sql);
                    ?>
                    <!-- <label for="id_paciente">Buscar paciente por Nombres, Apellidos o C.I.</label> -->
                    <select class="select2 form-control" data-rel="chosen" id='id_paciente' name='id_paciente'>
                        <option value="0" selected>Seleccione el Paciente</option>
                        <?php
                        if ($result) {
                            while ($fila = mysqli_fetch_array($result)) {
                                echo "<option value='" . $fila['id'] . "'>" . $fila["numero_identidad"] . "  " .  $fila["nombres"] . "  " . $fila["apellidos"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <!-- <label for="doctor">Seleccione doctor</label> -->
                    <select class="select2 form-control" data-rel="chosen" id="doctor" name="doctor">
                        <option value="" selected="" hidden="">Seleccione el Fisioterapeuta</option>
                        <?php
                        $sql_traer_doctor = "SELECT * FROM usuarios WHERE rol = 'fis'";
                        $consulta_traer_doctor = $mysqli->query($sql_traer_doctor);
                        while ($row = mysqli_fetch_array($consulta_traer_doctor)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['nombre'] . ' ' . $row['apellidos'] . "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12">Agregar Servicios</div><br><br>
                <div class="col-md-6">
                    <!-- <label for="servicios">Selecciones los servicios</label> -->
                    <select class="select2 form-control" data-rel="chosen" name="servicios" id="servicios">
                        <option value="0" selected>Seleccione Servicio</option>
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
                    </select><br><br>
                    <!-- <label for="numero_sesion">Nº de sesiones</label> -->
                    <input type="number" placeholder="Nº de sesiones" name="numero_sesion" id="numero_sesion" value="Ingrese numero de sesiones">
                    <input type="button" value="Agregar" id="agregar_servicio">
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover" id="table">
                        <thead class="tabla_cabecera">
                            <tr>
                                <th>Servicio</th>
                                <th>Valor</th>
                                <th>Nº Sesiones</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="table_servicio">

                        </tbody>
                    </table>
                </div>
            </div><br>
            <div class="row">
                <div class="col-md-12 tex">Agregar Productos</div><br><br>
                <div class="col-md-6">
                    <!-- <label for="">Seleccione los productos</label> -->
                    <select class="select2 form-control" id="productos" name="productos" required>
                        <option value="" selected="" hidden="">Seleccione Producto</option>
                        <?php
                        $sql_traer_productos = "SELECT * FROM productos";
                        $consulta_traer_productos = $mysqli->query($sql_traer_productos);
                        while ($row = mysqli_fetch_array($consulta_traer_productos)) {
                            echo "<option data-cost='" . $row['precio_v'] . "'data-count='" . $row['stock'] . "' data-name='" . $row['nombre'] . "' value='" . $row['id'] . "'>" . $row['nombre'] . "</option>";
                        }
                        ?>
                    </select><br><br>
                    <!-- <div class="col-md-6">Ingrese cantidad</div> -->
                    <input type="number" placeholder="Ingrese Cantidad" name="cantidad_producto" id="cantidad_producto" min="1" value="Cantidad">
                    <input type="button" value="Agregar" id="agregar_producto">
                </div>
                <div class="col-md-6">
                    <table class="table table-bordered table-hover">
                        <thead class="tabla_cabecera">
                            <tr>
                                <th>Producto</th>
                                <th>Valor</th>
                                <th>Cantidad</th>
                                <th>Total</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="table_producto">
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="text-center">
                        <div class="d-flex justify-content-center">
                            <div class="box mx-5">
                                <div>Total Sesiones</div>
                                <div id="total_sesiones">00</div>
                            </div>
                            <div class="box mx-5">
                                <div>Total Pago</div>
                                <div id="total_pago">00</div>
                            </div>
                        </div>
                    </div><br>
                    <div class="d-flex justify-content-center">
                        <input class="btn btn-primary" type="submit" name="btn_crear_paquete" id="btn_crear_paquete" value="Cobrar" />
                    </div>
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
                'cost': 10,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '2',
                'name': 'Pie plano',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '3',
                'name': 'Artrosis de rodilla',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '4',
                'name': 'Hernias discales',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '5',
                'name': 'Tratamientos con células madre',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '6',
                'name': 'Tratamientos para artrosis, enfermedades degenerativas, osteomusculares y osteoarticulares',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '7',
                'name': 'Reemplazo Articulares',
                'cost': 10,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '8',
                'name': 'Tratamientos para el adulto',
                'cost': 40,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '9',
                'name': 'Esguinces',
                'cost': 50,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '10',
                'name': 'Fracturas',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '11',
                'name': 'Artrosis',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '12',
                'name': 'Dolores crónicos',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '13',
                'name': 'Fortalecimiento pulmonar',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '14',
                'name': 'Enfermedades crónicas respiratorias',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '15',
                'name': 'Pacientes post-COVID',
                'cost': 40,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '16',
                'name': 'Mejora de calidad del sueño',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '17',
                'name': 'Ayuda a la concentración y memoria',
                'cost': 10,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '18',
                'name': 'Elimina contracturas musculares',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '19',
                'name': 'Incontinecia urinaria',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '20',
                'name': 'Prolapsos',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '21',
                'name': 'Dolores en relaciones sexuales',
                'cost': 40,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '22',
                'name': 'Eyaculación precoz',
                'cost': 50,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '23',
                'name': 'Problemas de erección',
                'cost': 30,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '24',
                'name': 'Programa de hidroterapia personalizado',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            },
            {
                'id': '25',
                'name': 'Elimina contracturas musculares',
                'cost': 20,
                'sesiones': 0,
                'total': 0
            }
        ];

        $('#agregar_servicio').on('click', function() {
            let idA = $('#servicios').val();
            let numeroSesiones = $('#numero_sesion').val();
            if (idA > 0 && numeroSesiones != undefined && numeroSesiones != 0) {
                agregar(idA, numeroSesiones);
            }
        });

        var arrayServicios = [];

        function agregar(idA, numeroSesiones) {
            var servicioaa = servicios.filter(function(item) {
                return item.id === idA;
            });

            var validacion = arrayServicios.filter(function(item) {
                return item.id === idA;
            });

            if (validacion.length === 0) {
                servicioaa[0].sesiones = numeroSesiones;
                servicioaa[0].total = servicioaa[0].sesiones * servicioaa[0].cost;
                arrayServicios.push(servicioaa[0]);
                mostrarTabla();
                totalizarPago();
                totalizarSesiones();
            }
        }

        function mostrarTabla() {
            var foo = arrayServicios.map(function(item) {
                let template = `<tr id='${item.id}'>
                                    <td> ${item.name} </td>
                                    <td> ${item.cost} </td>
                                    <td> ${item.sesiones} </td>
                                    <td> ${item.total} </td>
                                    <td><a class='btn btn-danger btn-sm ml-1' id='eliminar_servicio'><i class='fas fa-trash-alt table-icon'></i></a></td>
                                </tr>`;
                return template;
            });
            $('#table_servicio').html(foo);
            const del = document.querySelectorAll("#eliminar_servicio");
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
            totalizarPago();
            totalizarSesiones();
        }

        $('#agregar_producto').on('click', function() {
            let idA = $('#productos').val();
            let cantidad = Number($('#cantidad_producto').val());
            var selectElement = document.querySelector('#productos');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var stock = selectedOption.getAttribute('data-count');
            if (idA > 0 && cantidad > 0 && cantidad <= stock) {
                var producto = {
                    'id': idA,
                    'name': selectedOption.getAttribute('data-name'),
                    'cost': selectedOption.getAttribute('data-cost'),
                    'count': cantidad,
                    'total': selectedOption.getAttribute('data-cost') * cantidad
                }
                agregarProductos(producto);
            }
        });

        var arrayProductos = [];

        function agregarProductos(producto) {
            console.log(arrayProductos);
            var validacion = arrayProductos.filter(function(item) {
                return item.id === producto.id;
            });

            if (validacion.length === 0) {
                arrayProductos.push(producto);
                mostrarTablaProductos();
                totalizarPago();
            }
        }

        function mostrarTablaProductos() {
            var foo = arrayProductos.map(function(item) {
                let template = `<tr id='${item.id}'>
                                    <td> ${item.name} </td>
                                    <td> ${item.cost} </td>
                                    <td> ${item.count} </td>
                                    <td> ${item.total} </td>
                                    <td><a class='btn btn-danger btn-sm ml-1' id='eliminar_producto'><i class='fas fa-trash-alt table-icon'></i></a></td>
                                </tr>`;
                return template;
            });
            $('#table_producto').html(foo);
            const del = document.querySelectorAll("#eliminar_producto");
            del.forEach((card, i) => {
                card.addEventListener('click', () => {
                    var id = card.parentElement.parentElement.id;
                    eliminarProducto(id);
                });
            });
        }

        function eliminarProducto(id) {
            var index = arrayProductos.findIndex(function(o) {
                return o.id === id;
            });

            if (index !== -1) arrayProductos.splice(index, 1);

            mostrarTablaProductos();
            totalizarPago();
        }

        function totalizarPago() {
            var total = arrayServicios.reduce((acumulador, servicio) => acumulador + servicio.total, 0);
            total += arrayProductos.reduce((acumulador, producto) => acumulador + producto.total, 0);
            $('#total_pago').html(total);
        }

        function totalizarSesiones() {
            var total = arrayServicios.reduce((acumulador, servicio) => acumulador + Number(servicio.sesiones), 0);
            $('#total_sesiones').html(total);
        }

        function validar() {
            let validator = true;
            var alertElement = document.getElementById('alert-danger');
            var duracion = 3000;
            if (Number($('#id_paciente').val()) === 0) {
                validator = false;
                $('#alert-danger').html('Seleccione Paciente');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }
            if (Number($('#doctor').val()) === 0) {
                $('#alert-danger').html('Seleccione Fisioterapeuta');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }
            if (arrayServicios.length === 0) {
                validator = false;
                $('#alert-danger').html('Seleccione Servicios');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            return validator;
        }

        function crear() {
            if (validar()) {
                const FD = new FormData();
                FD.append('cabecera_id', Number($('#id_paquete').text()));
                FD.append('paciente_id', Number($('#id_paciente').val()))
                FD.append('usuario_id', Number($("#doctor").val()));
                FD.append('total', Number($('#total_pago').text()));
                FD.append('servicios', JSON.stringify(arrayServicios));
                console.log(arrayProductos);
                if (arrayProductos.length > 0) {
                    FD.append('productos', JSON.stringify(arrayProductos));
                }
                fetch("crear_paquete_ajax.php", {
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

                // $.ajax({
                //     url: 'crear_paquete_ajax.php',
                //     type: 'post',
                //     data: {
                //         FD
                //     },
                //     success: function(response) {
                //         var alertElement = document.getElementById('alert-success');
                //         alertElement.classList.remove('d-none');
                //         setTimeout(function() {
                //             alertElement.classList.add('d-none');
                //         }, 3000);
                //     }
                // });
            }
        }

        $('#btn_crear_paquete').on('click', function() {
            crear();
        });
    </script>
</body>

</html>