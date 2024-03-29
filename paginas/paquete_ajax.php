<?php

include '../conection/conection.php';
session_start();
$ACTION = $_POST['action'];

if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']))) {
    if (isset($ACTION) && !empty($ACTION)) {
        switch ($ACTION) {
            case 'ver_paquetes':
                verPaquetes($mysqli);
                break;
            case 'crear_paquete':
                crearPaquete($mysqli);
                break;
            case 'buscar_productos_servicio':
                buscarProductosServicio($mysqli);
                break;
            case 'ver_paquete':
                verPaquete($mysqli);
                break;
            case 'actualizar_paquete':
                actualizarPaquete($mysqli);
                break;
            case 'eliminar_paquete':
                eliminarPaquete($mysqli);
                break;
            default:
                # code...
                break;
        }
    }
}

function crearPaquete($mysqli)
{
    $TITULO_PAQUETE = $_POST['titulo_paquete'];
    $TIPO_PAQUETE = $_POST['tipo_paquete'];
    $NUMERO_SESIONES = $_POST['numero_sesiones'];
    $TOTAL = $_POST['total'];
    $DESCUENTO = $_POST['descuento'];
    $query = "INSERT INTO `paquete_cabecera`(`titulo_paquete`, `tipo_paquete`, `numero_sesiones`, `total`, `ahorra`) 
        VALUES ('$TITULO_PAQUETE', $TIPO_PAQUETE, $NUMERO_SESIONES, $TOTAL, $DESCUENTO)";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    $PAQUETE_ID = $mysqli->insert_id;
    $LISTA = json_decode($_POST['lista']);
    foreach ($LISTA as $indice => $element) {
        $query = "INSERT INTO `paquete_detalle`(`paquete_id`, `pro_ser_id`, `nombre`, `tipo`, `costo`, `cantidad`, `total`) 
                    VALUES ($PAQUETE_ID, $element->id, '$element->name', '$element->type', $element->cost, $element->amount, $element->total)";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }
    }

    
}

function buscarProductosServicio($mysqli)
{
    $query = "SELECT * FROM deatelle_servicio";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'id_producto' => $row['id_producto'],
            'nombre' => $row['nombre'],
            'precio' => $row['precio'],
            'cantidad' => $row['cantidad'],
            'subtotal' => $row['subtotal']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verPaquetes($mysqli)
{
    $query = "SELECT * FROM paquete_cabecera";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'paquete_id' => $row['paquete_id'],
            'titulo_paquete' => $row['titulo_paquete'],
            'tipo_paquete' => $row['tipo_paquete'],
            'numero_sesiones' => $row['numero_sesiones'],
            'total' => $row['total']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verPaquete($mysqli)
{
    $PAQUETE_ID = $_POST['paquete_id'];
    $query = "SELECT pc.paquete_id, pc.titulo_paquete, pc.tipo_paquete, pc.numero_sesiones, pc.total as total_paquete,
                pd.* FROM paquete_cabecera pc, paquete_detalle pd  
                WHERE pc.paquete_id = pd.paquete_id AND pd.paquete_id =$PAQUETE_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
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

function actualizarPaquete($mysqli)
{
    $PAQUETE_ID = $_POST['paquete_id'];
    $TITULO_PAQUETE = $_POST['titulo_paquete'];
    $TIPO_PAQUETE = $_POST['tipo_paquete'];
    $NUMERO_SESIONES = $_POST['numero_sesiones'];
    $TOTAL = $_POST['total'];

    $query = "UPDATE `paquete_cabecera` SET `titulo_paquete`='$TITULO_PAQUETE',  
                `tipo_paquete`=$TIPO_PAQUETE, `numero_sesiones`=$NUMERO_SESIONES, `total`=$TOTAL 
                WHERE paquete_id=$PAQUETE_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    $query = "DELETE FROM `paquete_detalle` WHERE paquete_id = $PAQUETE_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    $LISTA = json_decode($_POST['lista']);
    foreach ($LISTA as $indice => $element) {
        $query = "INSERT INTO `paquete_detalle`(`paquete_id`, `pro_ser_id`, `nombre`, `tipo`, `costo`, `cantidad`, `total`) 
                    VALUES ($PAQUETE_ID, $element->id, '$element->name', '$element->type', $element->cost, $element->amount, $element->total)";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }
    }
    echo "Paquete actualizado";
}

function eliminarPaquete($mysqli)
{
    $PAQUETE_ID = $_POST['paquete_id'];
    $mysqli->begin_transaction();
    try {
        $query = "DELETE FROM `paquete_detalle` WHERE paquete_id = $PAQUETE_ID";
        $mysqli->query($query);

        $query2 = "DELETE FROM `paquete_cabecera` WHERE paquete_id = $PAQUETE_ID";
        $mysqli->query($query2);
        $mysqli->commit();
        echo ("Paquete Eliminado Correctamente");
    } catch (Exception $e) {
        echo ("Error: " . $e->getMessage());
    }
}
