<?php
include 'header.php';
session_start();
include_once "../conection/conection.php";
$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];

if (isset($_POST['btn_crear_compra'])) {
    //date_default_timezone_set('America/Bogota');
    //$fecha_hora = date("Y-m-d H:i:s");
    $fecha = $_POST['fecha'];
    $producto_id = $_POST['producto_id'];
    $cantidad = $_POST['cantidad'];
    $proveedor = $_POST['proveedor'];
    $factura = $_POST['factura'];

    $sql_crear_producto = "INSERT INTO compras (fecha, producto_id, cantidad , proveedor, factura "
            . "VALUES ('$fecha', '$producto_id', '$cantidad', '$proveedor', '$factura');"
            . " UPDATE productos SET stock = stock + $cantidad WHERE id = $producto_id;";
            
    $query_crear_producto = $mysqli->query($sql_crear_producto);
    if ($query_crear_producto == TRUE) {
        header("location:lista_productos.php?status=OK");
    } else {
        header("location:crear_producto.php?status=ER");
    }
}
?>
<head> 
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">
</head>
<body>
    <section class="cuerpo">
        <h1>Crear Compra</h1><br>
        <form  method="post" onsubmit="return validar()">
            <div class="row">
                <div class="col-md-4">   
                    <input class="form-control" title="Fecha" type="date" id="fecha" name="fecha" required/>
                    <input class="form-control" title="Cantidad" type="number"   id="cantidad" name="cantidad" required/>
                    <input class="form-control" title="Proveedor" placeholder="Proveedor" id="proveedor" name="proveedor" required/>
                    <input class="form-control" title="Factura" placeholder="Factura" id="factura" name="factura" required/>
                    <div class="float-right">
                        <input class="btn btn-primary" type="submit" name="btn_crear_compra" id="btn_crear_compra" value="Aceptar" />
                        <a class="btn btn-danger" href="lista_productos.php">Cancelar</a>
                    </div>
                </div>
               
            </div>
        </form>
        <br>
    </section>
    <br>
    <?php
    include 'footer.php';
    ?>
    <script language="javascript" src="../js/jquery.datetimepicker.full.min.js"></script>
    <script>
            $('#fecha_nacimiento').datetimepicker({
                timepicker:false,
                format:'Y-m-d'
            });
            $.datetimepicker.setLocale('es');
    </script>
</body>
