<?php
include '../conection/conection.php';

$conn =  $mysqli;

$sql = "SELECT vc.id AS venta_id, vc.fecha_venta, vd.detalle_id, vd.cantidad, vd.subtotal
        FROM ventas_cabecera AS vc
        INNER JOIN ventas_detalle AS vd ON vc.id = vd.venta_id";

?>


<!DOCTYPE html>
<html>
<head>
    <title>Lista de Ventas</title>
</head>
<body>
    <h1>Lista de Ventas</h1>
    <select class="form-control" title="Empresa" id="empresa" name="empresa">
        <option value="" selected hidden>Seleccione la Empresa</option>
        <?php
        $query_empresas = "SELECT id, nombre FROM empresa";
        $result_empresas = $mysqli->query($query_empresas);

        while ($row_empresa = $result_empresas->fetch_assoc()) {
            echo '<optgroup label="' . $row_empresa['nombre'] . '" data-id="' . $row_empresa['id'] . '"></optgroup>';
        }
        ?>
    </select>

    <select class="form-control" title="Opciones" id="opciones" name="opciones">
        <!-- Las opciones se llenarán dinámicamente mediante JavaScript -->
    </select>

    <script>
        document.getElementById("empresa").addEventListener("change", function () {
            var empresaSelect = document.getElementById("empresa");
            var opcionesSelect = document.getElementById("opciones");
            var selectedOptgroup = empresaSelect.options[empresaSelect.selectedIndex];
            var empresaId = selectedOptgroup.getAttribute("data-id");

            // Limpia las opciones actuales
            opcionesSelect.innerHTML = '';

            // Consulta las opciones correspondientes a la empresa seleccionada (debes implementar esta lógica en PHP)
            var opciones = obtenerOpcionesPorEmpresa(empresaId);

            // Agrega las nuevas opciones al segundo select
            opciones.forEach(function (opcion) {
                var option = document.createElement("option");
                option.value = opcion.value;
                option.text = opcion.text;
                opcionesSelect.appendChild(option);
            });
        });

        // Esta función debe obtener las opciones correspondientes a la empresa seleccionada
        function obtenerOpcionesPorEmpresa(empresaId) {
            // Debes implementar esta lógica en PHP y devolver las opciones en un formato adecuado (array de objetos)
            // Aquí solo es un ejemplo
            return [
                { value: "1", text: "Opción 1" },
                { value: "2", text: "Opción 2" },
                // Agrega más opciones según tus necesidades
            ];
        }
    </script>
</body>
</html>

<!-- 

<select class="form-control" id="limitacion_movilidad" name="limitacion_movilidad" title="Limitación de la Movilidad">
                                    <option value="0">Seleccione</option>
                                    <option value="1">Crujidos</option>
                                    <optgroup label="Osteoarticular">
                                        <option value="2">Topes articulares</option>
                                        <option value="3">Musculo Tendinosos</option>
                                    </optgroup>
                                </select>
 -->