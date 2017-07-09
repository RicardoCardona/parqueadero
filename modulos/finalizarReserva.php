<?php
	try{
		// Captura el código de la reserva enviado por el Ajax. 
		$param= $_POST['reserva'];
		$placa= $_POST['placa'];
		
		// Url donde se va a enviar el parámetro para la cancelación de la reserva 
		$url = "http://2park.co/serviceApp/reservacionesTerminarMicropagos.php?cod_reserva=$param&accion=finalizar&metodo=finalizarReserva";
		// echo $url;
		$json = file_get_contents($url);
		$array = json_decode($json, true);

		// echo $array["Bool"];
		// echo $array["state"];
		// echo $array["message"];

		if ($array["Bool"] == true) {
		    // echo "true";
		} else {
		    // echo "false";
		}

		if(!isset($_SESSION)){
			session_start();
		}
		date_default_timezone_set('America/Bogota');
		$hoy=date('Y-m-d H:i:s');
		
		require "conexion.php";

		//datos temporales, hasta definir estructura.
		$tipovehiculo="1";
		$idTarifa="1";

		$sql=$conn->prepare('SELECT idVehiculo FROM vehiculos WHERE placa = :P1');
		$resultado=$sql->execute(array('P1'=>$placa));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			foreach ($resultado as $fila) {
				$idVehiculo=$fila['idVehiculo'];
			}
			$sqlV=$conn->prepare('SELECT * FROM flujovehiculo WHERE idVehiculo = :P1 AND estado = "activo"');
			$resultadoV=$sqlV->execute(array('P1'=>$idVehiculo));
			$resultadoV=$sqlV->fetchAll();
			$numV=$sqlV->rowCount();

			if ($numV>=1) {
				echo 'ERROR! El Vehiculo Ya Fue Ingresado';
			}else{
				$sql2=$conn->prepare('INSERT INTO flujovehiculo (movFechaInicial, idVehiculo, idTarifa, idCaja, idUsuarioEntrada) VALUES (:P1, :P2, :P3, :P4, :P5)');
				$resultado2=$sql2->execute(array('P1'=>$hoy, 'P2'=>$idVehiculo, 'P3'=>$idTarifa, 'P4'=>$_SESSION["idCaja"], 'P5'=>$_SESSION["idUser"]));
				$num2=$sql2->rowCount();

				if ($num2>=1) {
					echo '
						<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm3">Comprobante: '.$placa.'</button>

						<script type="text/javascript">
							var placaVehiculo="'.$placa.'";
							$("#codigoBarras3").JsBarcode(placaVehiculo, {displayValue:true, fontSize:18, width:2, height:50});
						</script>
					';
				}else{
					echo 'no';
				}
			}
		}else{
			$sql3=$conn->prepare('INSERT INTO vehiculos (placa, idTipoVehiculo, createDate) VALUES (:P1, :P2, :P3)');
			$resultado3=$sql3->execute(array('P1'=>$placa, 'P2'=>$tipovehiculo, 'P3'=>$hoy));
			$num3=$sql3->rowCount();

			if ($num3>=1) {
				$sql4=$conn->prepare('SELECT idVehiculo FROM vehiculos WHERE placa = :P1');
				$resultado4=$sql4->execute(array('P1'=>$placa));
				$resultado4=$sql4->fetchAll();
				$num4=$sql4->rowCount();

				if ($num4>=1) {
					foreach ($resultado4 as $fila) {
						$idVehiculo=$fila['idVehiculo'];
					}
					$sql5=$conn->prepare('INSERT INTO flujovehiculo (movFechaInicial, idVehiculo, idTarifa, idCaja, idUsuarioEntrada) VALUES (:P1, :P2, :P3, :P4, :P5)');
					$resultado5=$sql5->execute(array('P1'=>$hoy, 'P2'=>$idVehiculo, 'P3'=>$idTarifa, 'P4'=>$_SESSION["idCaja"], 'P5'=>$_SESSION["idUser"]));
					$num5=$sql5->rowCount();

					if ($num5>=1) {
						echo '
							<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target=".bs-example-modal-sm3">Comprobante: '.$placa.'</button>

							<script type="text/javascript">
								var placaVehiculo="'.$placa.'";
								$("#codigoBarras3").JsBarcode(placaVehiculo, {displayValue:true, fontSize:18, width:2, height:50});
							</script>
						';
					}else{
						echo 'no';
					}					
				}
			}
		}
		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>