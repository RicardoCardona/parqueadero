<?php
    // Compañia: Bynary01
    // Proyecto: 2park
    // Fecha: 25/11/2016
    // Autor: Ricardo Cardona
    // Comentarios: Web service para Sincronización de Historial

	require "../modulos/conexion.php";

	$sql=$conn->prepare('SELECT flujovehiculo.idFlujoVehiculo, flujovehiculo.movFechaInicial, flujovehiculo.movFechaFinal, flujovehiculo.minutosCalculados, flujovehiculo.valorFlujoVehiculo, flujovehiculo.ivaFlujoVehiculo, flujovehiculo.valorCalculado, flujovehiculo.dineroRecibido, flujovehiculo.cambio, vehiculos.placa, tarifas.nombre, flujovehiculo.idUsuarioEntrada, flujovehiculo.idUsuarioSalida, configuracion.idParqueadero FROM factura INNER JOIN configuracion ON configuracion.idParqueadero=factura.idParqueadero INNER JOIN flujovehiculo ON flujovehiculo.idFlujoVehiculo=factura.idFlujoVehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo INNER JOIN tarifas ON tarifas.idTarifa=flujovehiculo.idTarifa WHERE flujovehiculo.sincronizado = "no" AND flujovehiculo.estado = "inactivo"');
	$resultado=$sql->execute(array());
	$resultado=$sql->fetchAll();
	$num=$sql->rowCount();

	if ($num>=1) {
		$jsonHistorial = "[";
		foreach ($resultado as $fila) {
            $jsonHistorial .= '{"idFlujoVehiculo":"'.$fila['idFlujoVehiculo'].'","movHoraInicial":"'.substr($fila['movFechaInicial'], 11).'","movFechaInicial":"'.substr($fila['movFechaInicial'], 0, 10).'","movHoraFinal":"'.substr($fila['movFechaFinal'], 11).'","movFechaFinal":"'.substr($fila['movFechaFinal'], 0, 10).'","minutosCalculados":"'.$fila['minutosCalculados'].'","valorFlujoVehiculo":"'.$fila['valorFlujoVehiculo'].'","ivaFlujoVehiculo":"'.$fila['ivaFlujoVehiculo'].'","valorCalculado":"'.$fila['valorCalculado'].'","dineroRecibido":"'.$fila['dineroRecibido'].'","cambio":"'.$fila['cambio'].'","placa":"'.$fila['placa'].'","nombre":"'.$fila['nombre'].'","idUsuarioEntrada":"'.$fila['idUsuarioEntrada'].'","idUsuarioSalida":"'.$fila['idUsuarioSalida'].'","idParqueadero":"'.$fila['idParqueadero'].'"},';
		}
		$jsonHistorial = substr_replace($jsonHistorial, '', -1); // to get rid of extra comma
		$jsonHistorial .= "]";
		$jsonHistorial;

        $urlHistorial = "http://2park.co/serviceApp/webServiceHistorial.php?idSincronizacionHistorial=$jsonHistorial";
        echo $jsonConfirmacion = trim(file_get_contents($urlHistorial), "\xEF\xBB\xBF");
		$arrayHistorial = json_decode(trim($jsonConfirmacion), TRUE);
		$cantArray = count($arrayHistorial);

		for ($i=0; $i<$cantArray ; $i++) {
			$idFlujoVehiculo= $arrayHistorial[$i]["idFlujoVehiculo"];
			$sincronizado= $arrayHistorial[$i]["sincronizado"];
			$idParqueadero= $arrayHistorial[$i]["idParqueadero"];

			try{
				$sql2=$conn->prepare('SELECT * FROM configuracion WHERE idParqueadero = :P1');
				$resultado2=$sql2->execute(array('P1'=>$idParqueadero));
				$resultado2=$sql2->fetchAll();
				$num2=$sql2->rowCount();

				if ($num2>=1) {
					$sql3=$conn->prepare('UPDATE flujovehiculo SET sincronizado = :P2 WHERE idFlujoVehiculo = :P1');
					$resultado3=$sql3->execute(array('P1'=>$idFlujoVehiculo, 'P2'=>$sincronizado));
					$num3=$sql3->rowCount();

					if ($num3>=1) {
						echo '<h3 style="color: green;">El Historial de Mensajes Fue Sincronizado Correctamente.</h3>';
					}else{
						echo '<h3 style="color: red;">El Historial de Mensajes <strong>NO</strong> Fue Sincronizado.</<h3>';
					}
				}else{
                    echo '<h3 style="color: red;">El Parqueadero No Fue Encontrado.</<h3>';
				}
            }catch(PDOException $e){
                echo "ERROR: ".$e->getMessage();
                exit();
            }
        }
	}else{
		echo "Error Inesperado";
	}
?>