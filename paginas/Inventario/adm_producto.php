<?php

session_start();
include_once "../conection/conection.php";
$usuario = $_SESSION['usuario'];
$rol = $_SESSION['rol'];

if (isset($_POST['btn_crear_producto'])) {
    //date_default_timezone_set('America/Bogota');
    //$fecha_hora = date("Y-m-d H:i:s");
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio_c = $_POST['precio_c'];
    $precio_v = $_POST['precio_v'];
    $stock = $_POST['stock'];

    $sql_crear_producto = "INSERT INTO productos (codigo, nombre, descripcion , precio_c, precio_v, stock, "
            . "VALUES ('$codigo', '$nombre', '$descripcion', '$precio_c', '$precio_v', '$stock')";
    $query_crear_producto = $mysqli->query($sql_crear_producto);
    if ($query_crear_producto == TRUE) {
        header("location:lista_productos.php?status=OK");
    } else {
        header("location:crear_producto.php?status=ER");
    }
}

