<!DOCTYPE html>
<html>
<?php
include 'header.php';
$status = $_GET['status'];
$class = '';
$close = '';
if (isset($status)) {
    $close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';
    if ($status === 'OK') {
        $error = 'Paciente creado correctamente';
        $class = 'class="alert alert-success alert-dismissible fade show" role="alert"';
    } else {
        $error = 'Ocurrió un error al crear el paciente';
        $class = 'class="alert alert-danger alert-dismissible fade show" role="alert"';
    }
}
?>

<!-- <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PHP MySQL Select2 Example</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
    <title>Clínica</title>
    <link rel="stylesheet" href="../css/bootstrap.css">
    <link rel="stylesheet" href="../css/fontawesome-all.min.css">
    <link rel="stylesheet" href="../css/estilos.css">
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">

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

        .custom-div {
            /* height: 200px;
            background-color: #f2f2f2; */
            display: flex;
            align-items: center;
            justify-content: center;
            /* border: 1px solid #ccc;
            border-radius: 2px; */
        }
    </style>
</head> -->

<body>
    <section class="cuerpo">

        <h1 class="text-center">Crear Paquete</h1><br>
        <div class="row">
            <div class="col-md-12">
                <h4>Agregar Detalles del Paquete</h4>
            </div><br><br>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="titulo_paquete">Titulo del paquete:</label>
                    <input type="text" class="form-control" name="titulo_paquete" id="titulo_paquete" placeholder="Ingrese titulo del paquete">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="numero_sesiones">Número de sesiones:</label>
                    <input type="number" class="form-control" name="numero_sesiones" id="numero_sesiones" value="1" min="1">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="tipo_paquete">Tipo de paquete:</label>
                    <select class="form-control" name="tipo_paquete" id="tipo_paquete">
                        <option value="0">Seleccione Tipo de Paquete</option>
                        <option value="1">Básico</option>
                        <option value="2">Plus</option>
                        <option value="3">Premium</option>
                        <option value="4">Empresas</option>
                        <option value="5">Convenios</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>Agregar Servicios</h4>
            </div><br><br>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="servicios">Servicios:</label>
                    <select class="form-control select2" data-rel="chosen" name="servicios" id="servicios">
                        <option value="0">Seleccione Servicio</option>
                        <?php
                        $sql_traer_servicios = "SELECT * FROM servicios";
                        $consulta_traer_servicios = $mysqli->query($sql_traer_servicios);
                        while ($row = mysqli_fetch_array($consulta_traer_servicios)) {
                            echo "<option data-cost='" . $row['total'] . "' data-name='" . $row['titulo_servicio'] . "' data-adicional='" . $row['valor_adicional'] . "' value='" . $row['id_servicio'] . "'>" . $row['titulo_servicio'] . ' - $'.$row['valor_adicional']. "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="numero_sesion">Número de sesiones:</label>
                    <input type="number" class="form-control" name="numero_sesion" id="numero_sesion" value="1" min="1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="button" class="btn btn-primary" value="Agregar" id="agregar_servicio">
                </div>
            </div>
            <div class="col-md-12">
                <h4>Agregar Productos</h4>
            </div><br><br>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="productos">Productos:</label>
                    <select class="select2 form-control" id="productos" name="productos">
                        <option value="0">Seleccione Producto</option>
                        <?php
                        $sql_traer_productos = "SELECT * FROM productos";
                        $consulta_traer_productos = $mysqli->query($sql_traer_productos);
                        while ($row = mysqli_fetch_array($consulta_traer_productos)) {
                            echo "<option data-cost='" . $row['precio_v'] . "' data-name='" . $row['nombre'] . "' value='" . $row['id'] . "'>" . $row['nombre'] .' - '. $row['descripcion'].' - $'.$row['precio_v']. "</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="cantidad_producto">Cantidad:</label>
                    <input type="number" class="form-control" name="cantidad_producto" id="cantidad_producto" min="1" value="1">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="button" class="btn btn-primary" value="Agregar" id="agregar_producto">
                </div>
            </div>

            <div class="col-md-12">
                <table class="table table-bordered table-hover" id="table">
                    <thead class="tabla_cabecera">
                        <tr>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Valor</th>
                            <th>Cantidad</th>
                            <th>Total</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="table_detalle">

                    </tbody>
                </table>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <div class="d-flex justify-content-center">
                        <div class="box mx-5">
                            <div>Sub Total</div>
                            <div id="total_pago" >00</div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        <div class="box mx-5">
                            <div>Total con descuento</div>
                            <input class="form-control" type="text" id="descuento" name="descuento">
                        </div>
                    </div>
                </div><br>
                <div class="d-flex justify-content-center">
                    <input class="btn btn-primary" type="submit" name="btn_crear_paquete" id="crear_paquete" value="Crear Paquete" />
                </div>
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
        function calcularDescuento() {
            var total_v = parseFloat(document.getElementById("total_v").value);
            

            if (!isNaN(total_v) ) {
                var descuento =  total_pago - total_v;
                document.getElementById("descuento").value = descuento.toFixed(2); // Redondear a 2 decimales
            } else {
                document.getElementById("descuento").value = ""; // Limpiar el campo si los valores son inválidos
            }
        }


        $('#agregar_servicio').on('click', function() {
            let idA = $('#servicios').val();
            let numeroSesiones = $('#numero_sesion').val();
            if (idA > 0 && numeroSesiones != undefined && numeroSesiones != 0) {
                // var service = servicios.filter(function(item) {
                //     return item.id === Number(idA);
                // });
                var selectedOption = $('#servicios').find('option:selected');
                //var idA = selectedOption.val();
                var nombre = selectedOption.text();
                var precio = parseFloat(selectedOption.data('cost'));
                var valorAdicional = parseFloat(selectedOption.data('adicional'));

                var element = {
                    'id': idA,     
                    'name': nombre,
                    'type': "Servicio",
                    'cost': valorAdicional,
                    'amount': Number(numeroSesiones),
                    'total': valorAdicional * numeroSesiones,
                }
                agregar(element);
                const FD = new FormData();
                FD.append('action', "buscar_productos_servicio");
                FD.append('id_servicio', idA);
                fetch("paquete_ajax.php", {
                        method: 'POST',
                        body: FD
                    }).then(respuesta => respuesta.text())
                    .then(decodificado => {
                        console.log(decodificado);
                        const data = JSON.parse(decodificado);
                        data.forEach(datos => {
                            var element = {
                                'id': Number(datos.id_producto),
                                'name': datos.nombre,
                                'type': "Producto",
                                'cost': Number(datos.precio),
                                'amount': Number(datos.cantidad),
                                'total': Number(datos.subtotal)
                            }
                            agregar(element);
                        });
                    })
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            }
        });

        $('#agregar_producto').on('click', function() {
            let idA = $('#productos').val();
            let cantidad = Number($('#cantidad_producto').val());
            var selectElement = document.querySelector('#productos');
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            if (idA > 0 && cantidad > 0) {
                var element = {
                    'id': Number(idA),
                    'name': selectedOption.getAttribute('data-name'),
                    'type': "Producto",
                    'cost': selectedOption.getAttribute('data-cost'),
                    'amount': cantidad,
                    'total': selectedOption.getAttribute('data-cost') * cantidad
                }
                agregar(element);
            }
        });

        var listaPaquete = [];

        function agregar(elemento) {
            var validacion = listaPaquete.filter(function(item) {
                return (item.id === elemento.id && item.type === elemento.type);
            });

            if (validacion.length === 0) {
                listaPaquete.push(elemento);
                mostrarTabla();
                totalizarPago();
            } else {
                var alertElement = document.getElementById('alert-danger');
                var duracion = 3000;
                $('#alert-danger').html('Servicio/Producto ya agregado');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }
        }

        function mostrarTabla() {
            var foo = listaPaquete.map(function(item) {
                let template = `<tr id='${item.id}' data-type='${item.type}'>
                                    <td> ${item.name} </td>
                                    <td> ${item.type} </td>
                                    <td> ${item.cost} </td>
                                    <td> ${item.amount} </td>
                                    <td> ${item.total} </td>
                                    <td><a class='btn btn-danger btn-sm ml-1' id='eliminar_servicio'><i class='fas fa-trash-alt table-icon'></i></a></td>
                                </tr>`;
                return template;
            });
            $('#table_detalle').html(foo);
            const del = document.querySelectorAll("#eliminar_servicio");
            del.forEach((card, i) => {
                card.addEventListener('click', () => {
                    var id = card.parentElement.parentElement.id;
                    var type = card.parentElement.parentElement.getAttribute('data-type');
                    eliminar(id, type);
                });
            });
        }

        function eliminar(id, type) {
            var index = listaPaquete.findIndex(function(o) {
                return (o.id === Number(id) && o.type === type);
            });
            if (index !== -1) listaPaquete.splice(index, 1);
            mostrarTabla();
            totalizarPago();
        }

        function totalizarPago() {
            var total = listaPaquete.reduce((acumulador, servicio) => acumulador + servicio.total, 0);
            $('#total_pago').html(total);
        }

        function validar() {
            let validator = true;
            var alertElement = document.getElementById('alert-danger');
            var duracion = 3000;
            if ($('#titulo_paquete').val() == '') {
                validator = false;
                $('#alert-danger').html('Ingrese titulo del paquete');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if ($('#numero_sesiones').val() < 1) {
                validator = false;
                $('#alert-danger').html('Ingrese numero de sesiones correctas');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if ($('#tipo_paquete').val() == 0) {
                validator = false;
                $('#alert-danger').html('Seleccione tipo de paquete');
                alertElement.classList.remove('d-none');
                setTimeout(function() {
                    alertElement.classList.add('d-none');
                }, duracion);
            }

            if (listaPaquete.length == 0) {
                validator = false;
                $('#alert-danger').html('Agregue pòr lo menos un servicio');
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
                FD.append('action', "crear_paquete");
                FD.append('titulo_paquete', $('#titulo_paquete').val());
                FD.append('tipo_paquete', Number($("#tipo_paquete").val()));
                FD.append('numero_sesiones', Number($('#numero_sesiones').val()));
                FD.append('total', Number($('#total_pago').text()));
                FD.append('descuento', Number($('#descuento').val()));
                FD.append('lista', JSON.stringify(listaPaquete));
                fetch("paquete_ajax.php", {
                        method: 'POST',
                        body: FD
                    }).then(respuesta => respuesta.text())
                    .then(decodificado => {
                        
                        
                        Swal.fire({
                                icon: 'success',
                                title: 'Paquete creado',
                                text: 'El paquete se creo correctamente!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            location.reload();
                            

                        // var alertElement = document.getElementById('alert-success');
                        // alertElement.classList.remove('d-none');
                        // setTimeout(function() {
                        //     alertElement.classList.add('d-none');
                        // }, 3000);
                    })
                    
                    .catch(function(error) {
                        console.log('Hubo un problema con la petición Fetch: ' + error.message);
                    });
            
                    
                }
        }

        $('#crear_paquete').on('click', function() {
            crear();
        });
    </script>
</body>

</html>