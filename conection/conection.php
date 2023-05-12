<?php

error_reporting(E_COMPILE_ERROR | E_ERROR | E_CORE_ERROR);

$mysqli = new mysqli("localhost", "villame5_clinica", "cl1n1c4p@ssw0rd", "villame5_bb0");
if (mysqli_connect_error()) {
    echo 'Conexion fallida : ', mysqli_connect_error();
}