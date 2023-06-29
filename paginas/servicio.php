<?php
include '../conection/conection.php';
session_start();
$ACTION = $_POST['action'];

if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']))) {
    if (isset($ACTION) && !empty($ACTION)) {
        switch ($ACTION) {
            case 'ver_servicios':
                verServicios($mysqli);
                break;
            case 'crear_servicio':
                crearServicio($mysqli);
                break;
            case 'ver_servicio':
                verServicio($mysqli);
                break;
            case 'actualizar_servicio':
                actualizarServicio($mysqli);
                break;
            case 'eliminar_servicio':
                eliminarServicio($mysqli);
                break;
            default:
                # code...
                break;
        }
    }
}

function crearServicio($mysqli)
{
    $TITULO_SERVICIO = $_POST['titulo_servicio'];
    $TOTAL = $_POST['total'];

    $query = "INSERT INTO `servicio_cabecera`(`titulo_servicio`, `total`) 
        VALUES ('$TITULO_SERVICIO', $TOTAL)";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    $SERVICIO_ID = $mysqli->insert_id;
    $LISTA = json_decode($_POST['lista']);
    foreach ($LISTA as $indice => $element) {
        $query = "INSERT INTO `servicio_detalle`(`servicio_id`, `pro_ser_id`, `nombre`, `tipo`, `costo`, `cantidad`, `total`) 
                    VALUES ($SERVICIO_ID, $element->id, '$element->name', '$element->type', $element->cost, $element->amount, $element->total)";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }
    }
}

function verServicios($mysqli)
{
    $query = "SELECT * from servicio_cabecera";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'servicio_id' => $row['servicio_id'],
            'titulo_servicio' => $row['titulo_servicio'],
            'total' => $row['total']
        );
    }
    $jsonstring = json_encode($json);
    echo $jsonstring;
}

function verServicio($mysqli)
{
    $SERVICIO_ID = $_POST['servicio_id'];
    $query = "SELECT * FROM deatelle_servicio WHERE id_servicio = $SERVICIO_ID";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }
    $json = array();
    while ($row = mysqli_fetch_array($result)) {
        $json[] = array(
            'id_detalle_servicio' => $row['id_det_servicio'],
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

// function verServicio($mysqli)
// {
//     $SERVICIO_ID = $_POST['servicio_id'];
//     $query = "SELECT sc.servicio_id, sc.titulo_servicio, sc.total as total_servicio,
//                 sd.* FROM servicio_cabecera sc, servicio_detalle sd  
//                 WHERE sc.servicio_id = sd.servicio_id AND sd.servicio_id =$SERVICIO_ID";
//     $result = $mysqli->query($query);
//     if (!$result) {
//         die('Query Failed.');
//     }
//     $json = array();
//     while ($row = mysqli_fetch_array($result)) {
//         $json[] = array(
//             'servicio_id' => $row['servicio_id'],
//             'titulo_servicio' => $row['titulo_servicio'],
//             'total_servicio' => $row['total_servicio'],
//             'pro_ser_id' => $row['pro_ser_id'],
//             'nombre' => $row['nombre'],
//             'tipo' => $row['tipo'],
//             'costo' => $row['costo'],
//             'cantidad' => $row['cantidad'],
//             'total' => $row['total']
//         );
//     }
//     $jsonstring = json_encode($json);
//     echo $jsonstring;
// }

function actualizarServicio($mysqli)
{
    $servicio_id = $_POST['servicio_id'];
    $productosSeleccionados = json_decode($_POST["productos"]);
    $totalPago = $_POST["total_pago"];
    $valorAdicional = $_POST["valor_adicional"];

    $query = "UPDATE `servicios` SET `total`=$totalPago, `valor_adicional`=$valorAdicional 
                WHERE id_servicio=$servicio_id";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    $query = "DELETE FROM `deatelle_servicio` WHERE id_servicio = $servicio_id";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    foreach ($productosSeleccionados as $indice => $element) {
        $query = "INSERT INTO deatelle_servicio (id_servicio, id_producto, nombre, precio, cantidad, subtotal) 
        VALUES ($servicio_id, $element->id_producto, '$element->nombre', $element->precio, $element->cantidad, $element->subtotal)";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }
    }

    // $LISTA = json_decode($_POST['lista']);
    // foreach ($LISTA as $indice => $element) {
    //     $query = "INSERT INTO `servicio_detalle`(`servicio_id`, `pro_ser_id`, `nombre`, `tipo`, `costo`, `cantidad`, `total`) 
    //                 VALUES ($SERVICIO_ID, $element->id, '$element->name', '$element->type', $element->cost, $element->amount, $element->total)";
    //     $result = $mysqli->query($query);
    //     if (!$result) {
    //         die('Query Failed.');
    //     }
    // }
    echo "Servicio actualizado";
}

// function actualizarServicio($mysqli)
// {
//     $SERVICIO_ID = $_POST['servicio_id'];
//     $TITULO_SERVICIO = $_POST['titulo_servicio'];
//     $TOTAL = $_POST['total'];

//     $query = "UPDATE `servicio_cabecera` SET `titulo_servicio`='$TITULO_SERVICIO',  
//                 `total`=$TOTAL 
//                 WHERE servicio_id=$SERVICIO_ID";
//     $result = $mysqli->query($query);
//     if (!$result) {
//         die('Query Failed.');
//     }

//     $query = "DELETE FROM `servicio_detalle` WHERE servicio_id = $SERVICIO_ID";
//     $result = $mysqli->query($query);
//     if (!$result) {
//         die('Query Failed.');
//     }

//     $LISTA = json_decode($_POST['lista']);
//     foreach ($LISTA as $indice => $element) {
//         $query = "INSERT INTO `servicio_detalle`(`servicio_id`, `pro_ser_id`, `nombre`, `tipo`, `costo`, `cantidad`, `total`) 
//                     VALUES ($SERVICIO_ID, $element->id, '$element->name', '$element->type', $element->cost, $element->amount, $element->total)";
//         $result = $mysqli->query($query);
//         if (!$result) {
//             die('Query Failed.');
//         }
//     }
//     echo "Servicio actualizado";
// }

function eliminarServicio($mysqli)
{
    $SERVICIO_ID = $_POST['servicio_id'];
    $mysqli->begin_transaction();
    try {
        $query = "DELETE FROM `servicio_detalle` WHERE servicio_id = $SERVICIO_ID";
        $mysqli->query($query);

        $query2 = "DELETE FROM `servicio_cabecera` WHERE servicio_id = $SERVICIO_ID";
        $mysqli->query($query2);
        $mysqli->commit();
        echo ("Servicio Eliminado Correctamente");
    } catch (Exception $e) {
        echo ("Error: " . $e->getMessage());
    }
}
