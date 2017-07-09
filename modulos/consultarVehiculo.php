<?php
	try{
		date_default_timezone_set('America/Bogota');
		session_start();
		require "conexion.php";

		$placa=$_POST['placa'];
		$fechaHoy=date('Y-m-d');

		$sql=$conn->prepare('SELECT TIMESTAMPDIFF(SECOND, flujovehiculo.movFechaInicial, now()), flujovehiculo.idFlujoVehiculo, vehiculos.idVehiculo, tarifas.valorTarifa FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo INNER JOIN tarifas ON tarifas.idTarifa=flujovehiculo.idTarifa WHERE vehiculos.placa = :P1 AND flujovehiculo.estado = "activo"');
		$resultado=$sql->execute(array('P1'=>$placa));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		//operación de caluclo
		if ($num>=1) {
			$sql5=$conn->prepare('SELECT * FROM convenios WHERE fechaFin >= :P1');
			$resultado5=$sql5->execute(array('P1'=>$fechaHoy));
			$resultado5=$sql5->fetchAll();
			$num5=$sql5->rowCount();

			if ($num5>=1) {
				echo '
					<div class="form-group col-md-12">
						<h4><label for="convenio">Convenios</label></h4>
						<select class="input form-control" id="convenio">
							<option value="0">- Seleccione -</option>
				';
				foreach ($resultado5 as $fila) {
					echo '
							<option value="'.$fila['idConvenio'].'">'.$fila['nombre'].'</option>
					';
				}
				echo '	</select>							
					</div>
				';
			}
			foreach ($resultado as $fila) {
				$tiempoCalculadoLiquidar=round(($fila['TIMESTAMPDIFF(SECOND, flujovehiculo.movFechaInicial, now())']/60), 2);
				$valor=$fila['valorTarifa']*$tiempoCalculadoLiquidar;
				$iva=$valor*$_SESSION["iva"]/100;
				$valorFlujoVehiculo=$valor+$iva;

				if ($valorFlujoVehiculo>50) {
					$valorFlujoVehiculo=ceil($valorFlujoVehiculo/50);
					$valorFlujoVehiculo=$valorFlujoVehiculo*50;
				}else{
					$valorFlujoVehiculo=50;
				}

				echo '
					<div id="espacioLiquidar">				
						<input type="hidden" class="valorSinIva" value="'.$valor.'">
						<input type="hidden" class="iva" value="'.$iva.'">
						<div class="form-group col-md-6">
							<h4><label for="tiempoCalculado">Tiempo</label></h4>
							<input type="text" data-id="'.$fila['idFlujoVehiculo'].'" class="input form-control" id="tiempoCalculado" value="'.$tiempoCalculadoLiquidar.'" autocomplete="off" readonly>
						</div>
						<div class="form-group col-md-6">
							<h4><label for="valor">Valor (Incluye IVA)</label></h4>
							<input type="text" class="input form-control" id="valor" value="'.$valorFlujoVehiculo.'" autocomplete="off" readonly>
						</div>
						<div class="form-group col-md-12">
							<h4><label for="tipoPago">Tipo de Pago</label></h4>
							<select class="input form-control" id="tipoPago">
								<option value="1">Efectivo</option>
								<option value="2">Tarjeta Débito</option>
								<option value="3">Tarjeta Crédito</option>
							</select>
						</div>
						<div id="pagos">
							<div class="form-group col-md-6">
								<h4><label for="dineroRecibido">Dinero Recibido *</label></h4>
								<input type="text" class="input form-control" id="dineroRecibido" placeholder="Dinero Recibido" autocomplete="off">
							</div>
							<div class="form-group col-md-6">
								<h4><label for="cambioDinero">Cambio</label></h4>
								<input type="text" class="input form-control" id="cambioDinero" placeholder="Cambio" autocomplete="off" readonly>
							</div>						
							<div class="text-center">
								<button type="button" class="cancelar btn btn-danger btn-lg">Cancelar</button>
								<button type="button" class="liquidar btn btn-success btn-lg">Liquidar</button>
								<button type="button" id="factura" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".bs-example-modal-sm2">Ver Factura</button>
							</div>
						</div>
					</div>
				';
			}
			$sql2=$conn->prepare('UPDATE flujovehiculo SET movFechaFinal = NOW(), minutosReales = :P2, valorFlujoVehiculo = :P3 WHERE idFlujoVehiculo = :P1 AND estado = "activo"');
			$resultado2=$sql2->execute(array('P1'=>$fila['idFlujoVehiculo'], 'P2'=>$tiempoCalculadoLiquidar, 'P3'=>$valorFlujoVehiculo));
			$num2=$sql2->rowCount();
		}else{
			$sql3=$conn->prepare('SELECT * FROM tipovehiculo');
			$resultado3=$sql3->execute(array());
			$resultado3=$sql3->fetchAll();
			$num3=$sql3->rowCount();

			if ($num3>=1) {
				echo '
						<div class="form-group col-md-6">
							<h4><label for="tipoVehículo">Tipo de Vehículo *</label></h4>
							<select class="input form-control" name="tipoVehiculo" id="tipoVehiculo">';
				foreach ($resultado3 as $fila) {
					echo '		<option value="'.$fila['idTipoVehiculo'].'">'.$fila['descripcion'].'</option>';
				}
				echo '		</select>
						</div>';
			}

			$sql4=$conn->prepare('SELECT * FROM modalidades');
			$resultado4=$sql4->execute(array());
			$resultado4=$sql4->fetchAll();
			$num4=$sql4->rowCount();

			if ($num4>=1) {
				echo '
						<div class="form-group col-md-6">
							<h4><label for="tipoVehículo">Modalidad *</label></h4>
							<select class="input form-control" name="modalidad" id="modalidad">';
				foreach ($resultado4 as $fila) {
					echo '		<option value="'.$fila['idModalidad'].'">'.$fila['nombre'].'</option>';
				}
				echo '		</select>
						</div>';
			}
			
			echo '
					<div class="form-group col-md-12">
						<h4><label for="descripcion">Descripción </label></h4>
						<textarea id="descripcion" class="input form-control" placeholder="Ingrese Descripción"></textarea>
					</div>
					<div class="text-center">
						<button type="button" class="cancelar btn btn-danger btn-lg">Cancelar</button>						
						<button type="button" class="ingresarVehiculo btn btn-success btn-lg">Aceptar</button>
						<button type="button" id="comprobante" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".bs-example-modal-sm">Ver Comprobante</button>
					</div>
				';
		}

		$conn=NULL; //Cerrar la Conexión

	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>