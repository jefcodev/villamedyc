<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

//$mysqli = new mysqli("systemcode.ec", "clinica", "cl1n1c4p@ssw0rd", "villame5");
$mysqli = new mysqli("localhost", "root", "", "prueba");

//$mysqli = new mysqli("localhost", "dr_cesar", "!2S85oft2", "clinica_dr");
//$mysqli_replica = new mysqli("localhost", "clinica", "cl1n1c4p@ssw0rd", "villame5");

if (mysqli_connect_error()) {
    echo 'Conexion fallida : ', mysqli_connect_error();
}
$mysqli->set_charset("utf8mb4");
//