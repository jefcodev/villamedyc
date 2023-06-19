<?php
require('pdf/fpdf.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../img/logo.png', 70, 6, 60);
    
        $this->Ln(25);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

// Instanciation of inherited class
require('../conection/conection.php');

// Creación de PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 15);
$pdf->Cell(190, 10, utf8_decode('Reporte de Stock '), 0, 0,'C' ,0);
$pdf->Ln(15);

require('../conection/conection.php');

// Obtener los datos de los productos
$sql_productos = "SELECT nombre, stock FROM productos";
$result_productos = $mysqli->query($sql_productos);

// Configurar el estilo de fuente y tamaño para la cabecera de la tabla
$pdf->SetFont('Arial', 'B', 12);
$pdf->SetFillColor(234, 236, 238);
$pdf->SetDrawColor(182, 182, 182);
// Escribir la cabecera de la tabla
$pdf->Cell(95, 10, 'Producto', 1, 0, 'C',1);
$pdf->Cell(95, 10, 'Stock', 1, 1, 'C',1);

// Configurar el estilo de fuente y tamaño para el contenido de la tabla
$pdf->SetFont('Arial', '', 12);

// Escribir los datos de los productos en la tabla
while ($row = mysqli_fetch_array($result_productos)) {
    $pdf->Cell(95, 10, $row['nombre'], 1, 0, 'C');
    $pdf->Cell(95, 10, $row['stock'], 1, 1, 'C');
}

$pdf->Output();
