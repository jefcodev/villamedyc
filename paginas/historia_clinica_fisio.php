<?php
require('pdf/fpdf.php');


class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
    $this->Image('../img/logo.png',70,6,60);
    
    $this->Ln(25);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }
}

// Instanciation of inherited class


require('../conection/conection.php');

$id_paciente = $_GET['id_paciente'];

// Cosultar datos personales de paciente 
$consulta = "SELECT * FROM pacientes WHERE id='$id_paciente' ";
$resultado = $mysqli->query($consulta);

// Creción de pdf

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 15);
$pdf->Cell(190, 10, utf8_decode('Historia Clínica '). utf8_decode(' VM-00-F-') . $id_paciente,0, 0,'C' ,0);
$pdf->Ln(15);

$pdf->SetFont('Times', '', 11);


$pdf->SetFillColor(234, 236, 238);
$pdf->SetDrawColor(182, 182, 182);
// Lectura de array 
while ($row = $resultado->fetch_assoc()) {
    $pdf->Cell(50, 6, ' NOMBRES Y APELLIDOS', 1, 0, 'C', 1);
    $pdf->Cell(140, 6, utf8_decode($row['nombres']) .' '. utf8_decode($row['apellidos']), 1, 0, 'C', 0);
    $pdf->Ln(6);
    $pdf->Cell(50, 6, ' FECHA DE NACIMIENTO', 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['fecha_nacimiento']), 1, 0, 'C', 0);
    $pdf->Cell(50, 6, 'CI', 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['numero_identidad']), 1, 0, 'C', 0);
    $pdf->Ln(6);
    $pdf->Cell(50, 6, utf8_decode(' GÉNERO'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['genero']), 1, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode('TELÉFONO FIJO'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['telefono_fijo']), 1, 0, 'C', 0);
    $pdf->Ln(6);
    $pdf->Cell(50, 6, utf8_decode('TELÉFONO MOVIL'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['telefono_movil']), 1, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode('DIRECCIÓN'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['direccion']), 1, 0, 'C', 0);
    $pdf->Ln(6);
    $pdf->Cell(50, 6, utf8_decode(' CORREO ELECTRÓNICO'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['correo_electronico']), 1, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode('OCUPACIÓN'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['ocupacion']), 1, 0, 'C', 0);
    $pdf->Ln(6);
    $pdf->Cell(50, 6, 'ANTC. PERSONALES', 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['antecedentes_personales']), 1, 0, 'C', 0);
    $pdf->Cell(50, 6, utf8_decode('ANTC. FAMILIARES'), 1, 0, 'C', 1);
    $pdf->Cell(45, 6, utf8_decode($row['antecedentes_familiares']), 1, 0, 'C', 0);
    $pdf->Ln(10);
}

$pdf->SetFont('Times', 'B', 15);
$pdf->Cell(190, 15, utf8_decode('Consultas Fisioterapía'), 0,0,'C', 0);
$pdf->Ln(20);
$pdf->SetFont('Times', '', 11);

// Consulta de # de consultas por id de paciente 

$cosulta_citas = "select * from consultas_fisioterapeuta where paciente_id ='$id_paciente'";
$resultado_citas = $mysqli->query($cosulta_citas);

while ($row_citas = $resultado_citas->fetch_assoc()) {
   // Define el ancho y alto de las celdas
$consulta_fisio_id = $row_citas['consulta_fisio_id'];
$cellWidth = 45;
$cellHeight = 10;
$pdf->Cell(60, 6, utf8_decode('PROFESIÓN'), 1, 0, 'C', 1);
$pdf->Cell(45, 6, utf8_decode($row_citas['profesion']), 1, 0, 'C', 0);
$pdf->Cell(40, 6, utf8_decode('TIPO TRABAJO'), 1, 0, 'C', 1);
$pdf->Cell(45, 6, utf8_decode($row_citas['tipo_trabajo']), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea

$pdf->Cell(60, 6, utf8_decode('SEDENTACIÓN PROLONGADA'), 1, 0, 'C', 1);
if($row_citas['sedestacion_prolongada']==1){
    $pdf->Cell(45, 6, utf8_decode('SI'), 1, 0, 'C', 0);
}
if($row_citas['sedestacion_prolongada']==2){
    $pdf->Cell(45, 6, utf8_decode('NO'), 1, 0, 'C', 0);
}

$pdf->Cell(40, 6, utf8_decode('ESFUERZO FÍSICO'), 1, 0, 'C', 1);

if ($row_citas['esfuerzo_fisico'] == "1") {
    $pdf->Cell(45, 6, utf8_decode('Bajo'), 1, 0, 'C', 0);
}
if ($row_citas['esfuerzo_fisico'] == "2") {
    $pdf->Cell(45, 6, utf8_decode('Medio'), 1, 0, 'C', 0);
}
if ($row_citas['esfuerzo_fisico'] == "3") {
    $pdf->Cell(45, 6, utf8_decode('Alto'), 1, 0, 'C', 0);
}


$pdf->Ln(); // Salto de línea

$pdf->Cell(60, 6, utf8_decode('HÁBITOS'), 1, 0, 'C', 1);
$pdf->MultiCell(130, 6, utf8_decode($row_citas['habitos']), 1, 'L', false);


$pdf->Cell(60, 6, utf8_decode('ANTC. DIAGNÓSTICO'), 1, 0, 'C', 1);
$pdf->MultiCell(130, 6, utf8_decode($row_citas['antecedentes_diagnostico']), 1, 'L', false);


$pdf->Cell(60, 6, utf8_decode('TRATAMIENTOS ANTERIORES'), 1, 0, 'C', 1);
$pdf->MultiCell(130, 6, utf8_decode($row_citas['tratamientos_anteriores']), 1, 'L', false);


$pdf->Cell(60, 6, utf8_decode('CONTRACTURAS'), 1, 0, 'C', 1);
$pdf->MultiCell(130, 6, utf8_decode($row_citas['contracturas']), 1, 'L', false);


$pdf->Cell(60, 6, utf8_decode('IRRADIACIÓN'), 1, 0, 'C', 1);

if($row_citas['irradiacion']==1){
    $pdf->Cell(45, 6, utf8_decode('SI'), 1, 0, 'C', 0);
}
if($row_citas['irradiacion']==2){
    $pdf->Cell(45, 6, utf8_decode('NO'), 1, 0, 'C', 0);
}
$pdf->Cell(40, 6, utf8_decode('LIM. DE MOVILIDAD'), 1, 0, 'C', 1);

if($row_citas['limitacion_movilidad']==1){
    $pdf->Cell(45, 6, utf8_decode('Crujidos'), 1, 0, 'C', 0);
}
if($row_citas['limitacion_movilidad']==2){
    $pdf->Cell(45, 6, utf8_decode('Topes Articulares'), 1, 0, 'C', 0);
}
if($row_citas['limitacion_movilidad']==3){
    $pdf->Cell(45, 6, utf8_decode('Músculo Tendioso'), 1, 0, 'C', 0);
}

$pdf->Ln(15); // Salto de línea
   

$pdf->SetFont('Times', 'B', 15);
$pdf->Cell(190, 15, utf8_decode('Sesiones'), 0,0,'C', 0);
$pdf->Ln(10);
$pdf->SetFont('Times', '', 11);

$cosulta_dealle = "select * from consultas_fisioterapeuta_detalle where consulta_fisio_id ='$consulta_fisio_id'";
$resultado_detalle = $mysqli->query($cosulta_dealle);
$cont = 1;
while ($row_detalle = $resultado_detalle->fetch_assoc()) {

$user = $row_detalle['usuario_id'];

$sql_user= "select * from usuarios where id ='$user'";
$res_user = $mysqli->query($sql_user);
$row_user = $res_user->fetch_assoc();

$pdf->Cell(190, 15, utf8_decode('Sesión #'. $cont), 0,0,'L', 0);
$pdf->Ln(15);

$pdf->Cell(55, 6, utf8_decode('ATENDIDO POR:'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_user['nombre'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('FECHA:'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['laserterapia'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(10); // Salto de línea
$pdf->Cell(55, 6, utf8_decode('ELECTROESTIMULACIÓN'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['electroestimulacion'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('LASERTERAPIA'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['laserterapia'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea
$pdf->Cell(55, 6, utf8_decode('ULTRASONIDO'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['ultrasonido'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('MAGNETO'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['magnetoterapia'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea

$pdf->Cell(55, 6, utf8_decode('MASOTERAPIA'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['masoterapia'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('TERMOTERAPIA'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['termoterapia'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea

$pdf->Cell(55, 6, utf8_decode('CRIOTERAPIA'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['crioterapia'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('MOV. ACTIVA LIBRE'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['malibre'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea

$pdf->Cell(55, 6, utf8_decode('MOV. ACTIVA ASISTIDA'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['maasistida'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('FORTC. MUSCULAR'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['fmuscular'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea

$pdf->Cell(55, 6, utf8_decode('PROPIOCEPCIÓN'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['propiocepcion'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Cell(55, 6, utf8_decode('PUNCIÓN SECA'), 1, 0, 'C', 1);
$pdf->Cell(40, 6, utf8_decode($row_detalle['epunta'] == 1 ? 'x' : ''), 1, 0, 'C', 0);
$pdf->Ln(); // Salto de línea

$cont ++;
}


}

$pdf->Output();

