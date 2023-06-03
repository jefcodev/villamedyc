<?php
include 'header.php';
?>
<head> 
    <link rel="stylesheet" href="../css/jquery.datetimepicker.css">
</head>
<body>
    <section class="cuerpo">
        <h1>Crear producto</h1><br>
        <form action="adm_producto.php" method="post" onsubmit="return validar()">
            <div class="row">
                <div class="col-md-4">   
                    <input class="form-control" title="Código" placeholder="SEP-123.." id="codigo" name="codigo" required/>
                    <input class="form-control" title="Nombre" placeholder="Nombre" id="nombre" name="nombre" required/>
                    <input class="form-control" title="Descripción" placeholder="Descripción" id="descripcion" name="descripcion" required/>
                    <input class="form-control" type="text" autocomplete="off" placeholder="Fecha nacimiento" id="fecha_nacimiento" name="fecha_nacimiento"/>
                    <select class="form-control" title="Género" id="genero" name="genero">
                        <option value="" selected="" hidden="">Seleccione el Género</option>                  
                        <option value="Masculino">Maculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                    <input class="form-control" title="Teléfono móvil" placeholder="Teléfono móvil" id="telefono_movil" name="telefono_movil" required/>
                    <input class="form-control" title="Teléfono fijo" placeholder="Teléfono fijo" id="telefono_fijo" name="telefono_fijo" />
                    <input class="form-control" title="Dirección" placeholder="Dirección" id="direccion" name="direccion" required/>

                    <div class="float-right">
                        <input class="btn btn-primary" type="submit" name="btn_crear_producto" id="btn_crear_producto" value="Aceptar" />
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
