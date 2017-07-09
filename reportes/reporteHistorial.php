<?php
    if(!isset($_SESSION)){
        session_start();
    }
    date_default_timezone_set('America/Bogota');
    $db = mysqli_connect("bynary01.com", "bynary", "4QEH^z6Z{6Xv", "bynary_2park");
    mysqli_set_charset($db, "utf8");

    $historial=$_REQUEST['historial'];

    $sql = "SELECT vehiculos.placa, flujovehiculo.movFechaInicial, flujovehiculo.movFechaFinal, flujovehiculo.minutosReales, flujovehiculo.valorFlujoVehiculo FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo WHERE vehiculos.placa LIKE '$historial%' OR flujovehiculo.movFechaInicial LIKE '$historial%' OR flujovehiculo.movFechaFinal LIKE '$historial%' OR flujovehiculo.minutosCalculados LIKE '$historial%' OR flujovehiculo.valorCalculado LIKE '$historial%'";

    $resultado = mysqli_query($db, $sql);
    $registros = mysqli_num_rows($resultado);

    require_once '../clases/PHPExcel.php';

    $objPHPExcel = new PHPExcel();

    //Informacion del excel
    $objPHPExcel->getProperties()
    ->setCreator("2park.com.co")
    ->setLastModifiedBy("2park.com.co")
    ->setTitle("Reporte de Historial")
    ->setSubject("reporte de Historial")
    ->setDescription("Reporte de Historial")
    ->setKeywords("Reporte de Historial")
    ->setCategory("Historial");
    $objPHPExcel->setActiveSheetIndex(0)->setTitle('Reporte Historial');
    
    // Agrego encabezados
    $i = 1;
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, "Placa");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, "Fecha Inicio");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, "Fecha Fin");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, "Tiempo Calculado");
    $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, "Valor Calculado");
    
    if ($registros > 0) {
        $i = 2;
        while ($registro = mysqli_fetch_array($resultado, MYSQLI_ASSOC)) {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $registro['placa']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B' . $i, $registro['movFechaInicial']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C' . $i, $registro['movFechaFinal']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('D' . $i, $registro['minutosReales']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E' . $i, $registro['valorFlujoVehiculo']);
            $i++;
        }
    }

    $historial=date('Y-m-d');
    
    require_once '../clases/PHPExcel/IOFactory.php';
    header('Content-Type: application/vnd.ms-excel; charset = iso-8859-1');
    header('Content-Disposition: attachment;filename="Reporte_Historial_'.$historial.'.xlsx"');
    // header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');

    exit;
    mysql_close();
?>