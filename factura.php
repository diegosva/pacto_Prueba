

<?php
session_start();
require_once 'php_action/db_connect.php';
require "/xampp/htdocs/pacto/assests/fpdf/fpdf.php";
?>

<?php

$ASOID = $_SESSION['asoId'];
$DETALLEID = $_POST['DETALLEID'];

$sql = "SELECT 
	DETALLEFACTURA.PEDIDOSID AS PEDIDOID, 
	USUARIO.NOMBREAPEUSU AS NOMBREAPEUSU,
	USUARIO.CEDULAUSU AS CEDULAUSU,
	USUARIO.TELCUSU AS TELCUSU,
	USUARIO.TELCELUSU AS TELCELUSU,
	USUARIO.EMAILUSU AS EMAILUSU,
	USUARIO.DIRUSU AS DIRUSU,
	PRODUCTO.NOMPRODUCT AS NOMPRODUCT,
	DETALLEFACTURA.CANTIDAD AS CANTIDAD,
	PRODUCTO.PRECIOPRODUCT AS PRECIOPRODUCT, 
	PRODUCTO.PVPPRODUCT AS PVPPRODUCT,
	PEDIDOS.FECHAPEDIDO AS FECHAPEDIDO,
    DETALLEFACTURA.TOTALPRODUCT AS TOTALPRODUCT,
    PRODUCTO.DESCRIPCIONPRODUCT AS DESCRIPCIONPRODUCT,
    COUNT(DETALLEFACTURA.PEDIDOSID) AS PEDIDOIDCOUNT, 
	ESTADOPEDIDO.NOMBREESPE AS NOMBREESPE
	FROM DETALLEFACTURA, PEDIDOS, PRODUCTO, ASOCIACION, PERTENECEN, USUARIO ,ESTADOPEDIDO
	WHERE DETALLEFACTURA.PEDIDOSID = PEDIDOS.PEDIDOSID  
	AND DETALLEFACTURA.PRODUCTOID = PRODUCTO.PRODUCTOID 
	AND PRODUCTO.PERTENECENID = PERTENECEN.PERTENECENID 
	AND PEDIDOS.USUARIOID = USUARIO.USUARIOID 
	AND PEDIDOS.ESTADOPEDIDOID=ESTADOPEDIDO.ESTADOPEDIDOID
	AND PERTENECEN.ASOCIACIONID = $ASOID
	AND PEDIDOS.STATUSPEDIDO = 1
	AND DETALLEFACTURA.PEDIDOSID=$DETALLEID
	GROUP BY PRODUCTO.PRODUCTOID";
$r = mysqli_query($connect, $sql) or die("Error al leer ");
$r2 = mysqli_query($connect, $sql) or die("Error al leer ");

while ($fila = $r2->fetch_assoc()) {
    $PEDIDOIDCOUNT = $fila['PEDIDOIDCOUNT'];
}
$LOGOASO = $_POST['LOGOASO'];
$PEDIDOSID = $_POST['PEDIDOSID'];
$NOMBREAPEUSU = $_POST['NOMBREAPEUSU'];
$CEDULAUSU = $_POST['CEDULAUSU'];
$TELCUSU = $_POST['TELCUSU'];
$TELCELUSU = $_POST['TELCELUSU'];
$EMAILUSU = $_POST['EMAILUSU'];
$DIRUSU = $_POST['DIRUSU'];
$FECHAPEDIDO = $_POST['FECHAPEDIDO'];
$TELCELUSU = $_POST['TELCELUSU'];
$ASONOM = $_POST['ASONOM'];

$SECTORASO = $_POST['SECTORASO'];
$BARRIOASO = $_POST['BARRIOASO'];
$PARROQUIAASO = $_POST['PARROQUIAASO'];

?>


<?php




$pdf = new FPDF($orientation = 'P', $unit = 'mm');
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 20);
$textypos = 5;
$pdf->setY(12);
$pdf->setX(10);
// Agregamos los datos de la empresa
$pdf->Cell(5, $textypos, utf8_decode($ASONOM));

$pdf->Image($LOGOASO,130,-5,70,-400);
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "DE:");
$pdf->SetFont('Arial', '', 10);
$pdf->setY(45);
$pdf->setX(10);
$pdf->Cell(5, $textypos, utf8_decode("Asociación: ") . utf8_decode($ASONOM));
$pdf->setY(50);
$pdf->setX(10);
$pdf->Cell(5, $textypos,  "Sector: " . utf8_decode($SECTORASO));
$pdf->setY(55);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "Parroquia: " . utf8_decode($PARROQUIAASO));


// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(75);
$pdf->Cell(5, $textypos, "PARA:");
$pdf->SetFont('Arial', '', 10);
$pdf->setY(45);
$pdf->setX(75);
$pdf->Cell(5, $textypos, "Nombre: " . utf8_decode($NOMBREAPEUSU));
$pdf->setY(50);
$pdf->setX(75);
$pdf->Cell(5, $textypos, utf8_decode("Cédula: ") . utf8_decode($TELCELUSU));
$pdf->setY(55);
$pdf->setX(75);
$pdf->Cell(5, $textypos, utf8_decode("Dirección: ") . utf8_decode($DIRUSU));
$pdf->setY(60);
$pdf->setX(75);
$pdf->Cell(5, $textypos, "Celular: " . utf8_decode($TELCELUSU));
$pdf->setY(65);
$pdf->setX(75);
$pdf->Cell(5, $textypos, utf8_decode("Teléfono: ") . utf8_decode($TELCUSU));
$pdf->setY(70);
$pdf->setX(75);
$pdf->Cell(5, $textypos, utf8_decode("Email: ") . utf8_decode($EMAILUSU));

// Agregamos los datos del cliente
$pdf->SetFont('Arial', 'B', 10);
$pdf->setY(40);
$pdf->setX(135);
$pdf->Cell(5, $textypos, "RECIBO #" . $DETALLEID);
$pdf->SetFont('Arial', '', 10);
$pdf->setY(45);
$pdf->setX(135);
$pdf->Cell(5, $textypos, "Fecha: " . $FECHAPEDIDO);


/// Apartir de aqui empezamos con la tabla de productos
$pdf->setY(80);
$pdf->setX(135);
$pdf->Ln();
/////////////////////////////
//// Array de Cabecera
$header = array("Producto", "Descripcion", "Cant.", "Precio", "Total");
//// Arrar de Productos
$i = 0;


$products = [100];
while ($fila = $r->fetch_assoc()) {

    $CANTIDAD = $fila['CANTIDAD'];
    $PRECIOPRODUCT = $fila['PRECIOPRODUCT'];
    $PVPPRODUCT = $fila['PVPPRODUCT'];
    $DESCRIPCIONPRODUCT = $fila['DESCRIPCIONPRODUCT'];
    $NOMPRODUCT = $fila['NOMPRODUCT'];
    $PRECIOPRODUCT = $fila['PRECIOPRODUCT'];
    $TOTALPRODUCT = $fila['TOTALPRODUCT'];

    $products[$i] = array($NOMPRODUCT, $DESCRIPCIONPRODUCT, $CANTIDAD, $PRECIOPRODUCT);
    $i++;
}
// Column widths
$w = array(40, 75, 20, 25, 25);
// Header
for ($i = 0; $i < count($header); $i++)
    $pdf->Cell($w[$i], 7, $header[$i], 1, 0, 'C');
$pdf->Ln();
// Data
$total = 0;
foreach ($products as $row) {
    $pdf->Cell($w[0], 6, $row[0], 1);
    $pdf->Cell($w[1], 6, $row[1], 1);
    $pdf->Cell($w[2], 6, number_format($row[2]), '1', 0, 'R');
    $pdf->Cell($w[3], 6, "$ " . number_format($row[3], 2, ".", ","), '1', 0, 'R');
    $pdf->Cell($w[4], 6, "$ " . number_format($row[3] * $row[2], 2, ".", ","), '1', 0, 'R');

    $pdf->Ln();
    $total += $row[3] * $row[2];
    $impuesto = $total * 0.12;
}
/////////////////////////////
//// Apartir de aqui esta la tabla con los subtotales y totales
$yposdinamic = 80 + (count($products) * 10);

$pdf->setY($yposdinamic);
$pdf->setX(235);
$pdf->Ln();
/////////////////////////////
$header = array("", "");
$data2 = array(
    array("Subtotal", $total),
    array("Impuesto", $impuesto),
    array("Total", $total+$impuesto),
);
// Column widths
$w2 = array(40, 40);
// Header

$pdf->Ln();
// Data
foreach ($data2 as $row) {
    $pdf->setX(115);
    $pdf->Cell($w2[0], 6, $row[0], 1);
    $pdf->Cell($w2[1], 6, "$ " . number_format($row[1], 2, ".", ","), '1', 0, 'R');

    $pdf->Ln();
}
/////////////////////////////

$yposdinamic += (count($data2) * 10);
$pdf->SetFont('Arial', 'B', 10);

$pdf->setY($yposdinamic);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "TERMINOS Y CONDICIONES");
$pdf->SetFont('Arial', '', 10);

$pdf->setY($yposdinamic + 10);
$pdf->setX(10);
$pdf->Cell(5, $textypos, "El cliente se compromete a pagar la factura.");
$pdf->setY($yposdinamic + 20);
$pdf->setX(10);



$pdf->output();









?>