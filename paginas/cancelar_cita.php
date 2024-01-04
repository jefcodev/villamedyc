<?php
include '../conection/conection.php';

 $id_cita = $_GET['id_cita']; 
/* $id_cita = $_POST['id_cita']; */


$result = $mysqli->query("update citas set consultado='si' where id='$id_cita'");
if ($result) {
    echo "La actualización se realizó correctamente.";
} else {
    echo "Error al actualizar la cita: " . $mysqli->error;
}

// Redirige al menú después de la actualización
header("Location: inicio.php");
?>