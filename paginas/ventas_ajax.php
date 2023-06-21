<?php

include '../conection/conection.php';
session_start();
$ACTION = $_POST['action'];

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
            case 'crear_cita':
                crearCita($mysqli);
                break;
            case 'actualizar_evaluacion':
                actualizarEvaluacion($mysqli);
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
    $NUMERO_HISTORIA = $_POST['numero_historia'];
    echo $PAQUETE_ID;
    $query = "INSERT INTO `consultas_fisioterapeuta`(`paciente_id`, `usuario_id`, `paquete_id`, `numero_historia`, 
                `fecha`, `profesion`, `tipo_trabajo`, `sedestacion_prolongada`, `esfuerzo_fisico`, 
                `habitos`, `antecendentes_diagnostico`, `tratamientos_anteriores`, `contracturas`, 
                `irradiacion`, `hacia_donde`, `intensidad`, `sensaciones`, `limitacion_movilidad`, `estado_atencion`)  
                VALUES ($PACIENTE_ID, 2, $PAQUETE_ID, '$NUMERO_HISTORIA', '', '', '', 0, 0, '', '', '', '', 0, '', '', '', 0, 'Por Asignar Cita')";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    echo "Venta exitosa";
}

function verVenta($mysqli)
{
    $CONSULTA_FISIO_ID = $_POST['consulta_fisio_id'];
    $query = "SELECT cf.consulta_fisio_id, cf.numero_historia, cf.fecha, CONCAT(p.nombres,' ',p.apellidos) as nombres, u.id as usuario_id, CONCAT(u.nombre,' ',u.apellidos) as nombres_doc,pc.paquete_id, pc.titulo_paquete, pc.tipo_paquete, pc.numero_sesiones, pc.total as total_paquete, pd.* 
    FROM paquete_cabecera pc, paquete_detalle pd, consultas_fisioterapeuta cf, pacientes p, usuarios u
    WHERE pc.paquete_id=pd.paquete_id AND cf.paquete_id=pc.paquete_id AND cf.paciente_id=p.id AND u.id=cf.usuario_id AND cf.consulta_fisio_id= $CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'consulta_fisio_id' => $row['consulta_fisio_id'],
            'numero_historia' => $row['numero_historia'],
            'fecha' => $row['fecha'],
            'usuario_id' => $row['usuario_id'],
            'nombres' => $row['nombres'],
            'nombres_doc' => $row['nombres_doc'],
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
    $query = "SELECT usuario_id, fecha, estado_atencion FROM consultas_fisioterapeuta WHERE consulta_fisio_id = $CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'usuario_id' => $row['usuario_id'],
            'fecha' => $row['fecha'],
            'estado_atencion' => $row['estado_atencion']
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

    $query = "UPDATE `consultas_fisioterapeuta` SET `usuario_id`=$USUARIO_ID, `fecha`='$FECHA', `estado_atencion`='Cita Asignada' 
                WHERE consulta_fisio_id=$CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    echo "Cita Creada";
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

    $query = "UPDATE `consultas_fisioterapeuta` SET `profesion`='$PROFESION', 
                `tipo_trabajo`='$TIPO_TRABAJO', `sedestacion_prolongada`=$SEDESTACION_PROLONGADA, 
                `esfuerzo_fisico`=$ESFUERZO_FISICO, `habitos`='$HABITOS', `antecendentes_diagnostico`='$ANTECEDENTES_DIAGNOSTICO', 
                `tratamientos_anteriores`='$TRATAMIENTOS_ANTERIORES', `contracturas`='$CONTRACTURAS', `irradiacion`=$IRRADIACION, 
                `hacia_donde`='$HACIA_DONDE', `intensidad`='$INTENSIDAD', `sensaciones`='$SENSACIONES', 
                `limitacion_movilidad`=$LIMITACION_MOVILIDAD, `estado_atencion`='Atendido'
                WHERE consulta_fisio_id=$CONSULTA_FISIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    echo "Evaluación Actualizada";
}
