<?php

require_once 'core.php';

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {

    $DETALLEBODEGAID=$_POST['detallebodega'];
    $NOMPRODUCT = $_POST['NOMPRODUCT'];
    $UNIDADESID = $_POST['UNIDADESID'];
    $STOCKBODEGA = $_POST['STOCKBODEGA'];
    $DESCRIPBODEGA = $_POST['DESCRIPBODEGA'];
    $PRECIOBODEGA = $_POST['PRECIOBODEGA'];



        $sql1 = "INSERT INTO DETALLEBODEGA (PRODUCTOID,UNIDADESID,BODEGAID,STOCKBODEGA,PRECIOBODEGA,DESCRIPBODEGA) 
        VALUES ($NOMPRODUCT,$UNIDADESID,  $DETALLEBODEGAID , $STOCKBODEGA,  $PRECIOBODEGA, ' $DESCRIPBODEGA')";
        $EJECUTADO1 = mysqli_query($connect, $sql1);



    if ($EJECUTADO1) {
        $valid['success'] = true;
        $valid['messages'] = "Creado exitosamente";
    } else {
        $valid['success'] = false;
        $valid['messages'] = "Error no se ha podido guardar";
    }


    $connect->close();

    echo json_encode($valid);
} // /if $_POST