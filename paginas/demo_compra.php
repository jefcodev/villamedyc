<?php
include 'header.php';

?>

<body>

    <body>
        <div class="cuerpo">


            <!-- Contenido principal de tu página -->
            <form id="form_doctor">
                <label for="doctor_select">Seleccionar Doctor:</label>
                <select id="doctor_select" name="doctor_id">

                    <option value="todos">Todos</option>
                    <?php
                    // Consultar la tabla de usuarios para obtener la lista de doctores
                    $result = mysqli_query($mysqli, "SELECT id, nombre FROM usuarios WHERE rol = 'doc' OR rol = 'adm' OR  rol = 'fis' ");
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                    }
                    ?>
                </select>
            </form>

            <!-- Div para mostrar dinámicamente el segundo select -->
           
            <div id="citas_container">

            </div>
        </div>


        <?php
        include 'footer.php';
        ?>

<script>
    // Función para cargar las citas al seleccionar un doctor o al cargar la página
    function cargarCitas(doctor_id) {
        // Realizar una solicitud AJAX para obtener las citas del doctor seleccionado
        $.ajax({
            url: 'buscar_citas.php',
            method: 'POST',
            data: {
                doctor_id: doctor_id
            },
            success: function(response) {
                // Insertar el contenido del segundo select en el div con id "citas_container"
                $('#citas_container').html(response);
            }
        });
    }

    $(document).ready(function() {
        // Llamar a la función al cargar la página para obtener todas las citas
        cargarCitas();

        // Agregar evento de cambio al select de doctores
        $('#doctor_select').on('change', function() {
            var doctor_id = $(this).val();
            // Llamar a la función al cambiar el select de doctores
            cargarCitas(doctor_id);
        });
    });
</script>

    </body>

    </html>