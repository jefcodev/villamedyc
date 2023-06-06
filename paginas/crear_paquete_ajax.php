<?php

include '../conection/conection.php';
session_start();

if (isset($_SESSION['usuario']) && (isset($_SESSION['rol']))) {
    $CABECERA_ID = $_POST['cabecera_id'];
    $USUARIO_ID = $_POST['usuario_id'];
    $PACIENTE_ID = $_POST['paciente_id'];
    $TOTAL = $_POST['total'];
    echo $CABECERA_ID;

    $query = "INSERT INTO `paquete_cabecera`(`CABECERA_ID`, `USUARIO_ID`, `PACIENTE_ID`, `TOTAL`) 
        VALUES ($CABECERA_ID, $USUARIO_ID, $PACIENTE_ID, $TOTAL)";
    $result = $mysqli->query($query);
    if (!$result) {
        die('Query Failed.');
    }

    $SERVICIOS = json_decode($_POST['servicios']);
    foreach ($SERVICIOS as $indice => $servicio) {
        $query = "INSERT INTO `paquete_detalle_servicio`(`CABECERA_ID`, `SERVICIO_ID`, `NUMERO_SESIONES`, `SESIONES_REALIZADAS`, `TOTAL`) 
                    VALUES ($CABECERA_ID, $servicio->id, $servicio->sesiones, 0, $servicio->total)";
        $result = $mysqli->query($query);
        if (!$result) {
            die('Query Failed.');
        }
    }
    if (isset($_POST['productos'])) {
        $PRODUCTOS = json_decode($_POST['productos']);
        foreach ($PRODUCTOS as $indice => $producto) {
            $query = "INSERT INTO `paquete_detalle_producto`(`CABECERA_ID`, `PRODUCTO_ID`, `COSTO_PRODUCTO`, `CANTIDAD`, `TOTAL`) 
                    VALUES ($CABECERA_ID, $producto->id, $producto->cost, $producto->count, $producto->total)";
            $result = $mysqli->query($query);
            if (!$result) {
                die('Query Failed.');
            }
        }
    }
}
