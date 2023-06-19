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

    $sql = "UPDATE productos SET codigo = '$codigo', nombre = '$nombre', descripcion='$descripcion', precio_c='$precio_c', precio_v= '$precio_v' WHERE id='$id_producto'";
            
    $result_update_consulta = $mysqli->query($sql);



    if ($result_update_consulta === true) {
        $error = 'Producto actualizad con éxito';
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
        <h1>Editar Producto</h1><br>        
        <?php
        $sql_ver = "SELECT * FROM productos WHERE id='$id_producto'";
        $result_ver_paciente = $mysqli->query($sql_ver);
        $row_consulta = $result_ver_paciente->fetch_assoc();
        ?>
        <div style="padding: 2% 2% 1% 2%; background-color: #D8D8D8">
            <form method="post" >
                <div class="row">                    
                    <div class = "col-md-6">
                        <input class = "form-control" title = "Código" placeholder = "Código" value = "<?php echo $row_consulta['codigo']; ?>" id = "codigo" name = "codigo" />
                        <input class = "form-control" title = "Nombre" placeholder = "Nombre" value = "<?php echo $row_consulta['nombre']; ?>" id = "nombre" name = "nombre" />
                        <textarea class="form-control" title="Descripción" placeholder="Descripción" id="descripcion" name="descripcion"><?php echo $row_consulta['descripcion']; ?></textarea>
                    </div>
                    <div class = "col-md-6">
                        <input class = "form-control" type="number" title = "Precio Compra" placeholder = "Precio Compra" value = "<?php echo $row_consulta['precio_c']; ?>" id = "precio_c" name = "precio_c" />
                        <input class = "form-control"  type="number" title = "Precio Venta" placeholder = "Precio Venta" value = "<?php echo $row_consulta['precio_v']; ?>" id = "precio_v" name = "precio_v" />
                        <div <?php echo $class; ?> >
                            <?php echo isset($error) ? $error : ''; ?>
                            <?php echo $close; ?>
                        </div>
                        <div class=" float-right">
                            <input class = "btn btn-primary" type = "submit" name = "btn_actualizar_producto" id = "btn_actualizar_producto" value = "Actualizar" />
                            <a class = "btn btn-danger" href="lista_productos.php">Cancelar</a>
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