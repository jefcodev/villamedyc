<?php
require('pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // Cabecera del informe
        $this->Image('../img/logo.png', 70, 6, 60);
        $this->Ln(25);
        $this->SetFont('Arial','B',14);
        $this->Cell(0,10,'Informe de Compras',0,1,'C');
        $this->Ln(5);
    }

    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, utf8_decode('Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    function GenerateReport($conn, $fechaInicio, $fechaFin)
    {
        $totalSum = 0;
        // Verificar que las fechas sean válidas
        if (!strtotime($fechaInicio) || !strtotime($fechaFin)) {
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,'Fechas inválidas',0,1,'C');
            return;
        }

        // Consultar las compras en el rango de fechas especificado
        $sql = "SELECT v.fecha_hora, v.nombre_paciente, v.apellidos_pacientes, v.valor_pagado, v.nombre_doctor
                FROM consulta_ventas v
                WHERE v.fecha_hora BETWEEN '$fechaInicio' AND '$fechaFin'
                ORDER BY v.nombre_doctor";

        $result = $conn->query($sql);
        $this->SetFillColor(234, 236, 238);
        $this->SetDrawColor(182, 182, 182);

        $ventasPorDoctor = array(); // Array para almacenar las ventas por doctor

        if ($result->num_rows > 0) {
            $this->SetFont('Arial','B',11);

            $this->Cell(50,10,'Fecha',1,0,'C',1);
            $this->Cell(75,10,'Paciente',1,0,'C',1);
            $this->Cell(30,10,'Doctor',1,0,'C',1);
            $this->Cell(20,10,'Total',1,1,'C',1);

            while ($row = $result->fetch_assoc()) {
                $fecha = $row['fecha_hora'];
                $paciente = utf8_decode($row['apellidos_pacientes'].' '.$row['nombre_paciente']);
                $doctor = utf8_decode($row['nombre_doctor']);
                $total = $row['valor_pagado'];

                $this->SetFont('Arial','',11);
                $this->Cell(50,10,$fecha,1,0,'C');
                $this->Cell(75,10,$paciente,1,0,'C');
                $this->Cell(30,10,$doctor,1,0,'C');
                $this->Cell(20,10,$total,1,1,'C');
                $totalSum += $total;

                // Agregar el total al array de ventas por doctor
                if (isset($ventasPorDoctor[$doctor])) {
                    $ventasPorDoctor[$doctor] += $total;
                } else {
                    $ventasPorDoctor[$doctor] = $total;
                }
            }
            // Imprimir la suma total
            $this->SetFont('Arial','B',11);
            $this->Cell(155,10,'Total:',1,0,'R');
            $this->Cell(20,10,$totalSum,1,1,'C');

            // Mostrar la tabla de ventas por doctor
            $this->Ln(10);
            $this->Cell(0,10,'Ventas por Doctor',0,1,'C');
            $this->Cell(60,10,'Doctor',1,0,'C',1);
            $this->Cell(60,10,'Venta Total',1,1,'C',1);

            foreach ($ventasPorDoctor as $doctor => $ventaTotal) {
                $this->SetFont('Arial','',11);
                $this->Cell(60,10,$doctor,1,0,'C');
                $this->Cell(60,10,$ventaTotal,1,1,'C');
            }
        } else {
            $this->SetFont('Arial','B',12);
            $this->Cell(0,10,'No se encontraron compras en el rango de fechas especificado',0,1,'C');
        }
    }
}

// Crear instancia de la clase PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

require('../conection/conection.php');

$conn = $mysqli;

// Verificar la conexión
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Obtener los valores de las fechas filtradas (reemplaza con tus propios valores)
$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Generar el informe en PDF si las fechas están presentes
if (!empty($fechaInicio) && !empty($fechaFin)) {
    $pdf->GenerateReport($conn, $fechaInicio, $fechaFin);
} else {
    $pdf->SetFont('Arial','B',12);
    $pdf->Cell(0,10,'No se especificaron fechas',0,1,'C');
}

// Cerrar la conexión a la base de datos
$conn->close();

// Salida del PDF
$pdf->Output();
?>
