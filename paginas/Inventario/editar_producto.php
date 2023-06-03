<?php
include 'header.php';
$id_producto = $_GET['id_producto'];
$class = '';
$close = '';

if (isset($_POST['btn_actualizar_producto'])) {

    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_c = $_POST['precio_c'];
    $precio_v = $_POST['precio_v'];
    $stock = $_POST['stock'];

    $sql = "UPDATE pacientes SET codigo = '$codigo', nombre = '$nombre', descripcion='$descripcion', precio_c='$precio_c', precio_v= '$precio_v', stock='$stock',";
            
    $result_update_consulta = $mysqli->query($sql);



    if ($result_update_consulta === true) {
        $error = 'Consulta actualizada con éxito';
        $class = 'class = "alert alert-success alert-dismissible fade show" role = "alert"';
        $close = '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>';
    } else {
        $error = 'Ocurrió un error al actualizar la información de la consulta';
        $class = 'class="alert alert-danger alert-dismissible fade show" role="alert"';
        $close = '<button type = "button" class = "close" data-dismiss = "alert" aria-label = "Close">
        <span aria-hidden = "true">&times;
        </span>
        </button>';
    }
}
?>
<body>
    <section class="cuerpo">
        <h1>Editar datos del paciente</h1><br>        
        <?php
        $sql_ver = "SELECT * FROM pacientes WHERE id='$id_producto'";
        $result_ver_paciente = $mysqli->query($sql_ver);
        $row_consulta = $result_ver_paciente->fetch_assoc();
        ?>
        <div style="padding: 2% 2% 1% 2%; background-color: #D8D8D8">
            <form method="post" >
                <div class="row">                    
                    <div class = "col-md-4">
                        <input class = "form-control" title = "Cédula o Pasaporte" placeholder = "Cédula o Pasaporte" value = "<?php echo $row_consulta['numero_identidad']; ?>" id = "numero_identidad" name = "numero_identidad" />
                        <input class = "form-control" title = "Nombres" placeholder = "Nombres" value = "<?php echo $row_consulta['nombres']; ?>" id = "nombres" name = "nombres" />
                        <input class = "form-control" title = "Apellidos" placeholder = "Apellidos" value = "<?php echo $row_consulta['apellidos']; ?>" id = "apellidos" name = "apellidos" />
                        <input class = "form-control" title = "Fecha nacimiento" placeholder = "Fecha nacimiento" value = "<?php echo $row_consulta['fecha_nacimiento']; ?>" id = "fecha_nacimiento" name = "fecha_nacimiento" />
                        <select class = "form-control" id = "genero" name = "genero" title = "Seleccione el Género">
                            <option value = "<?php echo $row_consulta['genero']; ?>" selected = ""><?php echo $row_consulta['genero']; ?></option>
                            <option value = "Masculino">Maculino</option>
                            <option value = "Femenino">Femenino</option>
                        </select>
                        <input class = "form-control" title = "Teléfono fijo" placeholder = "Teléfono fijo" value = "<?php echo $row_consulta['telefono_fijo']; ?>" id = "telefono_fijo" name = "telefono_fijo" /></div>
                    <div class = "col-md-4">
                        <input class = "form-control" title = "Teléfono móvil" placeholder = "Teléfono móvil" value = "<?php echo $row_consulta['telefono_movil']; ?>" id = "telefono_movil" name = "telefono_movil" />
                        <input class = "form-control" title = "Dirección" placeholder = "Dirección" value = "<?php echo $row_consulta['direccion']; ?>" id = "direccion" name = "direccion" />
                        <select class = "form-control" id = "raza" name = "raza" title = "Seleccione la Raza">
                            <option value = "<?php echo $row_consulta['raza']; ?>" selected = ""><?php echo $row_consulta['raza']; ?></option>
                            <option value = "Mestiza">Mestiza</option>
                            <option value="Negra">Negra</option>
                            <option value = "Blanca">Blanca</option></select>
                        <input class="form-control" title="Ocupación" placeholder="Ocupación" value="<?php echo $row_consulta['ocupacion']; ?>" id="ocupacion" name="ocupacion" />
                        <select class = "form-control" id = "estado_civil" name = "estado_civil" title = "Seleccione Estado Civil">
                            <option value="<?php echo $row_consulta['estado_civil']; ?>" selected=""><?php echo $row_consulta['estado_civil']; ?></option>
                            <option value = "Casado">Casado</option>
                            <option value="Soltero">Soltero</option>
                            <option value = "Union libre">Unión libre</option></select>
                        <input class="form-control" title="Correo electrónico" placeholder="Correo electrónico"  value="<?php echo $row_consulta['correo_electronico']; ?>" id="correo_electronico" name="correo_electronico" /></div>
                    <div class = "col-md-4">
                        <textarea class="form-control" title="Antecedentes personales" placeholder="Antecedentes personales" id="antecedentes_personales" name="antecedentes_personales"><?php echo $row_consulta['antecedentes_personales']; ?></textarea>
                        <textarea class = "form-control" title = "Antecedentes familiares" placeholder = "Antecedentes familiares" id = "antecedentes_familiares" name = "antecedentes_familiares"><?php echo $row_consulta['antecedentes_familiares']; ?></textarea><br>
                        <div <?php echo $class; ?> >
                            <?php echo isset($error) ? $error : ''; ?>
                            <?php echo $close; ?>
                        </div>
                        <div class=" float-right">
                            <input class = "btn btn-primary" type = "submit" name = "btn_actualizar_producto" id = "btn_actualizar_producto" value = "Actualizar datos del paciente" />
                            <a class = "btn btn-danger" href="lista_pacientes.php">Cancelar</a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <br>
    <?php
    include 'footer.php';
    ?>

    <script src="../js/bootstrap-select.js"></script>
</body>