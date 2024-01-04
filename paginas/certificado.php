<?php
require('pdf/fpdf.php'); // Asegúrate de proporcionar la ruta correcta a la librería FPDF.
require('NumeroALetras.php');

class PDF extends FPDF
{
    function Header()
    {
        // Cabecera del informe
        $this->Image('../img/logo.png', 15, 10, 50);
        $this->SetFont('Times', 'B', 12);
        $this->Cell(180, 25, utf8_decode('TRAUMATOLOGÍA - FISIOTERAPIA Y REHABILITACIÓN'), 0, 1, 'R');

        $this->SetLineWidth(0.25); // Ancho de línea
        $this->SetDrawColor(0, 0, 0);
        $this->Line(75, 26, 190, 26);
        $this->Ln(10);
    }

    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-25);
        // Times italic 8

        $this->SetFont('Times', '', 11);
        $this->Cell(0, 10, utf8_decode('Villa Flora - Av. Alonso de Angulo y Francisco Gómez - Planta baja'), 0, 0, 'C');
        $this->Ln(6);
        $this->Cell(0, 10, utf8_decode('Teléfonos: 2658550 - 0999795566'), 0, 0, 'C');
        $this->Ln(6);
        $this->Cell(0, 10, utf8_decode('www.villamedyc.com'), 0, 0, 'L');
        $this->Cell(0, 10, utf8_decode('villamedycquito@gmail.com'), 0, 0, 'R');

        $this->SetLineWidth(0.25); // Ancho de línea
        $this->SetDrawColor(0, 0, 0);
        $this->Line(20, 270, 190, 270);
        $this->Ln(10);
    }
}

// Crear una instancia de tu clase personalizada PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();


require('../conection/conection.php'); // Asegúrate de proporcionar la ruta correcta al archivo de conexión.

// Resto del código para obtener los datos y generar el PDF, como se muestra en tu ejemplo original...

// Obtener el ID de la consulta (cambia esto según cómo obtengas el ID).
$id_consulta = $_GET['id_consulta'];

// Consulta SQL para obtener los datos de la consulta médica.
$sql = "SELECT * FROM consultas_datos WHERE id_consulta = $id_consulta";

$result = $mysqli->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();

    // Datos del paciente
    $nombre = $row['nombres'] . ' ' . $row['apellidos'];
    $ci = $row['numero_identidad'];
    $nro_historia = $row['id_paciente'];
    $fecha_nacimiento = $row['fecha_nacimiento'];
    $fecha_nac = $row['fecha_nacimiento'];

    // Calcular la fecha actual
    $fecha_actual = date('Y-m-d');

    // Convertir las fechas en objetos DateTime
    $fecha_nacimiento = new DateTime($fecha_nacimiento);
    $fecha_actual = new DateTime($fecha_actual);

    // Calcular la diferencia entre las fechas
    $diferencia = $fecha_nacimiento->diff($fecha_actual);

    // Obtener la edad en años
    $edad = $diferencia->y;


    $direccion = $row['direccion'];
    $telefono = $row['telefono'];

    // Datos de la consulta médica
    $fecha_atencion = $row['fecha_hora'];
    $fecha_inicio_repo = $row['fecha_inicio_repo'];
    $fecha_fin_repo = $row['fecha_fin_repo'];
    $dias_repo = $row['certificado'];
    $diagnostico = $row['diagnostico'];
    $cie10 = $row['cie10'];


    $institucion = $row['institucion'];
    $ocupacion = $row['ocupacion'];
    $descripcion = $row['descripcion'];
    $tipo_contingencia = $row['tipo_contingencia'];

    // Incluir la librería FPDF



    $pdf->SetFont('Times', '', 12);
    // Encabezado

    $meses = array(
        1 => 'enero',
        2 => 'febrero',
        3 => 'marzo',
        4 => 'abril',
        5 => 'mayo',
        6 => 'junio',
        7 => 'julio',
        8 => 'agosto',
        9 => 'septiembre',
        10 => 'octubre',
        11 => 'noviembre',
        12 => 'diciembre'
    );

    $fecha_actual = date('j') . ' de ' . $meses[date('n')] . ' del ' . date('Y');

    $pdf->Cell(0, 10, 'Quito, ' . $fecha_actual, 0, 1, 'R');

    // Establecer la fuente en negrita para el título
    $pdf->SetFont('Times', 'B', 14);
    $pdf->Cell(0, 10, utf8_decode('CERTIFICADO TRAUMATOLOGÍA'), 0, 1, 'C');
    $pdf->Ln(10); // Espacio después del título

    $pdf->SetFont('Times', '', 12);

    // Datos del paciente en una sola línea con tabulación
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, utf8_decode('NOMBRE:  ' . $nombre), 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, 'C.I: ' . $ci, 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, utf8_decode('N° HISTORIA:  VM-00') . $ci, 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, 'FECHA DE NACIMIENTO:  ' . $fecha_nac, 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, 'EDAD:  ' . $edad . utf8_decode(' años'), 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, utf8_decode('DIRECCIÓN:  ' . $direccion), 0, 1);
    $pdf->SetX(20); // Tabulación 
    $pdf->Cell(0, 5, utf8_decode('TELÉFONO:  ') . $telefono, 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->SetFont('Times', 'B', 12);
    $pdf->Cell(0, 5, utf8_decode('CERTIFICO QUE LA PACIENTE EN MENCION RECIBE ATENCION EN EL CENTRO'), 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, utf8_decode('MÉDICO VILLAMEDYC '), 0, 1);
    $pdf->SetFont('Times', '', 12);
    // Certificado médico



    // Obtener día, mes y año de la fecha de atención
    $fecha_atencion = strtotime($row['fecha_hora']);
    $dia_atencion = date('d', $fecha_atencion);
    $mes_atencion = date('F', $fecha_atencion);
    $mes_ate = date('m', $fecha_atencion);
    $ano_atencion = date('Y', $fecha_atencion);

    // Convertir fecha de atención a letras usando la clase NumeroALetras
    $dia_atencion_letras = NumeroALetras::convertir($dia_atencion);
    $mes_atencion_letras = NumeroALetras::mesesTraducida($mes_atencion);
    $ano_atencion_letras = NumeroALetras::convertir($ano_atencion);



    $timestamp_inicio = strtotime($row['fecha_hora']);

    $timestamp_fin = $timestamp_inicio + ($dias_repo * 86400);

    $fecha_fin_repo = date('d-m-Y', $timestamp_fin);
    $fecha_fin_repo_dia = date('d', $timestamp_fin);
    $fecha_fin_repo_mes = date('F', $timestamp_fin);
    $fecha_fin_repo_ano = date('Y', $timestamp_fin);

    $fecha_fin_repo_dia_letras = NumeroALetras::convertir($fecha_fin_repo_dia);
    $fecha_fin_repo_mes_letras = NumeroALetras::mesesTraducida($fecha_fin_repo_mes);
    $fecha_fin_repo_ano_letras = NumeroALetras::convertir($fecha_fin_repo_ano);







    // Imprimir la fecha de atención en letras
    $pdf->SetX(20); // Tabulación
    $pdf->MultiCell(0, 5, utf8_decode('FECHA DE ATENCIÓN: ') . "$dia_atencion-$mes_ate-$ano_atencion ($dia_atencion_letras DE $mes_atencion_letras DE $ano_atencion_letras)", 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->MultiCell(0, 5, utf8_decode('FECHA DE INICIO DE REPOSO: ') . "$dia_atencion-$mes_ate-$ano_atencion ($dia_atencion_letras DE $mes_atencion_letras DE $ano_atencion_letras)", 0, 1);
    $pdf->SetX(20); // Tabulación
    $pdf->MultiCell(0, 5, utf8_decode('FECHA DE FIN DE REPOSO: ') . "$fecha_fin_repo ($fecha_fin_repo_dia_letras DE $fecha_fin_repo_mes_letras DE $fecha_fin_repo_ano_letras)", 0, 1);

    $dias_letras = NumeroALetras::convertir($dias_repo);
    $pdf->SetX(20); // Tabulación
    $pdf->Cell(0, 5, utf8_decode('DÍAS REPOSO:  ') . $dias_repo . ' (' . $dias_letras . ')', 0, 1);
    $pdf->SetX(20);
    $pdf->MultiCell(190, 5,  utf8_decode('DIAGNÓSTICO:  ' . $diagnostico), 0, 1);
    $pdf->SetX(20);
    $pdf->MultiCell(190, 5,  utf8_decode('INSTITUCIÓN:  ' . $institucion), 0, 1);
    $pdf->SetX(20);
    $pdf->MultiCell(190, 5,  utf8_decode('PUESTO DE TRABAJO:  ' .  $ocupacion), 0, 1);
    $pdf->SetX(20);
    $pdf->MultiCell(190, 5,  utf8_decode('DESCRIPCIÓN:  ' . $descripcion), 0, 1);
    $pdf->SetX(20);
    $pdf->MultiCell(190, 5,  utf8_decode('TIPO DE CONTINGENCIA:  ' . $tipo_contingencia), 0, 1);


    $pdf->Ln(50);
    $pdf->Cell(190, 6, utf8_decode($row['nombre_doctor']) . ' ' . utf8_decode($row['apellidos_doctor']), 0, 0, 'C', 0); // Sin bordes
    $pdf->Ln(6);
    $pdf->Cell(190, 6, utf8_decode('TRAUMATOLOGÍA Y ORTOPEDIA'), 0, 0, 'C', 0); // Sin bordes
    $pdf->Ln(6);
    $pdf->Cell(190, 6, '', 0, 0, 'C', 0); // Sin bordes
    $pdf->Ln(6);

    // Generar el PDF
    $pdf->Output();
} else {
    echo 'No se encontraron datos para la consulta con ID ' . $id_consulta;
}
