<?php
	session_start();
	// conexión a la base de datos mediante msqli 
	$conexion = new mysqli("bynary01.com", "bynary", "4QEH^z6Z{6Xv", "bynary_2park");

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
	$urlPlantas= 'http://2park.co/serviceApp/consultarPlantas.php?parqueadero='.$parqueadero;
	$jsonPlantas = file_get_contents($urlPlantas);
	// Convierte los datos que vienen en formato JSON y los deja en Array 
	$arrayPlantas = json_decode($jsonPlantas, true);
	// $arrayPlantas=var_dump($arrayPlantas);
	$cantArray = count($arrayPlantas);

	$estadoA=0;
	$estadoI=0;
	$estadoPO=0;
	$estadoPF=0;
	for($i=0; $i<$cantArray; $i++){
		if($arrayPlantas[$i]['Estado']=='A'){
			$estadoA=$estadoA+1;

		}elseif($arrayPlantas[$i]['Estado']=='I'){
			$estadoI=$estadoI+1;
		
		}elseif($arrayPlantas[$i]['Estado']=='PO'){
			$estadoPO=$estadoPO+1;

		}elseif($arrayPlantas[$i]['Estado']=='PF'){
			$estadoPF=$estadoPF+1;
		}
	}

	echo '
		<div class="alert alert-success alert-dismissable">
			<h5>Puestos: <strong>'.$cantArray.'</strong> &nbsp; Disponibles: <strong>'.$estadoA.'</strong> &nbsp; Reservados: <strong>'.$estadoI.'</strong> &nbsp; Ocupados: <strong>'.$estadoPO.'</strong> &nbsp; Falla: <strong>'.$estadoPF.'</strong></h5>
		</div>
	';

	for ($i=0; $i <$cantArray; $i++) { 
		// Array Numero de puesto.
		$numeroPuesto= $arrayPlantas[$i]["Numero_Puesto"];
		// Array Estado de puesto.
		$estado= $arrayPlantas[$i]["Estado"];
		// Array Nombre de Planta.
		$plantas= $arrayPlantas[$i]["Nombre_Planta"];
		// Array Codigo reserva.
		$reserva= $arrayPlantas[$i]["CodigoReserva"];
		// Array  placa de reserva.
		$placa= $arrayPlantas[$i]["PlacaReserva"];
		// echo $plantas;

		// Se generan los botones consultados según los datos consultados en el web service
		if ($estado == "I") {
			// Reservados
			echo '<div class="result" style="width: 3em; display: inline-block;"><button class="reserva" data-reserva="'.$reserva.'" title="Reservado: '.$placa.'" data-placa="'.$placa.'" style="color: #000; background: orange; width: 100%; margin-right: 0.2em;  text-align: center;">'."P".$plantas."-".$numeroPuesto.'</button></div>';
		}elseif ($estado == "A") {
			// Disponibles
			echo '<button  title="Disponible" style="color: #000; background: #00FF00; width: 3em; margin-right: 0.2em; display: inline-block; text-align: center;">'."P".$plantas."-".$numeroPuesto.'</button>';
		}elseif ($estado == "PO") {
			echo '<button  style="color: #000; background: red; width: 3em; margin-right: 0.2em; display: inline-block; text-align: center;">'."P".$plantas."-".$numeroPuesto.'</button>';
		}elseif ($estado == "PF") {
			echo '<button style="color: white; background: black; width: 3em; margin-right: 0.2em; display: inline-block; text-align: center;" class="botonCirculo">'."P".$plantas."-".$numeroPuesto.'</button>';
		}else{
			echo '<button title="Sin Puestos"  style="color: #000; background: red; width: 4em; margin-right: 0.2em; display: inline-block; text-align: center;">P'.$plantas.'-SP</button>';
		}
	}			
?>