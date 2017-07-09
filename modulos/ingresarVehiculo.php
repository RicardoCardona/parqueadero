<?php
	try{
		session_start();
		require "conexion.php";
		
		$placaVehiculo=$_POST['placaVehiculo'];
		$tipoVehiculo=$_POST['tipoVehiculo'];
		$descripcion=$_POST['descripcion'];
		$modalidad=$_POST['modalidad'];

		$sqlV=$conn->prepare('SELECT * FROM configuracion');
		$resultadoV=$sqlV->execute(array());
		$resultadoV=$sqlV->fetchAll();
		$numV=$sqlV->rowCount();

		if ($numV>=1) {
			foreach ($resultadoV as $row) {			
				$parqueadero=$row["idParqueadero"];
			}
		}

		$urlPlantas= 'http://2park.co/serviceApp/consultarPlantas.php?parqueadero='.$parqueadero;
		$jsonPlantas = file_get_contents($urlPlantas);		
		$arrayPlantas = json_decode($jsonPlantas, true);
		$cantArray = count($arrayPlantas);

		echo $clave = array_search('A', $arrayPlantas);

		//consulta para no repetir vehiculos
		$sql5=$conn->prepare('SELECT * FROM vehiculos WHERE placa = :P1');
		$resultado5=$sql5->execute(array('P1'=>$placaVehiculo));
		$resultado5=$sql5->fetchAll();
		$num5=$sql5->rowCount();

		//si el vehiculo existe que extraga el id de ese de vehiculo		
		//positivo
		if ($num5>=1) {
			foreach ($resultado5 as $fila) {
				$fila['idVehiculo'];
			}
			$sql2=$conn->prepare('SELECT MAX(idVehiculo) FROM vehiculos WHERE placa = :P1');
			$resultado2=$sql2->execute(array('P1'=>$placaVehiculo));
			$resultado2=$sql2->fetchAll();
			$num2=$sql2->rowCount();

			if ($num2>=1) {
				foreach ($resultado2 as $fila) {
					$idVehiculo=$fila['MAX(idVehiculo)'];
				}				

				$sql3=$conn->prepare('SELECT idTarifa FROM tarifas WHERE idModalidad = :P1 AND idTipoVehiculo = :P2');
				$resultado3=$sql3->execute(array('P1'=>$modalidad, 'P2'=>$tipoVehiculo));
				$resultado3=$sql3->fetchAll();
				$num3=$sql3->rowCount();

				if ($num3>=1) {
					foreach ($resultado3 as $fila) {
						$fila['idTarifa'];
					}

					$sql4=$conn->prepare('INSERT INTO flujovehiculo (idVehiculo, descripcion, idCaja, idTarifa, idUsuarioEntrada) VALUES (:P1, :P2, :P3, :P4, :P5)');
					$resultado4=$sql4->execute(array('P1'=>$idVehiculo,'P2'=>$descripcion, 'P3'=>$_SESSION["idCaja"], 'P4'=>$fila['idTarifa'], 'P5'=>$_SESSION['idUser']));
					$num4=$sql4->rowCount();

					if ($num4>=1) {
						echo '
							<div class="alert alert-success alert-dismissable">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								El Vehículo <strong>'.$placaVehiculo.'</strong> Fue Ingresado con Éxito.
							</div>
						';
					}else{
						echo '
							<div class="alert alert-danger alert-dismissable">
								<button type="button" class="close" data-dismiss="alert">&times;</button>
								El Vehículo <strong>'.$placaVehiculo.'</strong> NO Fue Ingresado.
							</div>
						';
					}
				}
			}else{
				echo '
					<div class="alert alert-danger alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						El Vehículo <strong>'.$placaVehiculo.'</strong> NO Fue Ingresado.
					</div>
				';
			}

		//si el vehiculo NO existe inserte un nuevo vehiculo en la BD
		//en caso de actualización utilizar el lado negativo, ya que contiene por completo el codigo, para la parte positiva quitar el insert into vehiculos con su respectivo if y else.
		// negativo
		}else{
			$sql=$conn->prepare('INSERT INTO vehiculos (placa, idTipoVehiculo) VALUES (:P1, :P2)');
			$resultado=$sql->execute(array('P1'=>$placaVehiculo, 'P2'=>$tipoVehiculo));
			$num=$sql->rowCount();

			if ($num>=1) {
				$sql2=$conn->prepare('SELECT MAX(idVehiculo) FROM vehiculos WHERE placa = :P1');
				$resultado2=$sql2->execute(array('P1'=>$placaVehiculo));
				$resultado2=$sql2->fetchAll();
				$num2=$sql2->rowCount();

				if ($num2>=1) {
					foreach ($resultado2 as $fila) {
						$idVehiculo=$fila['MAX(idVehiculo)'];
					}				

					$sql3=$conn->prepare('SELECT idTarifa FROM tarifas WHERE idModalidad = :P1 AND idTipoVehiculo = :P2');
					$resultado3=$sql3->execute(array('P1'=>$modalidad, 'P2'=>$tipoVehiculo));
					$resultado3=$sql3->fetchAll();
					$num3=$sql3->rowCount();

					if ($num3>=1) {
						foreach ($resultado3 as $fila) {
							$fila['idTarifa'];
						}

						$sql4=$conn->prepare('INSERT INTO flujovehiculo (idVehiculo, descripcion, idCaja, idTarifa, idUsuarioEntrada) VALUES (:P1, :P2, :P3, :P4, :P5)');
						$resultado4=$sql4->execute(array('P1'=>$idVehiculo,'P2'=>$descripcion, 'P3'=>$_SESSION["idCaja"], 'P4'=>$fila['idTarifa'], 'P5'=>$_SESSION["idUser"]));
						$num4=$sql4->rowCount();

						if ($num4>=1) {
							echo '
								<div class="alert alert-success alert-dismissable">
									<button type="button" class="close" data-dismiss="alert">&times;</button>
									El Vehículo <strong>'.$placaVehiculo.'</strong> Fue Ingresado con Éxito.
								</div>
							';
						}else{
							echo '
								<div class="alert alert-danger alert-dismissable">
									<button type="button" class="close" data-dismiss="alert">&times;</button>
									El Vehículo <strong>'.$placaVehiculo.'</strong> NO Fue Ingresado.
								</div>
							';
						}
					}
				}else{
					echo '
						<div class="alert alert-danger alert-dismissable">
							<button type="button" class="close" data-dismiss="alert">&times;</button>
							El Vehículo <strong>'.$placaVehiculo.'</strong> NO Fue Ingresado.
						</div>
					';
				}
			}else{
				echo '
					<div class="alert alert-danger alert-dismissable">
	  					<button type="button" class="close" data-dismiss="alert">&times;</button>
	  					El Vehículo <strong>'.$placaVehiculo.'</strong> NO Fue Ingresado.
					</div>
				';
			}		
		}

		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>