<?php
require('pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // Cabecera del informe
         // Logo
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
        $sql = "SELECT * FROM compra_cabecera WHERE fecha BETWEEN '$fechaInicio' AND '$fechaFin'";
        $result = $conn->query($sql);
        $this->SetFillColor(234, 236, 238);
        $this->SetDrawColor(182, 182, 182);
        if ($result->num_rows > 0) {
            $this->SetFont('Arial','B',12);
            
            $this->Cell(50,10,'Fecha',1,0,'C',1);
            $this->Cell(90,10,'Proveedor',1,0,'C',1);
            $this->Cell(50,10,'Total',1,1,'C',1);
            
            while ($row = $result->fetch_assoc()) {
                $fecha = $row['fecha'];
                $proveedor =utf8_decode( $row['proveedor']);
                $total = $row['total'];

                $this->SetFont('Arial','',12);
                $this->Cell(50,10,$fecha,1,0,'C');
                $this->Cell(90,10,$proveedor,1,0,'C');
                $this->Cell(50,10,$total,1,1,'C');
                $totalSum += $total;
            }
            // Imprimir la suma total
            $this->SetFont('Arial','B',12);
            $this->Cell(140,10,'Total:',1,0,'R');
            $this->Cell(50,10,$totalSum,1,1,'C');
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
