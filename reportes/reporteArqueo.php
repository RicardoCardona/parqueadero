<?php
    if(!isset($_SESSION)){
        session_start();
    }
    date_default_timezone_set('America/Bogota');
    $db = mysqli_connect("bynary01.com", "bynary", "4QEH^z6Z{6Xv", "bynary_2park");
    mysqli_set_charset($db, "utf8");

    $fechaInicio=$_REQUEST['fechaInicio'];
    $fechaFin=$_REQUEST['fechaFin'];
    $estado="inactivo";

    $sql2 = "SELECT * FROM movimientoscaja WHERE fechaApertura BETWEEN '$fechaInicio' AND '$fechaFin'";

    $resultado2 = mysqli_query($db, $sql2);
    $registros2 = mysqli_num_rows($resultado2);

    if ($registros2 > 0) {
        while ($registro2 = mysqli_fetch_array($resultado2, MYSQLI_ASSOC)) {
            $base=$registro2['base'];
        }
    }

    $sql = "SELECT *, vehiculos.placa, tipoPago.tipoPago FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo INNER JOIN tipoPago ON tipoPago.idTipoPago=flujovehiculo.idTipoPago WHERE movFechaInicial BETWEEN '$fechaInicio' AND '$fechaFin' AND estado = '$estado'";

    $resultado = mysqli_query($db, $sql);
    $registros = mysqli_num_rows($resultado);

    require_once '../clases/PHPExcel.php';

    $objPHPExcel = new PHPExcel();

    //Informacion del excel
    $objPHPExcel->getProperties()
    ->setCreator("2park.com.co")
    ->setLastModifiedBy("2park.com.co")
    ->setTitle("Reporte de Arqueo")
    ->setSubject("reporte de Arqueo")
    ->setDescription("Reporte de Arqueo")
    ->setKeywords("Reporte de Arqueo")
    ->setCategory("Arqueo");
    $objPHPExcel->setActiveSheetIndex(0)->setTitle('Reporte Arqueo');
    
    // Agrego encabezados
    $i = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, "Placa");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, "Fecha Inicio");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, "Fecha Fin");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, "Tiempo Calculado");    
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, "Tipo De Pago");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, "Codigo de Transación");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, "Valor Calculado");
    
    if ($registros > 0) {
        $i = 2;
        while ($registro = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $registro['placa']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $registro['movFechaInicial']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $registro['movFechaFinal']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $registro['minutosReales']);            
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $registro['tipoPago']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F' . $i, $registro['codigoTransacion']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $registro['valorFlujoVehiculo']);
            $valorTotal=$registro['valorFlujoVehiculo']+$valorTotal;
            $i++;            
        }
        $totalBase=$valorTotal+$base;     
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $base);
        $i=$i+1;
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G' . $i, $totalBase);
    }

    $historial=date('Y-m-d');

    require_once '../clases/PHPExcel/IOFactory.php';
    header('Content-Type: application/vnd.ms-excel; charset = iso-8859-1');
    header('Content-Disposition: attachment;filename="Reporte_Arqueo_'.$historial.'.xlsx"');
    // header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

    exit;
    mysql_close();
?>