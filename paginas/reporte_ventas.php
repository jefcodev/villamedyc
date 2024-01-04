<?php
require('pdf/fpdf.php');

class PDF extends FPDF
{
    function Header()
    {
        // Cabecera del informe
        $this->Image('../img/logo.png', 70, 6, 60);
        $this->Ln(25);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(0, 10, 'Informe de Ventas', 0, 1, 'C');
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

    function GenerateReport($conn, $fechaInicio, $fechaFin, $id_user)
    {
        $totalSum = 0;
        $totalVentas = 0; // Contador de ventas

        // Inicializar totales por tipo de pago
        $totalesPorTipoPago = array(
            /* 'Efectivo' => 0,
            'Transferencia' => 0 */
        );

        // Verificar que las fechas sean válidas
        if (!strtotime($fechaInicio) || !strtotime($fechaFin)) {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'Fechas inválidas', 0, 1, 'C');
            return;
        }

        // Consultar las compras en el rango de fechas especificado
        $sql = "SELECT v.fecha_venta, v.id_paciente, v.descuento, v.total, v.tipo_pago, u.usuario
                FROM ventas_cabecera v
                JOIN usuarios u ON v.id_user = u.id
                WHERE v.fecha_venta BETWEEN '$fechaInicio' AND '$fechaFin'";
        if (!empty($id_user)) {
            $sql .= " AND u.id = '$id_user'";
        }
        $sql .= " ORDER BY v.fecha_venta";

        $result = $conn->query($sql);
        $this->SetFillColor(234, 236, 238);
        $this->SetDrawColor(182, 182, 182);

        $ventasPorUsuario = array(); // Array para almacenar las ventas por usuario

        if ($result->num_rows > 0) {
            $this->SetFont('Arial', 'B', 11);

            $this->Cell(30, 10, 'Fecha', 1, 0, 'C', 1);
            $this->Cell(40, 10, 'Paciente', 1, 0, 'C', 1);
            $this->Cell(40, 10, 'Usuario', 1, 0, 'C', 1);
            $this->Cell(30, 10, 'Descuento', 1, 0, 'C', 1);
            $this->Cell(30, 10, 'Total', 1, 1, 'C', 1);

            while ($row = $result->fetch_assoc()) {
                $fecha = $row['fecha_venta'];
                $paciente = utf8_decode($row['id_paciente']);
                $usuario = utf8_decode($row['usuario']);
                $descuento = $row['descuento'];
                $total = $row['total'];
                $tipoPago = $row['tipo_pago'];

                $this->SetFont('Arial', '', 11);
                $this->Cell(30, 10, $fecha, 1, 0, 'C');
                $this->Cell(40, 10, $paciente, 1, 0, 'C');
                $this->Cell(40, 10, $usuario, 1, 0, 'C');
                $this->Cell(30, 10, $descuento, 1, 0, 'C');
                $this->Cell(30, 10, $total, 1, 1, 'C');
                $totalSum += $total;
                $totalVentas++;

                // Actualizar los totales por tipo de pago
                $totalesPorTipoPago[$tipoPago] += $total;

                // Agregar el total al array de ventas por usuario
                if (isset($ventasPorUsuario[$usuario])) {
                    $ventasPorUsuario[$usuario] += $total;
                } else {
                    $ventasPorUsuario[$usuario] = $total;
                }
            }
            // Imprimir la suma total
            $this->SetFont('Arial', 'B', 11);
            $this->Cell(140, 10, 'Total:', 1, 0, 'R');
            $this->Cell(30, 10, $totalSum, 1, 1, 'C');

            // Mostrar la tabla de ventas por usuario
            $this->Ln(10);
            $this->Cell(0, 10, 'Ventas por Usuario', 0, 1, 'C');
            $this->Cell(60, 10, 'Usuario', 1, 0, 'C', 1);
            $this->Cell(40, 10, 'Venta Total', 1, 0, 'C', 1);
            $this->Ln(10);

            foreach ($ventasPorUsuario as $usuario => $ventaTotal) {
                $this->SetFont('Arial', '', 11);
                $this->Cell(60, 10, $usuario, 1, 0, 'C');
                $this->Cell(40, 10, $ventaTotal, 1, 0, 'C');
                $this->Ln(10);
            }
            $this->Ln(10);

            // Mostrar los totales por tipo de pago
            $this->Cell(0, 10, 'Totales por Tipo de Pago', 0, 1, 'C');
            $this->Cell(60, 10, 'Tipo de Pago', 1, 0, 'C', 1);
            $this->Cell(40, 10, 'Total', 1, 0, 'C', 1);
            $this->Ln(10);

            foreach ($totalesPorTipoPago as $tipoPago => $total) {
                $this->SetFont('Arial', '', 11);
                $this->Cell(60, 10, $tipoPago, 1, 0, 'C');
                $this->Cell(40, 10, $total, 1, 0, 'C');
                $this->Ln(10);
            }


            // Mostrar el total de ventas
            $this->Ln(10);
            $this->Cell(60, 10, 'Productos', 1, 0, 'C', 1);
            $this->Cell(60, 10, $totalVentas, 1, 0, 'C');


            // Mostrar el total de ventas
            $this->Ln(10);
            $this->Cell(60, 10, 'Pacientes', 1, 0, 'C', 1);
            $this->Cell(60, 10, $totalVentas, 1, 0, 'C');
        } else {
            $this->SetFont('Arial', 'B', 12);
            $this->Cell(0, 10, 'No se encontraron compras en el rango de fechas especificado', 0, 1, 'C');
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

// Obtener los valores de las fechas y el id_user filtrados (reemplaza con tus propios valores)
$fechaInicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fechaFin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$id_user = isset($_GET['id_user']) ? $_GET['id_user'] : '';

// Generar el informe en PDF
$pdf->GenerateReport($conn, $fechaInicio, $fechaFin, $id_user);

// Cerrar la conexión a la base de datos
$conn->close();

// Salida del PDF
$pdf->Output();
?>
