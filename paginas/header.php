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

        <!-- Bootstrap -->
        <link rel="stylesheet" href="../css/bootstrap.css">
        <link rel="stylesheet" href="../css/estilos.css">

        <!--  <link rel="stylesheet" href="../css/fontawesome-all.min.css"> -->

        <link rel="stylesheet" href="../css/jquery.datetimepicker.css">

        <!-- Iconos font-awesome  -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Select Start  -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
        <!-- Select End -->


        <!--  
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        
        <script src="../js/poppers.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
 -->


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
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                PACIENTES
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="crear_paciente.php">Nuevo Paciente</a>
                                <a class="dropdown-item" href="lista_pacientes.php">Listar Pacientes</a>
                                <a class="dropdown-item" href="crear_empresa.php">Nueva Empresa</a>
                                <a class="dropdown-item" href="crear_fuente.php">Nueva Fuente</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                CITAS
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="crear_cita.php">Nueva Cita</a>
                                <a class="dropdown-item" href="lista_citas.php">Listar Citas</a>
                                <a class="dropdown-item" href="lista_consultas.php">Listar Consultas</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                HISTORIAS
                            </a>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="lista_historias.php">Traumatología</a>
                                <a class="dropdown-item" href="lista_historias_fisioterapeuta.php">Fisioterapia </a>
                            </div>
                        </li>

                        <?php if ($rol == 'adm') { ?>
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    INVENTARIO
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="crear_producto.php">Crear Producto</a>
                                    <a class="dropdown-item" href="lista_productos.php">Listar Productos</a>
                                    <a class="dropdown-item" href="crear_compra.php">Crear Compra</a>
                                    <a class="dropdown-item" href="listar_compras.php">Reporte Compras</a>
                                    <a class="dropdown-item" href="crear_venta.php">Crear Venta</a>
                                    <a class="dropdown-item" href="listar_ventas.php">Reporte Ventas</a>
                                </div>
                            </li>



                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    SERVICIOS
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="crear_servicio.php">Nuevo Servicios</a>
                                    <a class="dropdown-item" href="lista_servicios.php">Listar Servicios</a>
                                    <a class="dropdown-item" href="crear_paquetes.php">Nuevo Paquete</a>
                                    <a class="dropdown-item" href="lista_paquetes.php">Listar Paquetes</a>

                                </div>
                            </li>
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

    </html>
<?php
} else {
    header("location:../index.php");
}
