<?php
session_start();
include '../conection/conection.php';
include './Seguridad.php';
if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']))) {
    $usuario = $_SESSION['usuario'];
    $id_usuario = $_SESSION['id'];
    $rol = $_SESSION['rol'];
?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="shortcut icon" href="../img/icon.png" type="image/x-icon">
        <title>Clínica</title>
        <link rel="stylesheet" href="../css/bootstrap.css">
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
        <link rel="stylesheet" href="../css/fontawesome-all.min.css">
        <link rel="stylesheet" href="../css/estilos.css">
        <link rel="stylesheet" href="../css/jquery.datetimepicker.css">
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
                            <?php if ($rol == 'fis') { ?>
                                <a class="nav-link" href="historias_clinicas.php">INICIO</a>

                            <?php } else { ?>
                                <a class="nav-link" href="inicio.php">INICIO</a>
                            <?php } ?>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="lista_pacientes.php">PACIENTES</a></li>
                        <li class="nav-item"><a class="nav-link" href="lista_citas.php">CITAS</a></li>
                        <li class="nav-item"><a class="nav-link" href="lista_consultas.php">CONSULTAS</a></li>
                        <li class="nav-item">
                            <?php if ($rol == 'fis') { ?>
                                <a class="nav-link" href="lista_historias_fisioterapeuta.php">HISTORIAS</a>

                            <?php } else { ?>
                                <a class="nav-link" href="lista_historias.php">HISTORIAS</a>
                            <?php } ?>
                        </li>

                        <?php if ($rol == 'adm') { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    INVENTARIO
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="crear_producto.php">Crear Producto</a>
                                    <a class="dropdown-item" href="lista_productos.php">Listar Productos</a>
                                    <a class="dropdown-item" href="crear_compra.php">Crear Compra</a>
                                    <a class="dropdown-item" href="listar_compras.php">Listar Compras</a>
                                </div>
                            </li>


                            <li class="nav-item"><a class="nav-link" href="lista_usuarios.php">USUARIOS</a></li>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    PAQUETES
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="crear_paquetes.php">Crear</a>
                                    <a class="dropdown-item" href="lista_paquetes.php">Lista</a>
                                    <a class="dropdown-item" href="venta.php">Venta</a>
                                    <a class="dropdown-item" href="lista_ventas.php">Lista Ventas</a>
                                    <a class="dropdown-item" href="crear_servicio.php">Servicios</a>
                                    <a class="dropdown-item" href="lista_servicios.php">Lista Servicios</a>

                                </div>
                            </li>
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

    </html>
<?php
} else {
    header("location:../index.php");
}
