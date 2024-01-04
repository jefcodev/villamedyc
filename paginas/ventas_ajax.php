<?php

include '../conection/conection.php';
session_start();
$ACTION = $_POST['action'];
$id_consulta = $_GET['id_consulta'];

if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']))) {
    if (isset($ACTION) && !empty($ACTION)) {
        switch ($ACTION) {
            case 'crear_venta':
                crearVenta($mysqli);
                break;
            case 'ver_venta':
                verVenta($mysqli);
                break;
            case 'ver_fecha_cita':
                verFechaCita($mysqli);
                break;
            case 'ver_cita':
                verCita($mysqli);
                break;
            case 'ver_procedimientos':
                verProcedimientos($mysqli);
                break;
            case 'ver_servicios':
                verServicios($mysqli);
                break;
            case 'ver_productos':
                verProductos($mysqli);
                break;
            case 'ver_evaluacion':
                verEvaluacion($mysqli);
                break;
            case 'ver_sesion':
                verSesion($mysqli);
                break;
            case 'crear_cita':
                crearCita($mysqli);
                break;
            case 'crear_procedimiento':
                crearProcedimiento($mysqli);
                break;
            case 'actualizar_procedimiento':
                actualizarProcedimiento($mysqli);
                break;
            case 'actualizar_evaluacion':
                actualizarEvaluacion($mysqli);
                break;
            case 'actualizar_estado':
                actualizarEstado($mysqli);
                break;
            default:
                # code...
                break;
        }
    }
}

function crearVenta($mysqli)
{
    $PACIENTE_ID = $_POST['paciente_id'];
    $PAQUETE_ID = $_POST['paquete_id'];
    $fechaActual = date("Y-m-d");
    $mysqli->begin_transaction();
    try {
        $query = "INSERT INTO `consultas_fisioterapeuta`(`paciente_id`, `paquete_id`, 
                    `fecha`, `profesion`, `tipo_trabajo`, `sedestacion_prolongada`, `esfuerzo_fisico`, 
                    `habitos`, `antecedentes_diagnostico`, `tratamientos_anteriores`, `contracturas`, 
                    `irradiacion`, `hacia_donde`, `intensidad`, `sensaciones`, `limitacion_movilidad`, `estado_atencion`, `motivo_consulta`)  
                    VALUES ($PACIENTE_ID, $PAQUETE_ID, '$fechaActual', '', '', 0, 0, '', '', '', '', 0, '', '', '', 0, 'Por Atender', '')";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }

        $CONSULTA_FISIO_ID = $mysqli->insert_id;
        $TOTAL = $_POST['total'];

        $query = "INSERT INTO `ventas`(`fecha_venta`, `id_consulta`, `id_paquete`, `total`) 
                    VALUES ('$fechaActual', null, $PAQUETE_ID, $TOTAL)";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }

        $LISTA = json_decode($_POST['lista']);
        foreach ($LISTA as $indice => $element) {
            $stock = $element->stock - $element->cantidad;
            actualizarStock(
                $mysqli,
                $stock,
                $element->pro_ser_id
            );
        }

        $mysqli->commit();
        echo "Venta exitosa";
    } catch (Exception $e) {
        echo ("Error: " . $e->getMessage());
    }
}

function verVenta($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "SELECT cf.consulta_fisio_id, p.id as paciente_id, cf.fecha, CONCAT(p.nombres,' ',p.apellidos) as nombres, pc.paquete_id, pc.titulo_paquete, pc.tipo_paquete, pc.numero_sesiones, pc.total as total_paquete, pd.* 
    FROM paquete_cabecera pc, paquete_detalle pd, consultas_fisioterapeuta cf, pacientes p
    WHERE pc.paquete_id=pd.paquete_id AND cf.paquete_id=pc.paquete_id AND cf.paciente_id=p.id AND cf.consulta_fisio_id= $CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'consulta_fisio_id' => $row['consulta_fisio_id'],
            'numero_historia' => 'VM-001-'.$row['paciente_id'],
            'fecha' => $row['fecha'],
            'nombres' => $row['nombres'],
            'paquete_id' => $row['paquete_id'],
            'titulo_paquete' => $row['titulo_paquete'],
            'tipo_paquete' => $row['tipo_paquete'],
            'numero_sesiones' => $row['numero_sesiones'],
            'total_paquete' => $row['total_paquete'],
            'pro_ser_id' => $row['pro_ser_id'],
            'nombre' => $row['nombre'],
            'tipo' => $row['tipo'],
            'costo' => $row['costo'],
            'cantidad' => $row['cantidad'],
            'total' => $row['total']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verFechaCita($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "SELECT fecha, estado_atencion FROM consultas_fisioterapeuta WHERE consulta_fisio_id = $CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'fecha' => $row['fecha'],
            'estado_atencion' => $row['estado_atencion']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verCita($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "SELECT * FROM consultas_fisioterapeuta WHERE consulta_fisio_id = $CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'consulta_fisio_id' => $row['consulta_fisio_id'],
            'profesion' => $row['profesion'],
            'tipo_trabajo' => $row['tipo_trabajo'],
            'sedestacion_prolongada' => $row['sedestacion_prolongada'],
            'esfuerzo_fisico' => $row['esfuerzo_fisico'],
            'habitos' => $row['habitos'],
            'antecedentes_diagnostico' => $row['antecedentes_diagnostico'],
            'tratamientos_anteriores' => $row['tratamientos_anteriores'],
            'contracturas' => $row['contracturas'],
            'irradiacion' => $row['irradiacion'],
            'hacia_donde' => $row['hacia_donde'],
            'intensidad' => $row['intensidad'],
            'sensaciones' => $row['sensaciones'],
            'limitacion_movilidad' => $row['limitacion_movilidad'],
            'motivo_consulta' => $row['motivo_consulta']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verProcedimientos($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "SELECT consulta_fisio_detalle_id, consulta_fisio_id FROM consultas_fisioterapeuta_detalle WHERE consulta_fisio_id=$CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'consulta_fisio_detalle_id' => $row['consulta_fisio_detalle_id'],
            'consulta_fisio_id' => $row['consulta_fisio_id']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verServicios($mysqli)
{
    $CONSULTA_FISIO_DETALLE_ID = $_POST['consulta_fisio_detalle_id'];
    $query = "SELECT * FROM consultas_fisioterapeuta_detalle WHERE consulta_fisio_detalle_id=$CONSULTA_FISIO_DETALLE_ID ";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'consulta_fisio_detalle_id' => $row['consulta_fisio_detalle_id'],
            'electroestimulacion' => $row['electroestimulacion'],
            'ultrasonido' => $row['ultrasonido'],
            'magnetoterapia' => $row['magnetoterapia'],
            'laserterapia' => $row['laserterapia'],
            'termoterapia' => $row['termoterapia'],
            'masoterapia' => $row['masoterapia'],
            'crioterapia' => $row['crioterapia'],
            'malibre' => $row['malibre'],
            'maasistida' => $row['maasistida'],
            'fmuscular' => $row['fmuscular'],
            'propiocepcion' => $row['propiocepcion'],
            'epunta' => $row['epunta'],
            'observaciones'=> $row['observaciones']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verProductos($mysqli)
{
    $PAQUETE_ID = $_POST['paquete_id'];
    $query = "SELECT pd.*, p.stock FROM paquete_detalle pd, productos p 
                WHERE pd.pro_ser_id = p.id AND pd.tipo='Producto' AND pd.paquete_id=$PAQUETE_ID ";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'paquete_id' => $row['paquete_id'],
            'pro_ser_id' => $row['pro_ser_id'],
            'nombre' => $row['nombre'],
            'tipo' => $row['tipo'],
            'costo' => $row['costo'],
            'cantidad' => $row['cantidad'],
            'total' => $row['total'],
            'stock' => $row['stock']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verEvaluacion($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "SELECT * FROM consultas_fisioterapeuta WHERE consulta_fisio_id=$CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'profesion' => $row['profesion'],
            'tipo_trabajo' => $row['tipo_trabajo'],
            'sedestacion_prolongada' => $row['sedestacion_prolongada'],
            'esfuerzo_fisico' => $row['esfuerzo_fisico'],
            'habitos' => $row['habitos'],
            'antecedentes_diagnostico' => $row['antecedentes_diagnostico'],
            'tratamientos_anteriores' => $row['tratamientos_anteriores'],
            'contracturas' => $row['contracturas'],
            'irradiacion' => $row['irradiacion'],
            'hacia_donde' => $row['hacia_donde'],
            'intensidad' => $row['intensidad'],
            'sensaciones' => $row['sensaciones'],
            'limitacion_movilidad' => $row['limitacion_movilidad'],
            'motivo_consulta' => $row['motivo_consulta'],
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verSesion($mysqli)
{
    $CONSULTA_FISIO_DETALLE_ID = $_POST['consulta_fisio_detalle_id'];
    $query = "SELECT cfd.*, CONCAT(u.nombre, ' ', u.apellidos) as nombres FROM consultas_fisioterapeuta_detalle cfd, usuarios u 
                WHERE cfd.usuario_id=u.id AND cfd.consulta_fisio_detalle_id =$CONSULTA_FISIO_DETALLE_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'fecha' => $row['fecha'],
            'nombres' => $row['nombres'],
            'electroestimulacion' => $row['electroestimulacion'],
            'ultrasonido' => $row['ultrasonido'],
            'magnetoterapia' => $row['magnetoterapia'],
            'laserterapia' => $row['laserterapia'],
            'termoterapia' => $row['termoterapia'],
            'masoterapia' => $row['masoterapia'],
            'crioterapia' => $row['crioterapia'],
            'malibre' => $row['malibre'],
            'maasistida' => $row['maasistida'],
            'fmuscular' => $row['fmuscular'],
            'propiocepcion' => $row['propiocepcion'],
            'epunta' => $row['epunta'],
            'observaciones'=> $row['observaciones']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function crearCita($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_cita_id'];
    $USUARIO_ID = $_POST['doctor_id'];
    $FECHA = $_POST['fecha'];

    $query = "UPDATE `consultas_fisioterapeuta` SET `fecha`='$FECHA', `estado_atencion`='Cita Asignada' 
                WHERE consulta_fisio_id=$CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    echo "Cita Creada";
}

function crearProcedimiento($mysqli)
{
    date_default_timezone_set('America/Guayaquil');
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $ID_CITA = $_POST['id_cita'];
    $ELECTROESTIMULACION = $_POST['electroestimulacion'];
    $ULTRASONIDO = $_POST['ultrasonido'];
    $MAGNETOTERAPIA = $_POST['magnetoterapia'];
    $LASERTERAPIA = $_POST['laserterapia'];
    $TERMOTERAPIA = $_POST['termoterapia'];
    $MASOTERAPIA = $_POST['masoterapia'];
    $CRIOTERAPIA = $_POST['crioterapia'];
    $MALIBRE = $_POST['malibre'];
    $MAASISTIDA = $_POST['maasistida'];
    $FMUSCULAR = $_POST['fmuscular'];
    $PROPIOCEPCION = $_POST['propiocepcion'];
    $EPUNTA = $_POST['epunta'];
    $OBSERVACIONES = $_POST['observaciones'];
    $USUARIO_ID = $_SESSION['id'];
    $fechaYHoraActual = date("Y-m-d H:i:s");
    $query = "INSERT INTO `consultas_fisioterapeuta_detalle`(`consulta_fisio_id`, `usuario_id`, 
                    `fecha`, `electroestimulacion`, `ultrasonido`, `magnetoterapia`, `laserterapia`, 
                    `termoterapia`, `masoterapia`, `crioterapia`, `malibre`, `maasistida`, 
                    `fmuscular`, `propiocepcion`, `epunta`,`fk_id_cita`, `observaciones`) 
                    VALUES ($CONSULTA_FISIO_ID, $USUARIO_ID, '$fechaYHoraActual', $ELECTROESTIMULACION, $ULTRASONIDO, $MAGNETOTERAPIA, 
                    $LASERTERAPIA, $TERMOTERAPIA, $MASOTERAPIA, $CRIOTERAPIA, $MALIBRE, $MAASISTIDA, 
                    $FMUSCULAR, $PROPIOCEPCION, $EPUNTA, $ID_CITA, '$OBSERVACIONES')";
    $result = $mysqli->query($query);

    if (!$result) {

        die('Query Failed 12'. $$mysqli->error);
        echo  $$mysqli->error;
    }

    // Actualizar el campo numero_sesiones en la tabla consultas_fisioterapeuta
    $queryUpdate = "UPDATE `consultas_fisioterapeuta` 
                    SET `numero_sesiones` = `numero_sesiones` - 1 
                    WHERE `consulta_fisio_id` = $CONSULTA_FISIO_ID";

    $resultUpdate = $mysqli->query($queryUpdate);

    if (!$resultUpdate) {
        die('Query Update Failed.');
    }

    $mysqli->query("update citas set consultado='si' where id='$ID_CITA'");
    

    echo "Procedimiento Registrado";
}

function actualizarStock($mysqli, $stock, $id)
{
    $query = "UPDATE `productos` SET `stock`=$stock
                WHERE id=$id";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
}

function actualizarProcedimiento($mysqli)
{
    date_default_timezone_set('America/Guayaquil');
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $CONSULTA_FISIO_DETALLE_ID = $_POST['consulta_fisio_detalle_id'];
    $ELECTROESTIMULACION = $_POST['electroestimulacion'];
    $ULTRASONIDO = $_POST['ultrasonido'];
    $MAGNETOTERAPIA = $_POST['magnetoterapia'];
    $LASERTERAPIA = $_POST['laserterapia'];
    $TERMOTERAPIA = $_POST['termoterapia'];
    $MASOTERAPIA = $_POST['masoterapia'];
    $CRIOTERAPIA = $_POST['crioterapia'];
    $MALIBRE = $_POST['malibre'];
    $MAASISTIDA = $_POST['maasistida'];
    $FMUSCULAR = $_POST['fmuscular'];
    $PROPIOCEPCION = $_POST['propiocepcion'];
    $EPUNTA = $_POST['epunta'];
    $USUARIO_ID = $_SESSION['id'];
    $fechaActual = date("Y-m-d H:i:s");
    $OBSERVACIONES = $_POST['observaciones'];

    $query = "UPDATE consultas_fisioterapeuta_detalle SET `usuario_id`=$USUARIO_ID, `fecha`='$fechaActual', `electroestimulacion`=$ELECTROESTIMULACION, `ultrasonido`=$ULTRASONIDO, 
                `magnetoterapia`=$MAGNETOTERAPIA, `laserterapia`=$LASERTERAPIA, `termoterapia`=$TERMOTERAPIA, `masoterapia`=$MASOTERAPIA, 
                `crioterapia`=$CRIOTERAPIA,`malibre`=$MALIBRE, `maasistida`=$MAASISTIDA, `fmuscular`=$FMUSCULAR,`propiocepcion`=$PROPIOCEPCION,
                `epunta`=$EPUNTA, `observaciones`='$OBSERVACIONES' WHERE consulta_fisio_detalle_id=$CONSULTA_FISIO_DETALLE_ID";
    $result = $mysqli->query($query);


    if (!$result) {
        die('Query Failed.');
    }
    echo "Procedimiento Actualizado";
}

function actualizarEvaluacion($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $PROFESION = $_POST['profesion'];
    $TIPO_TRABAJO = $_POST['tipo_trabajo'];
    $SEDESTACION_PROLONGADA = $_POST['sedestacion_prolongada'];
    $ESFUERZO_FISICO = $_POST['esfuerzo_fisico'];
    $HABITOS = $_POST['habitos'];
    $ANTECEDENTES_DIAGNOSTICO = $_POST['antecedentes_diagnostico'];
    $TRATAMIENTOS_ANTERIORES = $_POST['tratamientos_anteriores'];
    $CONTRACTURAS = $_POST['contracturas'];
    $IRRADIACION = $_POST['irradiacion'];
    $HACIA_DONDE = $_POST['hacia_donde'];
    $INTENSIDAD = $_POST['intensidad'];
    $SENSACIONES = $_POST['sensaciones'];
    $LIMITACION_MOVILIDAD = $_POST['limitacion_movilidad'];
    $MOTIVO_CONSULTA = $_POST['motivo_consulta'];
    $query = "UPDATE `consultas_fisioterapeuta` SET `profesion`='$PROFESION', 
                `tipo_trabajo`='$TIPO_TRABAJO', `sedestacion_prolongada`='$SEDESTACION_PROLONGADA', 
                `esfuerzo_fisico`='$ESFUERZO_FISICO', `habitos`='$HABITOS', `antecedentes_diagnostico`='$ANTECEDENTES_DIAGNOSTICO',
                `tratamientos_anteriores`='$TRATAMIENTOS_ANTERIORES', `contracturas`='$CONTRACTURAS', `irradiacion`='$IRRADIACION', 
                `hacia_donde`='$HACIA_DONDE', `intensidad`='$INTENSIDAD', `sensaciones`='$SENSACIONES', 
                `limitacion_movilidad`='$LIMITACION_MOVILIDAD',`motivo_consulta`='$MOTIVO_CONSULTA'
                WHERE `consulta_fisio_id`='$CONSULTA_FISIO_ID'";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.'.$mysqli->error);
    }

    echo "Evaluación Actualizada";
    
}

function actualizarEstado($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "UPDATE `consultas_fisioterapeuta` SET `estado_atencion`='Atendido'
                    WHERE consulta_fisio_id=$CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    echo "Atención Finalizada";
}
