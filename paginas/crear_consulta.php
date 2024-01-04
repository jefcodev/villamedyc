<?php

include '../conection/conection.php';
session_start();

if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']))) {
    $usuario = $_SESSION['usuario'];
    date_default_timezone_set('America/Bogota');
    $fecha_hora = date("Y-m-d H:i:s");
    $id_paciente = $_POST['id_paciente'];
    $precio = $_POST['precio'];
    $estado = 'pendiente';
    $descripcion_precio = $_POST['descripcion_precio'];
    $motivo_consulta = $_POST['motivo_consulta'];
    $examen_fisico = $_POST['examen_fisico'];
    $diagnostico = $_POST['diagnostico'];
    $tratamiento = $_POST['tratamiento'];
    $observaciones = $_POST['observaciones'];
    $id_cita = $_POST['id_cita'];
    $certificado = $_POST['certificado'];

    /* Datos de IMC */
  	$peso = isset($_POST['peso']) ? floatval($_POST['peso']) : 0;
    $talla = isset($_POST['talla']) ? floatval($_POST['talla']) : 0;
    $presion = isset($_POST['presion']) ? $_POST['presion'] : 0;
    $saturacion = isset($_POST['saturacion']) ? floatval($_POST['saturacion']) : 0;

    /*Datos de receta */
    $receta = isset($_POST['receta']) ? $_POST['receta'] : null;
    $indicaciones = isset($_POST['indicaciones']) ? $_POST['indicaciones'] : null;

    /*Datos receta fisio  */
    $examenes_fisio = isset($_POST['examenes_fisio']) ? $_POST['examenes_fisio'] : null;
    $tratamiento_fisio = isset($_POST['tratamiento_fisio']) ? $_POST['tratamiento_fisio'] : null;

    $hea = isset($_POST['hea']) ? $_POST['hea'] : null;


    
    if (!empty($tratamiento_fisio) && !empty($examenes_fisio) && !empty($receta) && !empty($indicaciones)) {

        $sql_receta_fis = "INSERT INTO receta_fisio (examenes, tratamiento, id_cita) VALUES ('$examenes_fisio', '$tratamiento_fisio', $id_cita)";

        $sql_receta = "INSERT INTO receta (receta, indicaciones, id_cita) VALUES ('$receta', '$indicaciones', $id_cita)";

        if ($mysqli->query($sql_receta_fis)) {
            // La inserción fue exitosa, ahora obtén el ID de la receta
            $id_receta_fis = $mysqli->insert_id;
            if ($mysqli->query($sql_receta)) {
                $id_receta = $mysqli->insert_id;
                echo "<script>
                            Swal.fire({
                                title: 'Imprimir' ,
                                text: 'Imprimir Observaciones Totales!',
                                icon: 'info',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Imprimir!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                
                                var pdfUrl = 'receta_all.php?id_receta=' + $id_receta + '&id_receta_fis=' + $id_receta_fis;


                                var pdfWindow = window.open(pdfUrl, '_blank');

                                pdfWindow.onload = function() {
                                pdfWindow.print();
                                
                                };
                                
                            
                                }
                                
                            }
                            );
                    </script>";
            }
        } else {
            // Manejo de errores si la inserción falla
            echo "Error al insertar la receta: " . $mysqli->error;
        }
    }

    if (!empty($receta) && !empty($indicaciones) && empty($tratamiento_fisio) && empty($examenes_fisio)) {

        $sql_receta = "INSERT INTO receta (receta, indicaciones, id_cita) VALUES ('$receta', '$indicaciones', $id_cita)";
        if ($mysqli->query($sql_receta)) {
            // La inserción fue exitosa, ahora obtén el ID de la receta
            $id_receta = $mysqli->insert_id;
            echo "<script>
            
        Swal.fire({
            title: 'Imprimir' ,
            text: 'Imprimir Receta!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Imprimir!'
          }).then((result) => {
            if (result.isConfirmed) {
             
             var pdfUrl = 'receta.php?id_receta=' + $id_receta; 

            var pdfWindow = window.open(pdfUrl, '_blank');

            pdfWindow.onload = function() {
            pdfWindow.print();
            
            };
            
           
            }
            
          }
        );
          
        
    </script>";
        } else {
            // Manejo de errores si la inserción falla
            echo "Error al insertar la receta: " . $mysqli->error;
        }
    }
    if (!empty($tratamiento_fisio) && !empty($examenes_fisio) && empty($receta) && empty($indicaciones)) {
        $sql_receta_fis = "INSERT INTO receta_fisio (examenes, tratamiento, id_cita) VALUES ('$examenes_fisio', '$tratamiento_fisio', $id_cita)";
        if ($mysqli->query($sql_receta_fis)) {
            // La inserción fue exitosa, ahora obtén el ID de la receta
            $id_receta_fis = $mysqli->insert_id;
            echo "<script>
            
        Swal.fire({
            title: 'Imprimir' ,
            text: 'Imprimir Observaciones!',
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Imprimir!'
          }).then((result) => {
            if (result.isConfirmed) {
             
             var pdfUrl = 'receta_fisio.php?id_receta=' + $id_receta_fis; 

            var pdfWindow = window.open(pdfUrl, '_blank');

            pdfWindow.onload = function() {
            pdfWindow.print();
            
            };
            
           
            }
            
          }
        );
          
        
    </script>";
        } else {
            // Manejo de errores si la inserción falla
            echo "Error al insertar la receta: " . $mysqli->error;
        }
    }

    $sql = "INSERT INTO consultas (id_paciente,motivo_consulta,examen_fisico,diagnostico,tratamiento,fecha_hora, id_cita, certificado, observaciones, descripcion_precio, estado, peso, talla, presion, saturacion, hea) VALUES ('$id_paciente','$motivo_consulta','$examen_fisico','$diagnostico','$tratamiento','$fecha_hora', '$id_cita', '$certificado', '$observaciones','$descripcion_precio','$estado', '$peso', '$talla','$presion','$saturacion', '$hea')";
    $result = $mysqli->query($sql);
    $resultado = "";
    if ($result === true) {
        $mysqli->query("update citas set consultado='si' where id='$id_cita'");
        $resultado_consulta = $mysqli->query("select id from consultas where id_cita='$id_cita' and id_paciente = '$id_paciente'");
        $id_consulta = $resultado_consulta->fetch_assoc();
        $resultado = $resultado . '<div class="row">';
        $resultado = $resultado . '<div class="col-md-6">';
        $resultado = $resultado . '<textarea class="form-control" placeholder="Motivo de la consulta" id="motivo_consulta" name="motivo_consulta">' . $motivo_consulta . '</textarea>';
        $resultado = $resultado . '<textarea class="form-control" placeholder="Precio de la consulta" id="precio" name="precio">' . $precio . '</textarea>';
        $resultado = $resultado . '<textarea class="form-control" placeholder="Descripción de precio de la consulta" id="descripcion_precio" name="descripcion_precio">' . $descripcion_precio . '</textarea>';
        $resultado = $resultado . '<textarea class="form-control" placeholder="Examen físico" id="examen_fisico" name="examen_fisico">' . $examen_fisico . '</textarea>';
        $resultado = $resultado . '<textarea class="form-control" placeholder="Tratamiento" id="tratamiento" name="tratamiento">' . $tratamiento . '</textarea></div>';
        $resultado = $resultado . '<div class="col-md-6">';
        $id_consulta_edit = $id_consulta['id'];
        $resultado = $resultado . '<textarea class="form-control" placeholder="Diagnóstico" id="diagnostico" name="diagnostico">' . $diagnostico . '</textarea>';
        $resultado = $resultado . '<textarea class="form-control" placeholder="Oservaciones" id="observaciones" name="observaciones">' . $observaciones . '</textarea>';
        $resultado = $resultado . '<input class="form-control" type="number" title="Días de certificado: no puede exceder los 60 días" placeholder="Días de certificado" id="certificado" name="certificado" value ="' . $certificado . '" min="1" max="60"/>';
        $resultado = $resultado . '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        $resultado = $resultado . 'Consulta registrada con éxito. Para modificarla en el siguiente enlace <a class="btn btn-success btn-sm" href="editar_consulta.php?id_consulta=' . $id_consulta_edit . '">Aquí</a> <button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $resultado = $resultado . '<span aria-hidden="true">&times;</span>';
        $resultado = $resultado . '</button></div>';
    } else {
        $resultado = $resultado . '<div class="alert alert-success alert-dismissible fade show" role="alert">';
        $resultado = $resultado . 'No se pudieron editar los datos hubo un error <button style="top: -2.35rem" type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $resultado = $resultado . '<span aria-hidden="true">&times;</span>';
        $resultado = $resultado . '</button></div>';
        var_dump($observaciones);
        var_dump($sql);
        echo 'Error' . $mysqli->error;
    }
    echo $resultado;
}
