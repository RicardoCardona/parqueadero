<?php
	// conexión a la base de datos mediante msqli 
	require "conexion2.php";

	// Consulta en la tabla configuración para identificar cual es el parqueadero que se va a enviar como parámetro 
	$sql= "SELECT * FROM configuracion";
	$resultado=$conexion->query($sql);
	$num= $resultado->num_rows;

	if ($num>=1) {
		foreach ($resultado as $row) {
			// Variable con el codigo del parqueadero
			$parqueadero=$row["idParqueadero"];
		}
	}

	// Url del web service al cual se le esta mandando el parámetro del parqueadero 
	$urlPlantas= 'http://2park.co/serviceApp/consultarNotificacion.php?parqueadero='.$parqueadero;
	$jsonPlantas = file_get_contents($urlPlantas);
	// Convierte los datos que vienen en formato JSON y los deja en Array 
	$arrayPlantas = json_decode($jsonPlantas, true);
	$cantArray = count($arrayPlantas);
	// var_dump($arrayPlantas);

	if ($cantArray>=1) {
		for ($i=0; $i < $cantArray ; $i++) { 
			$reserva=$arrayPlantas[$i]["Reserva"];
			// Array Numero de puesto.
			$numeroPuesto= $arrayPlantas[$i]["NombrePuesto"];
			// Array Placa 
			$placa= $arrayPlantas[$i]["Placa"];
			// Array Distancia 
			$distancia= $arrayPlantas[$i]["Distancia"];
			// Redondea el numero de km
			$round= round($distancia);
			$km= $round / 1000;

			// Notificacion de la reserva 
			echo '
				<div id="notificacion">
					<h5 class="espacio">Placa : '.$placa. '</h5>
					<h5 class="espacio">Puesto : '.$numeroPuesto. '</h5>
					<h5 class="espacio">Distancia : '.round($km,1).' Km</h5>
				</div>
			';

			// try{
			// 	require "conexion.php";

			// 	$placa="HIJ748";

			// 	$sql=$conn->prepare('SELECT idVehiculo FROM vehiculos WHERE placa = :P1');
			// 	$resultado=$sql->execute(array('P1'=>$placa));
			// 	$resultado=$sql->fetchAll();
			// 	$num=$sql->rowCount();

			// 	//operación de caluclo
			// 	if ($num>=1) {
			// 		foreach ($resultado as $fila) {
			// 			$idVehiculo=$fila['idVehiculo'];
			// 		}
			// 		$sql2=$conn->prepare('INSERT INTO flujovehiculo (idVehiculo) VALUES (:P1)');
			// 		$resultado2=$sql2->execute(array('P1'=>$idVehiculo));
			// 		$num2=$sql2->rowCount();

			// 		if ($num2>=1) {
			// 			echo "imprimir recibo";
			// 		}else{
			// 			echo 'no';
			// 		}
			// 	}else{
			// 		$sql3=$conn->prepare('INSERT INTO vehiculos (placa) VALUES (:P1)');
			// 		$resultado3=$sql3->execute(array('P1'=>$placa));
			// 		$num3=$sql3->rowCount();

			// 		if ($num3>=1) {
			// 			$sql4=$conn->prepare('SELECT idVehiculo FROM vehiculos WHERE placa = :P1');
			// 			$resultado4=$sql4->execute(array('P1'=>$placa));
			// 			$resultado4=$sql4->fetchAll();
			// 			$num4=$sql4->rowCount();

			// 			if ($num4>=1) {
			// 				foreach ($resultado4 as $fila) {
			// 					$idVehiculo=$fila['idVehiculo'];
			// 				}
			// 				$sql5=$conn->prepare('INSERT INTO flujovehiculo (idVehiculo) VALUES (:P1)');
			// 				$resultado5=$sql5->execute(array('P1'=>$idVehiculo));
			// 				$num5=$sql5->rowCount();

			// 				if ($num5>=1) {
			// 					echo "imprimir recibos";
			// 				}else{
			// 					echo 'no';
			// 				}
			// 			}
			// 		}	
			// 	}
			// 	$conn=NULL; //Cerrar la Conexión
			// }catch(PDOException $e){
			// 	echo "ERROR: ".$e->getMessage();
			// 	exit();
			// }
		}
	}
?>