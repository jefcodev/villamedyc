<?php
require('pdf/fpdf.php');

class PDF extends FPDF
{
    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-25);
        // Arial italic 8
        $this->SetFont('Arial', '', 8);
        $this->Cell(95, 10, utf8_decode('Villa Flora - Av. Alonso de Angulo y Francisco Gómez - Planta baja'), 0, 0, 'C');
        $this->Cell(95, 10, utf8_decode('Villa Flora - Av. Alonso de Angulo y Francisco Gómez - Planta baja'), 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(95, 10, utf8_decode('Teléfonos: 2658550 - 0999795566'), 0, 0, 'C');
        $this->Cell(95, 10, utf8_decode('Teléfonos: 2658550 - 0999795566'), 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(95, 10, utf8_decode('Correo: villamedycquito@gmail.com'), 0, 0, 'C');
        $this->Cell(95, 10, utf8_decode('Correo: villamedycquito@gmail.com'), 0, 0, 'C');
        $this->Ln(4);
        $this->Cell(95, 10, utf8_decode('www.villamedyc.com'), 0, 0, 'C');
        $this->Cell(95, 10, utf8_decode('www.villamedyc.com'), 0, 0, 'C');
        $this->SetLineWidth(0.25); // Ancho de línea
        $this->SetDrawColor(0, 0, 0);
        $this->Line(20, 273, 95, 273);
        $this->Line(115, 273, 190, 273);
        $this->Ln(10);
    }
}

// Instanciation of inherited class
require('../conection/conection.php');

$id_receta = $_GET['id_receta'];

// Consultar datos personales del paciente
$consulta = "SELECT * FROM consulta_receta_fisio WHERE id_receta='$id_receta' ";
$resultado = $mysqli->query($consulta);
$row = $resultado->fetch_assoc();

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

/* Encabezado */
$pdf->Image('../img/logo_receta.png', 8, 13, 25);
$pdf->Image('../img/logo_receta.png', 105, 13, 25);
$pdf->Image('../img/logo_receta_marca.png', 30, 100, 60);
$pdf->Image('../img/logo_receta_marca.png', 125, 100, 60);
$pdf->SetFillColor(234, 236, 238);
$pdf->SetDrawColor(182, 182, 182);
if ($row['id_doctor'] == 1) {
    $pdf->SetFont('Times', '', 14);

    $pdf->Cell(95, 10, utf8_decode('Dr. César Rovalino Troya'), 0, 0, 'C', 0);
    $pdf->Cell(95, 10, utf8_decode('Dr. César Rovalino Troya'), 0, 0, 'C', 0);
    $pdf->Ln(7);
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(95, 6, utf8_decode('Traumatología - Cirugía Artroscópica'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Traumatología - Cirugía Artroscópica'), 0, 0, 'C', 0);
    $pdf->Ln(4);
    $pdf->Cell(95, 6, 'Infantil y Adultos', 0, 0, 'C', 0);
    $pdf->Cell(95, 6, 'Infantil y Adultos', 0, 0, 'C', 0);
} elseif ($row['id_doctor'] == 11) {
    $pdf->SetFont('Times', '', 13);
    $pdf->Cell(95, 10, utf8_decode('Dr. Gabriel Paredes Báez'), 0, 0, 'C', 0);
    $pdf->Cell(95, 10, utf8_decode('Dr. Gabriel Paredes Báez'), 0, 0, 'C', 0);
    $pdf->Ln(7);
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(95, 6, utf8_decode('Médico Especialista en Cirugía'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Médico Especialista en Cirugía'), 0, 0, 'C', 0);
    $pdf->Ln(4);
    $pdf->Cell(95, 6, utf8_decode('Traumatología y Ortopédica'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Traumatología y Ortopédica'), 0, 0, 'C', 0);
}
elseif ($row['id_doctor'] == 29) {
    $pdf->SetFont('Times', '', 13);
    $pdf->Cell(95, 10, utf8_decode('Dr. Yuri Peralvo'), 0, 0, 'C', 0);
    $pdf->Cell(95, 10, utf8_decode('Dr. Yuri Peralvo'), 0, 0, 'C', 0);
    $pdf->Ln(7);
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(95, 6, utf8_decode('Médico Especialista en Cirugía'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Médico Especialista en Cirugía'), 0, 0, 'C', 0);
    $pdf->Ln(4);
    $pdf->Cell(95, 6, utf8_decode('Traumatología y Ortopédica'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Traumatología y Ortopédica'), 0, 0, 'C', 0);
}

elseif ($row['id_doctor'] == 30) {
    $pdf->SetFont('Times', '', 13);
    $pdf->Cell(95, 10, utf8_decode('Dr. Alexis Arellano'), 0, 0, 'C', 0);
    $pdf->Cell(95, 10, utf8_decode('Dr. Alexis Arellano'), 0, 0, 'C', 0);
    $pdf->Ln(7);
    $pdf->SetFont('Times', '', 9);
    $pdf->Cell(95, 6, utf8_decode('Médico Especialista en Cirugía'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Médico Especialista en Cirugía'), 0, 0, 'C', 0);
    $pdf->Ln(4);
    $pdf->Cell(95, 6, utf8_decode('Traumatología y Ortopédica'), 0, 0, 'C', 0);
    $pdf->Cell(95, 6, utf8_decode('Traumatología y Ortopédica'), 0, 0, 'C', 0);
}
$pdf->Ln(10);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(95, 6, utf8_decode('N° VM-004-') . $id_receta, 0, 0, 'C', 1);
$pdf->Cell(95, 6, utf8_decode('N° VM-004-') . $id_receta, 0, 0, 'C', 1);
$pdf->Ln(10);

$pdf->SetFont('Times', '', 11);
// Lectura de array
$pdf->Cell(95, 6, 'C.I. ' . utf8_decode($row['numero_identidad']) , 0, 0, 'L', 0);
$pdf->Cell(95, 6, 'C.I. ' . utf8_decode($row['numero_identidad']) , 0, 0, 'L', 0);
$pdf->Ln(5);
$pdf->Cell(95, 6, 'PACIENTE: ' . utf8_decode($row['nombres_paciente']) . ' ' . utf8_decode($row['apellidos_paciente']), 0, 0, 'L', 0);
$pdf->Cell(95, 6, 'PACIENTE: ' . utf8_decode($row['nombres_paciente']) . ' ' . utf8_decode($row['apellidos_paciente']), 0, 0, 'L', 0);
$pdf->Ln(5);
$pdf->Cell(95, 6, 'FECHA: ' . utf8_decode($row['fecha_hora']), 0, 0, 'L', 0);
$pdf->Cell(95, 6, 'FECHA: ' . utf8_decode($row['fecha_hora']), 0, 0, 'L', 0);
$pdf->Ln(5);
$pdf->Cell(95, 6, 'HISTORIA: ' . utf8_decode('VM-001-' . $row['numero_identidad']), 0, 0, 'L', 0);
$pdf->Cell(95, 6, 'HISTORIA: ' . utf8_decode('VM-001-' . $row['numero_identidad']), 0, 0, 'L', 0);
$pdf->Ln(5);
$pdf->Cell(95, 6, 'ATENDIDO POR: ' . utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellido_doctor']), 0, 0, 'L', 0);
$pdf->Cell(95, 6, 'ATENDIDO POR: ' . utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellido_doctor']), 0, 0, 'L', 0);
$pdf->Ln(5);
$pdf->MultiCell(95, 6, utf8_decode('CIE 10: ') . utf8_decode($row['diagnostico']), '', 'L', false);
$pdf->SetXY(105,66);
$pdf->MultiCell(95, 6, utf8_decode('CIE 10: ') . utf8_decode($row['diagnostico']), '', 'L', false);
$pdf->Ln(5);




// Ancho de cada columna
$columnWidth = 95;

// Primera columna para Receta
$pdf->SetX(10);
$pdf->Cell($columnWidth, 6, 'Observaciones:', 0, 0, 'C', 1);
$pdf->Cell($columnWidth, 6, 'Observaciones:', 0, 0, 'C', 1);
$pdf->Ln(10);
// Segunda columna para Receta
$pdf->SetX(10);
$pdf->MultiCell($columnWidth, 6, utf8_decode($row['examenes']), 0, 'L', 0);

// Segunda columna para Indicaciones
$pdf->SetXY(105,98);
$pdf->MultiCell($columnWidth, 6, utf8_decode($row['tratamiento']), 0, 'L', 0);

// Ajustar la posición Y para la siguiente sección
$pdf->SetY($pdf->GetY() + 10);



// Primera celda para Indicaciones


/* if ($row['id_doctor'] == 1) {
    $pdf->Image('img/Firma_digital_Dr_Cesar.png', 90, $pdf->GetY() + 10, 30);
    $pdf->Image('img/Firma_digital_Dr_Cesar.png', 185, $pdf->GetY() + 10, 30);
}
if ($row['id_doctor'] == 11) {
    $pdf->Image('img/firma-digital-Dra-Gabriela.png', 10, $pdf->GetY() + 10, 60);
    $pdf->Image('img/firma-digital-Dra-Gabriela.png', 105, $pdf->GetY() + 10, 60);
} */

$pdf->Ln(30);
$pdf->Cell(95, 6, utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellido_doctor']), 0, 0, 'C', 0);
$pdf->Cell(95, 6, utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellido_doctor']), 0, 0, 'C', 0);

$pdf->Ln();
$pdf->Cell(95, 6, utf8_decode('TRAUMATOLOGÍA Y ORTOPEDIA') , 0, 0, 'C', 0);
$pdf->Cell(95, 6, utf8_decode('TRAUMATOLOGÍA Y ORTOPEDIA') , 0, 0, 'C', 0);


$pdf->Output();
