<?php
require('pdf/fpdf_6.php');

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../img/logo.png', 27, 3, 20);

        $this->Ln(5);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-10);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 4);
        // Page number
        $this->Cell(0, 10, utf8_decode('Guarde este comprobante si tiene algún reclamo ') , 0, 0, 'C');
    }
}

// Instanciation of inherited class
require('../conection/conection.php');

$venta_id = $_GET['venta_id'];

// Consultar datos de la cabecera de la venta
$consulta = "SELECT * FROM ventas_cabecera WHERE id='$venta_id' ";
$resultado = $mysqli->query($consulta);
$total_venta;
$descuento;
if ($resultado->num_rows > 0) {
    $cabecera_venta = $resultado->fetch_assoc();
    
    // Obtener el ID del cliente desde la cabecera de la venta
    $id_paciente = $cabecera_venta['id_paciente'];
    $total_venta = $cabecera_venta['total'];
    $descuento = $cabecera_venta['descuento'];
    $fecha_cliente = $cabecera_venta['fecha_venta'] ;
    
    // Consultar el nombre del cliente utilizando el ID
    $consulta_cliente = "SELECT * FROM pacientes WHERE id='$id_paciente'";
    $resultado_cliente = $mysqli->query($consulta_cliente);
    
    if ($resultado_cliente->num_rows > 0) {
        $cliente = $resultado_cliente->fetch_assoc();
        $nombre_cliente = utf8_decode($cliente['nombres']) . ' ' . utf8_decode($cliente['apellidos']);
        
    } else {
        $nombre_cliente = 'Cliente no encontrado';
    }
} else {
    $nombre_cliente = 'Venta no encontrada';
}

// Creación de PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', 'B', 7);
$pdf->Cell(50, 1, utf8_decode('Nota de Venta  ') . utf8_decode(' 001-001-0000') . $venta_id, 0, 0, 'C', 0);
$pdf->Ln(5);

$pdf->SetFont('Times', '', 6);

$pdf->SetFillColor(234, 236, 238);
$pdf->SetDrawColor(182, 182, 182);


$pdf->Cell(15,2,utf8_decode("RUC: 1002003001001"),0,0,'L');
$pdf->Cell(40, 2, "Fecha: " . $fecha_cliente, 0,0,'R' );
$pdf->Ln(3);
$pdf->Cell(15,2,utf8_decode("Email: info@villamedyc.com"),0,0,'L');
$pdf->Ln(3);
$pdf->Cell(15,2,utf8_decode("Teléfono: 0987654321"),0,0,'L');
$pdf->Ln(5);

$pdf->Cell(15, 2, 'Cliente: '.$nombre_cliente, 0,0,'L' );
$pdf->Ln(3);

$cosulta_citas = "select * from ventas_detalle where venta_id ='$venta_id'";
$resultado_citas = $mysqli->query($cosulta_citas);
$ahorro ;

$pdf->Cell(5, 5, utf8_decode('Tipo'), 1, 0, 'C', 1);
$pdf->Cell(28, 5, utf8_decode('Descripción'), 1, 0, 'C', 1);
$pdf->Cell(6, 5, utf8_decode('Can.'), 1, 0, 'C', 1);
$pdf->Cell(8, 5, utf8_decode('P/U'), 1, 0, 'C', 1);
$pdf->Cell(10, 5, utf8_decode('SubT.'), 1, 0, 'C', 1);
$pdf->Ln(5);
while ($row_items = $resultado_citas->fetch_assoc()) {

    $option =  $row_items['tipo_item'];

    if($option == 'producto'){

        $id_producto = $row_items['item_id'];
        $consulta_producto = "SELECT * FROM productos WHERE id='$id_producto'";
        $resultado_pro = $mysqli->query($consulta_producto);
        $produ = $resultado_pro->fetch_assoc();
        $nombre_prodducto = utf8_decode($produ['nombre']) ;
        
        $pdf->Cell(5, 5, 'PRO', 1, 0, 'C', 0);
        $pdf->Cell(28, 5, $nombre_prodducto , 1, 0, 'C', 0);
        $pdf->Cell(6, 5, $row_items['cantidad'], 1, 0, 'C', 0);
        $pdf->Cell(8, 5, $row_items['precio_unitario'], 1, 0, 'C', 0);
        $pdf->Cell(10, 5,$row_items['subtotal'], 'LRT', 'C', 0);
        $pdf->Ln(0.01);

    }

    if($option == 'servicio'){

        $id_servicio = $row_items['item_id'];
        $consulta_servicio = "SELECT * FROM servicios WHERE id_servicio='$id_servicio'";
        $resultado_ser = $mysqli->query($consulta_servicio);
        $serv = $resultado_ser->fetch_assoc();
        $nombre_servicio = utf8_decode($serv['titulo_servicio']) ;
        
        $pdf->Cell(5, 5, 'SER', 1, 0, 'C', 0);
        $pdf->Cell(28, 5, $nombre_servicio , 1, 0, 'C', 0);
        $pdf->Cell(6, 5, $row_items['cantidad'], 1, 0, 'C', 0);
        $pdf->Cell(8, 5, $row_items['precio_unitario'], 1, 0, 'C', 0);
        $pdf->Cell(10, 5,$row_items['subtotal'], 1, 0, 'C', 0);
        $pdf->Ln();

    }

    if($option == 'paquete'){

        $id_paquete = $row_items['item_id'];
        $consulta_paquete = "SELECT * FROM paquete_cabecera WHERE paquete_id='$id_paquete'";
        $resultado_paq = $mysqli->query($consulta_paquete);
        $paque = $resultado_paq->fetch_assoc();
        $nombre_paquete = utf8_decode($paque['titulo_paquete']) ;
        $ahorro = $paque['total'] - $paque['ahorra'];

        $pdf->Cell(5, 5, 'PAQ', 1, 0, 'C', 0);
        $pdf->Cell(28, 5, $nombre_paquete , 1, 0, 'C', 0);
        $pdf->Cell(6, 5, $row_items['cantidad'], 1, 0, 'C', 0);
        $pdf->Cell(8, 5, $row_items['precio_unitario'], 1, 0, 'C', 0);
        $pdf->Cell(10, 5,$row_items['subtotal'], 'LRT', 'C', 0);
        $pdf->Ln(0.01);

    }
   
   
}


$pdf->Cell(47, 5, utf8_decode('Descuento'), 1, 0, 'R', 0);
$pdf->Cell(10, 5, utf8_decode($descuento), 1, 0, 'C', 0);
$pdf->Ln();
$pdf->Cell(47, 5, utf8_decode('Total Pagado'), 1, 0, 'R', 1);
$pdf->Cell(10, 5, utf8_decode($total_venta), 1, 0, 'C', 1);


$pdf->Ln(15);
$pdf->Cell(0, 10, utf8_decode('Ahorro ') , 0, 0, 'C');
$pdf->Ln(3);
$pdf->Cell(0, 10, utf8_decode($ahorro ) , 0, 0, 'C');
$pdf->Ln(3);
$pdf->Cell(0, 10, utf8_decode('Total Ahorro ') , 0, 0, 'C');
$pdf->Ln(3);
$pdf->Cell(0, 10, utf8_decode($ahorro + $descuento ) , 0, 0, 'C');

$pdf->Ln(3);
$pdf->Output();
?>
