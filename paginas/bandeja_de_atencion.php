<?php
include 'header.php';
$id_cita = $_GET['id_cita'];
?>
<link rel="stylesheet" href="../css/bootstrap_select.min.css">

<body>
    <section class="cuerpo">
        <h1>Atención al paciente</h1><br>
        <?php
        $sql_datos_cita = "SELECT * FROM citas WHERE id='$id_cita'";
        $result_datos_cita = $mysqli->query($sql_datos_cita);
        $rowscita = $result_datos_cita->fetch_assoc();
        $id_paciente = $rowscita['id_paciente'];

        $sql_datos_paciente = "SELECT * FROM pacientes WHERE id='$id_paciente'";
        $result_datos_paciente = $mysqli->query($sql_datos_paciente);
        $rowspaciente = $result_datos_paciente->fetch_assoc();
        ?>
        <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8">
            <b style="font-size: 18px">Datos del paciente</b><br><br>
            <div class="row" id="edicion_paciente">
                <div class="col-md-4">
                    <input type="hidden" id="id_paciente" name="id_paciente" value="<?php echo $rowspaciente['id']; ?>" />
                    <input class="form-control" title="Cédula o Pasaporte" placeholder="Cédula o Pasaporte" value="<?php echo $rowspaciente['numero_identidad']; ?>" id="numero_identidad" name="numero_identidad" />
                    <input class="form-control" title="Nombres" placeholder="Nombres" value="<?php echo $rowspaciente['nombres']; ?>" id="nombres" name="nombres" />
                    <input class="form-control" title="Apellidos" placeholder="Apellidos" value="<?php echo $rowspaciente['apellidos']; ?>" id="apellidos" name="apellidos" />
                    <input class="form-control" title="Fecha nacimiento" placeholder="Fecha nacimiento" value="<?php echo $rowspaciente['fecha_nacimiento']; ?>" id="fecha_nacimiento" name="fecha_nacimiento" />
                    <select class="form-control" id="genero" name="genero" title="Seleccione el Género">
                        <option value="<?php echo $rowspaciente['genero']; ?>" selected=""><?php echo $rowspaciente['genero']; ?></option>
                        <option value="Masculino">Maculino</option>
                        <option value="Femenino">Femenino</option>
                    </select>
                    <input class="form-control" title="Teléfono fijo" placeholder="Teléfono fijo" value="<?php echo $rowspaciente['telefono_fijo']; ?>" id="telefono_fijo" name="telefono_fijo" />
                </div>
                <div class="col-md-4">
                    <input class="form-control" title="Teléfono móvil" placeholder="Teléfono móvil" value="<?php echo $rowspaciente['telefono_movil']; ?>" id="telefono_movil" name="telefono_movil" />
                    <input class="form-control" title="Dirección" placeholder="Dirección" value="<?php echo $rowspaciente['direccion']; ?>" id="direccion" name="direccion" />
                    <select class="form-control" id="raza" name="raza" title="Seleccione la Raza">
                        <option value="<?php echo $rowspaciente['raza']; ?>" selected=""><?php echo $rowspaciente['raza']; ?></option>
                        <option value="Mestiza">Mestiza</option>
                        <option value="Negra">Negra</option>
                        <option value="Blanca">Blanca</option>
                    </select>
                    <input class="form-control" title="Ocupación" placeholder="Ocupación" value="<?php echo $rowspaciente['ocupacion']; ?>" id="ocupacion" name="ocupacion" />
                    <select class="form-control" id="estado_civil" name="estado_civil" title="Seleccione Estado Civil">
                        <option value="<?php echo $rowspaciente['estado_civil']; ?>" selected=""><?php echo $rowspaciente['estado_civil']; ?></option>
                        <option value="Casado">Casado</option>
                        <option value="Soltero">Soltero</option>
                        <option value="Union libre">Unión libre</option>
                    </select>
                    <input class="form-control" title="Correo electrónico" placeholder="Correo electrónico" value="<?php echo $rowspaciente['correo_electronico']; ?>" id="correo_electronico" name="correo_electronico" />
                </div>
                <div class="col-md-4">
                    <textarea class="form-control" title="Antecedentes personales" placeholder="Antecedentes personales" id="antecedentes_personales" name="antecedentes_personales"><?php echo $rowspaciente['antecedentes_personales']; ?></textarea>
                    <textarea class="form-control" title="Antecedentes familiares" placeholder="Antecedentes familiares" id="antecedentes_familiares" name="antecedentes_familiares"><?php echo $rowspaciente['antecedentes_familiares']; ?></textarea><br><br><br>
                    <input class="btn btn-primary float-right" type="button" name="btn_actualizar_paciente" id="btn_actualizar_paciente" value="Guardar datos del paciente" onclick="editar_paciente()" />
                </div>
            </div>
        </div><br>

        <form action="" id="formulario_consulta">
            <div style="padding: 1% 2% 1% 2%; background-color: #D8D8D8">
                <div class="row">
                    <div class="col-md-6">
                        <b style="font-size: 18px">Datos de la consulta</b>
                    </div>
                    <div class="col-md-6">
                        <a class="btn btn-primary btn-sm float-rigth" href="historia_clinica.php?id_paciente=<?php echo $id_paciente ?>" target="_blank"><i style="font-size:18px" class="fas fa-eye"></i> Historia Clínica</a>
                    </div>
                </div>
                <br>
                <div id="crear_consulta">
                    <div class="row">
                        <div class="col-md-6">
                            <textarea class="form-control" title="Motivo de la consulta" placeholder="Motivo de la consulta" id="motivo_consulta" name="motivo_consulta" required></textarea>
                            <textarea class="form-control" title="Examen físico" placeholder="Examen físico" id="examen_fisico" name="examen_fisico" required></textarea>
                            <textarea class="form-control" title="Tratamiento" placeholder="Tratamiento" id="tratamiento" name="tratamiento" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <select class="selectpicker" data-live-search="true" id="cie_10" title="CIE DIEZ">
                                <?php
                                $result_cie_10 = $mysqli->query("SELECT * FROM cie_diez");
                                while ($row_cie10 = mysqli_fetch_array($result_cie_10)) {
                                    echo '<option data-tokens="' . $row_cie10['codigo'] . '">' . $row_cie10['codigo'] . '-' . $row_cie10['descripcion'] . '</option>';
                                }
                                ?>
                            </select><br><br>
                            <textarea class="form-control" title="Diagnóstico" placeholder="Diagnóstico" id="diagnostico" name="diagnostico" required></textarea>
                            <textarea class="form-control" title="Observaciones" placeholder="Observaciones" id="observaciones" name="observaciones" required></textarea>
                            <input class="form-control" type="number" title="Días de certificado: no puede exceder los 60 días" placeholder="Días de certificado" id="certificado" name="certificado" min="1" max="60" required />
                        </div>
                    </div>



                    <b style="font-size: 18px">Datos</b><br><br>

                    <div class="row">
                        <div class="col-md-3">
                            <input class="form-control" type="number" placeholder="Peso" id="peso" name="peso" required oninput="calcularIMC();">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" type="number" placeholder="Talla" id="talla" name="talla" required oninput="calcularIMC();">
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" type="number" placeholder="IMC" id="imc" name="imc" readonly>
                        </div>
                    </div>

                    <div class="row">


                        <div class="col-md-3">
                            <input class="form-control" type="number" placeholder="Presión" id="presion" name="presion" required></input>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" type="number" placeholder="Saturación" id="saturacion" name="saturacion" required></input>
                        </div>
                    </div>


                    <b style="font-size: 18px">Detalles de la consulta</b><br><br>

                    <div class="row">

                        <div class="col-md-12">
                            <textarea class="form-control" title="Detalles precio" placeholder="Describa los procedimientos de la consulta" id="descripcion_precio" name="descripcion_precio" required></textarea>
                        </div>

                    </div>
                    <b style="font-size: 18px">Receta</b><br>
                    <label for=""> Crear receta</label>
                    <input type="checkbox" id="hacerReceta" name="hacerReceta">
                    <div id="formularioReceta" style="display: none;">
                        <div class="row">
                            <div class="col-md-6">
                                <label for="">Receta</label>
                                <textarea class="form-control" id="receta" name="receta" placeholder="Receta"></textarea>
                            </div>
                            <div class="col-md-6">
                                <label for="">Indicaciones</label>
                                <textarea class="form-control" id="indicaciones" name="indicaciones" placeholder="Indicaciones"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <input type="hidden" id="id_cita" name="id_cita" value="<?php echo $id_cita; ?>" />
                        </div>
                        <div class="col-md-6">
                            <button class="btn btn-primary float-right" type="button" name="btn_crear_consulta" id="btn_crear_consulta" onclick="validarYCrearConsulta()">Guardar datos de la consulta</button><br>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </section>
    <br>
    <?php
    include 'footer.php';
    ?>

    <script src="../js/bootstrap-select.js"></script>
    <script type="text/javascript">
        document.getElementById("hacerReceta").addEventListener("change", function() {
            var formularioReceta = document.getElementById("formularioReceta");
            formularioReceta.style.display = this.checked ? "block" : "none";
        });


        var select = document.getElementById("cie_10");
        var tArea = document.getElementById("diagnostico");
        select.onchange = function() {
            if (tArea.value.length === 0) {
                tArea.value = select.options[select.selectedIndex].value;
            } else {
                tArea.value = tArea.value + '\n' + select.options[select.selectedIndex].value;
            }
        }

        function validarYCrearConsulta() {
            var motivoConsulta = document.getElementById("motivo_consulta").value;
            var examenFisico = document.getElementById("examen_fisico").value;
            var tratamiento = document.getElementById("tratamiento").value;
            var diagnostico = document.getElementById("diagnostico").value;
            var observaciones = document.getElementById("observaciones").value;
            var certificado = document.getElementById("certificado").value;
            var descripcionPrecio = document.getElementById("descripcion_precio").value;

            if (motivoConsulta === "" || examenFisico === "" || tratamiento === "" || diagnostico === "" || observaciones === "" || certificado === "" || descripcionPrecio === "") {
                Swal.fire({
                    icon: 'info',
                    title: 'Campos incompletos',
                    text: 'Por favor, complete todos los campos antes de guardar la consulta.',
                });
            } else {
                crear_consulta();
            }
        }

        function calcularIMC() {
            var peso = parseFloat(document.getElementById("peso").value);
            var talla = parseFloat(document.getElementById("talla").value);

            if (!isNaN(peso) && !isNaN(talla) && talla > 0) {
                var imc = peso / (talla * talla);
                document.getElementById("imc").value = imc.toFixed(2); // Redondear a 2 decimales
            } else {
                document.getElementById("imc").value = ""; // Limpiar el campo si los valores son inválidos
            }
        }

        function editar_paciente() {
            var id_paciente = $("#id_paciente").val();
            var numero_identidad = $("#numero_identidad").val();
            var nombres = $("#nombres").val();
            var apellidos = $("#apellidos").val();
            var fecha_nacimiento = $("#fecha_nacimiento").val();
            var genero = document.getElementById("genero").value;
            var telefono_fijo = $("#telefono_fijo").val();
            var telefono_movil = $("#telefono_movil").val();
            var direccion = $("#direccion").val();
            var raza = document.getElementById("raza").value;
            var ocupacion = $("#ocupacion").val();
            var estado_civil = document.getElementById("estado_civil").value;
            var correo_electronico = $("#correo_electronico").val();
            var antecedentes_personales = document.getElementById("antecedentes_personales").value;
            var antecedentes_familiares = document.getElementById("antecedentes_familiares").value;
            $.ajax({
                url: 'editar_paciente_ajax.php',
                type: 'post',
                data: {
                    id_paciente: id_paciente,
                    numero_identidad: numero_identidad,
                    nombres: nombres,
                    apellidos: apellidos,
                    fecha_nacimiento: fecha_nacimiento,
                    genero: genero,
                    telefono_fijo: telefono_fijo,
                    telefono_movil: telefono_movil,
                    direccion: direccion,
                    raza: raza,
                    ocupacion: ocupacion,
                    estado_civil: estado_civil,
                    correo_electronico: correo_electronico,
                    antecedentes_personales: antecedentes_personales,
                    antecedentes_familiares: antecedentes_familiares,
                },
                success: function(response) {
                    $("#edicion_paciente").html(response);
                }
            });
        }

        function crear_consulta() {
            var id_paciente = $("#id_paciente").val();
            var motivo_consulta = document.getElementById("motivo_consulta").value;
            var precio = $("#precio").val();
            var descripcion_precio = document.getElementById("descripcion_precio").value;
            var examen_fisico = document.getElementById("examen_fisico").value;
            var diagnostico = document.getElementById("diagnostico").value;
            var observaciones = document.getElementById("observaciones").value;
            var tratamiento = $("#tratamiento").val();
            var certificado = $("#certificado").val();
            var id_cita = $("#id_cita").val();

            var peso = $("#peso").val();
            var talla = $("#talla").val();
            var presion = $("#presion").val();
            var saturacion = $("#saturacion").val();
            var receta = $("#receta").val();
            var indicaciones = $("#indicaciones").val();

            $.ajax({
                url: 'crear_consulta.php',
                type: 'post',
                data: {
                    id_paciente: id_paciente,
                    motivo_consulta: motivo_consulta,
                    examen_fisico: examen_fisico,
                    diagnostico: diagnostico,
                    tratamiento: tratamiento,
                    id_cita: id_cita,
                    certificado: certificado,
                    observaciones: observaciones,
                    precio: precio,
                    descripcion_precio: descripcion_precio,
                    peso: peso,
                    talla: talla,
                    presion: presion,
                    saturacion: saturacion,
                    receta: receta,
                    indicaciones: indicaciones
                },
                success: function(response) {
                    $("#crear_consulta").html(response);
                }
            });
        }
    </script>
</body>