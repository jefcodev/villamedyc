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

$id_receta = $_GET['id_receta'];

// Cosultar datos personales de paciente 
$consulta = "SELECT * FROM consulta_receta WHERE id_receta='$id_receta' ";
$resultado = $mysqli->query($consulta);




$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 15);
$pdf->Cell(190, 10, utf8_decode('Receta ') . utf8_decode(' N° VM-003-') . $id_receta, 0, 0, 'C', 0);
$pdf->Ln(15);

$pdf->SetFont('Times', '', 11);


$pdf->SetFillColor(234, 236, 238);
$pdf->SetDrawColor(182, 182, 182);
// Lectura de array 
$row = $resultado->fetch_assoc();
$pdf->Cell(100, 6, 'PACIENTE: ' . utf8_decode($row['nombres_paciente']) . ' ' . utf8_decode($row['apellidos_paciente']), 0, 0, 'L', 0); // Sin bordes
$pdf->Cell(90, 6, 'FECHA: ' . utf8_decode($row['fecha_hora']), 0, 0, 'R', 0); // Sin bordes
$pdf->Ln(6);
$pdf->Cell(190, 6, 'ATENDIDO POR: ' . utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellido_doctor']), 0, 0, 'L', 0); // Sin bordes
$pdf->Ln(6);
$pdf->MultiCell(190, 6, 'DIAGNOSTICO: ' . utf8_decode($row['diagnostico']), 0, 'L', 0); // Sin bordes
$pdf->Ln(10);
// Primera celda para Receta
$pdf->Cell(45, 6, 'Receta:', 0, 0, 'C', 1);
$pdf->MultiCell(0, 6, utf8_decode($row['receta']), 0, 0, 'L', 1);
$pdf->Ln(6);
// Segunda celda para Indicaciones
$pdf->Cell(45, 6, 'Indicaciones:', 0, 0, 'C', 1);
$pdf->MultiCell(0, 6, utf8_decode($row['indicaciones']), 0, 0, 'L', 0);


if ($row['id_doctor'] == 1) {
    $pdf->Image('img/Firma_digital_Dr_Cesar.png', 90, $pdf->GetY() + 10, 30); // Cambia las coordenadas (x, y) según tu diseño
}
if ($row['id_doctor'] == 2) {
    $pdf->Image('img/firma-digital-Dra-Gabriela.png', 10, $pdf->GetY() + 10, 60); // Cambia las coordenadas (x, y) según tu diseño
}
$pdf->Ln(30);
$pdf->Cell(190, 6, utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellido_doctor']), 0, 0, 'C', 0); // Sin bordes
$pdf->Ln(6);


$pdf->Output();