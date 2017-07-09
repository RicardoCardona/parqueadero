<?php
    if(!isset($_SESSION)){
        session_start();
    }
    date_default_timezone_set('America/Bogota');
    $db = mysqli_connect("bynary01.com", "bynary", "4QEH^z6Z{6Xv", "bynary_2park");
    mysqli_set_charset($db, "utf8");

    $sql = "SELECT tarifas.nombre, tipovehiculo.descripcion, tarifas.tiempoLimite, tarifas.valorTarifa, tarifas.createDate FROM tarifas INNER JOIN tipovehiculo ON tipovehiculo.idTipoVehiculo=tarifas.idTipoVehiculo";

    $resultado = mysqli_query($db, $sql);
    $registros = mysqli_num_rows($resultado);

    require_once '../clases/PHPExcel.php';

    $objPHPExcel = new PHPExcel();

    //Informacion del excel
    $objPHPExcel->getProperties()
    ->setCreator("2park.com.co")
    ->setLastModifiedBy("2park.com.co")
    ->setTitle("Reporte de Tarifas")
    ->setSubject("reporte de Tarifas")
    ->setDescription("Reporte de Tarifas")
    ->setKeywords("Reporte de Tarifas")
    ->setCategory("Tarifas");
    $objPHPExcel->setActiveSheetIndex(0)->setTitle('Reporte Tarifas');
    
    // Agrego encabezados
    $i = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, "Tipo de Tarifa");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, "Vehículo");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, "Tiempo Límite");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, "Valor Tarifa");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, "Fecha Creación");

    if ($registros > 0) {
        $i = 2;
        while ($registro = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $registro['nombre']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $registro['descripcion']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $registro['tiempoLimite']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $registro['valorTarifa']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $registro['createDate']);
            $i++;
        }
    }

    $historial=date('Y-m-d');
    
    require_once '../clases/PHPExcel/IOFactory.php';
    header('Content-Type: application/vnd.ms-excel; charset = iso-8859-1');
    header('Content-Disposition: attachment;filename="Reporte_Tarifa_'.$historial.'.xlsx"');
    // header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

    exit;
    mysql_close();
?>