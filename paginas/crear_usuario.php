<?php
include 'header.php';
$pagina = PAGINAS::CREAR_USUARIOS;
if (!Seguridad::tiene_permiso($rol, $pagina, ACCIONES::VER)) {
    header("location:./inicio.php?status=AD");
}
$status = $_GET['status'];

if (isset($status)) {
	
    if ($status === 'OK') {
        $error = '<div class="alert alert-success">Usuario creado correctamente</div>';
        //$class = 'class="alert alert-success alert-dismissible fade show" role="alert"';
    } else {
        $error = '<div class="alert alert-danger">Ocurrió un error al crear el usuario</div>';
        //$class = 'class="alert alert-danger alert-dismissible fade show" role="alert"';
    }
}
?>

<body>
    <section class="cuerpo">       
        <div class="row">
            <div class="col-md-4">
				<?php echo isset($error) ? $error : ''; ?>
                <h1>Crear usuario</h1><br>
                <form action="adm_usuario.php" method="post" onsubmit="return validar()">
                    <input class="form-control" placeholder="Usuario" id="usuario" name="usuario" required />
                    <input class="form-control" placeholder="Nombre" id="nombre" name="nombre" required />
                    <input class="form-control" placeholder="Apellidos" id="apellidos" name="apellidos" required />
                    <input class="form-control" placeholder="Teléfono" id="telefono" name="telefono" required />
                    <select class="form-control" id="rol" name="rol" required>
                        <option value="" selected="" hidden="">Seleccione el rol</option>
                        <option value="adm">Administrador</option>
                        <option value="asi">Asistente</option>
                        <option value="doc">Doctor</option>
                        <option value="fis">Fisiterapeuta</option>
                    </select><br>
                    <input class="btn btn-primary" type="submit" name="btn_crear_usuario" id="btn_crear_usuario" value="Aceptar" />
                    <a class="btn btn-danger" href="lista_usuarios.php">Cancelar</a>
                </form>
                <div id="miDiv" class="alert alert-danger" role="alert" style="display: none"></div>
            </div>
        </div>
    </section>
    <br>
    <?php
    include 'footer.php';
    ?>
</body>