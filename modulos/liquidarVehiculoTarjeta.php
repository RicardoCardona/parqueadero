<?php
	try{
		session_start();
		require "conexion.php";

		$idIngresarVehiculo=$_POST['idIngresarVehiculo'];
		$placaVehiculoLiquidar=$_POST['placaVehiculoLiquidar'];
		$tiempoCalculadoLiquidar=$_POST['tiempoCalculadoLiquidar'];
		$valorLiquidar=$_POST['valorLiquidar'];
		$valorFlujoVehiculo=$_POST['valorSinIva'];
		$iva=$_POST['iva'];
		$estadoLiquidar=$_POST['estadoLiquidar'];
		$idConvenio=$_POST['idConvenio'];
		$tipoPago=$_POST['tipoPago'];
		$transacion=$_POST['transacion'];		
		$valorFinal=0;

		$sqlV2=$conn->prepare('SELECT * FROM convenios WHERE idConvenio = :P1');
		$resultadoV2=$sqlV2->execute(array('P1'=>$idConvenio));
		$resultadoV2=$sqlV2->fetchAll();
		$numV2=$sqlV2->rowCount();

		if ($numV2>=1) {
			foreach ($resultadoV2 as $fila) {
				$minutosConvenio=$fila['minutosConvenio'];
				$dineroConvenio=$fila['dineroConvenio'];
			}
		}else{
			$minutosConvenio=0;
			$dineroConvenio=0;
		}

		$sqlV=$conn->prepare('SELECT TIMESTAMPDIFF(SECOND, flujovehiculo.movFechaInicial, now()), tarifas.valorTarifa FROM flujovehiculo INNER JOIN tarifas ON tarifas.idTarifa=flujovehiculo.idTarifa WHERE flujovehiculo.idFlujoVehiculo = :P1');
		$resultadoV=$sqlV->execute(array('P1'=>$idIngresarVehiculo));
		$resultadoV=$sqlV->fetchAll();
		$numV=$sqlV->rowCount();

		if ($numV>=1) {
			foreach ($resultadoV as $fila) {
				$tiempoCalculadoLiquidar=round(($fila['TIMESTAMPDIFF(SECOND, flujovehiculo.movFechaInicial, now())']/60), 2);	
				$valor=$fila['valorTarifa']*($tiempoCalculadoLiquidar-$minutosConvenio);
				$iva=($valor-$dineroConvenio)*$_SESSION["iva"]/100;
				$valorFlujoVehiculo=$valor+$iva;

				if ($valorFlujoVehiculo>50) {
					$valorFlujoVehiculo=ceil($valorFlujoVehiculo/50);
					$valorFlujoVehiculo=$valorFlujoVehiculo*50;
				}else{
					$valorFlujoVehiculo=50;
				}
			}
		
			$sql=$conn->prepare('UPDATE flujovehiculo SET minutosReales = :P1, valorCalculado = :P2, estado = :P6, valorFlujoVehiculo = :P7, ivaFlujoVehiculo = :P8, idUsuarioSalida = :P9, idConvenio = :P10, idTipoPago = :P11, codigoTransacion = :P12, minutosCalculados = :P13 WHERE idFlujoVehiculo = :P5');
			$resultado=$sql->execute(array('P1'=>$tiempoCalculadoLiquidar, 'P2'=>$valor, 'P5'=>$idIngresarVehiculo, 'P6'=>$estadoLiquidar, 'P7'=>$valorFlujoVehiculo, 'P8'=>$iva, 'P9'=>$_SESSION["idUser"], 'P10'=>$idConvenio, 'P11'=>$tipoPago, 'P12'=>$transacion, 'P13'=>$minutosConvenio));
			$num=$sql->rowCount();

			if ($num>=1) {
				$sql2=$conn->prepare('SELECT * FROM movimientoscaja WHERE idCaja = :P1');
				$resultado2=$sql2->execute(array('P1'=>$_SESSION["idCaja"]));
				$resultado2=$sql2->fetchAll();
				$num2=$sql2->rowCount();

				if ($num2>=1) {
					foreach ($resultado2 as $fila) {
						$fila['valorFinal'];
					}
					$valorFinal=$fila['valorFinal']+$valorLiquidar;

					$sql3=$conn->prepare('UPDATE movimientoscaja SET valorFinal = :P1 WHERE idCaja = :P2');
					$resultado3=$sql3->execute(array('P1'=>$valorFinal, 'P2'=>$_SESSION["idCaja"]));
					$num3=$sql3->rowCount();
				}

				$sql4=$conn->prepare('SELECT MAX(nFactura) FROM factura');
				$resultado4=$sql4->execute(array());
				$resultado4=$sql4->fetchAll();
				$num4=$sql4->rowCount();

				if ($num4>=1) {
					foreach ($resultado4 as $fila) {
						$fila['MAX(nFactura)'];
					}
					$nFactura=1+$fila['MAX(nFactura)'];

					$sql5=$conn->prepare('INSERT INTO factura (nFactura, idFlujoVehiculo, idParqueadero, resolucion, tipoCompañia, redondeo) VALUES (:P1, :P2, :P3, :P4, :P5, :P6)');
					$resultado5=$sql5->execute(array('P1'=>$nFactura, 'P2'=>$idIngresarVehiculo, 'P3'=>$_SESSION["idParqueadero"], 'P4'=>$_SESSION["resolucion"], 'P5'=>$_SESSION["tipoCompañia"], 'P6'=>$_SESSION["redondeo"]));
					$num5=$sql5->rowCount();
				}

				echo '
					<div class="alert alert-success alert-dismissable">
						<button type="button" class="close" data-dismiss="alert">&times;</button>
						El Vehículo <strong>'.$placaVehiculoLiquidar.'</strong> Fue Liquidado Con Éxito.
					</div>
				';
			}else{
				echo '
					<div class="alert alert-danger alert-dismissable">
		  				<button type="button" class="close" data-dismiss="alert">&times;</button>
		  				El Vehículo <strong>'.$placaVehiculoLiquidar.'</strong> NO Fue liquidado.
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