<?php
	try{
		session_start();
		require "conexion.php";

		$fechaInicio=$_POST['fechaInicio'];
		$fechaFin=$_POST['fechaFin'];
		$valorTotal=0;

		$sql=$conn->prepare('SELECT *, vehiculos.placa, tipoPago.tipoPago FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo INNER JOIN tipoPago ON tipoPago.idTipoPago=flujovehiculo.idTipoPago WHERE movFechaInicial BETWEEN :P1 AND :P2 AND estado = "inactivo"');
		$resultado=$sql->execute(array('P1'=>$fechaInicio, 'P2'=>$fechaFin));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			echo '
				<div id="arqueoImprimir">
					<table class="table table-hover">
						<tr>
							<th>Placa</th>
							<th>Fecha Inicio</th>
							<th>Fecha Fin</th>
							<th>Tiempo Calculado</th>							
							<th>Tipo De Pago</th>
							<th>Codigo de Transación</th>
							<th>Valor Calculado</th>
						</tr>
			';
			foreach ($resultado as $fila) {
				echo '
						<tr>
							<td>'.$fila['placa'].'</td>
							<td>'.$fila['movFechaInicial'].'</td>
							<td>'.$fila['movFechaFinal'].'</td>
							<td>'.$fila['minutosReales'].' Minutos</td>							
							<td>'.$fila['tipoPago'].'</td>
							<td>'.$fila['codigoTransacion'].'</td>
							<td>'.number_format($fila['valorFlujoVehiculo']).'</td>
						</tr>
				';
				$valorTotal=$fila['valorFlujoVehiculo']+$valorTotal;
			}

			$sql2=$conn->prepare('SELECT * FROM movimientoscaja WHERE fechaApertura BETWEEN :P1 AND :P2');
			$resultado2=$sql2->execute(array('P1'=>$fechaInicio, 'P2'=>$fechaFin));
			$resultado2=$sql2->fetchAll();
			$num2=$sql2->rowCount();

			if ($num2>=1) {
				foreach ($resultado2 as $fila) {
					$base=$fila['base'];
				}
			}
			
			$totalBase=$valorTotal+$fila['base'];
			echo '
					</table>
					<h4 class="col-sm-offset-10">Base: $ '.number_format($fila['base']).'</h4>
					<h4 class="col-sm-offset-10">Total: $ '.number_format($totalBase).'</h4>
				</div>
				';
		}else{
			echo 'NO EXISTE ARQUEO';
		}

		$sql4=$conn->prepare('INSERT INTO arqueo (fechaInicial, fechaFinal, saldoInicial, saldoFinal, idUser) VALUES (:P1, :P2, :P3, :P4, :P5)');
		$resultado4=$sql4->execute(array('P1'=>$fechaInicio, 'P2'=>$fechaFin, 'P3'=>$base, 'P4'=>$totalBase, 'P5'=>$_SESSION['idUser']));
		$num4=$sql4->rowCount();

		if ($num4>=1) {
			echo '
				<div class="form-group col-md-3">
					<h4><label for="descuadre">Ingresar Descuadre *</label></h4>
					<input type="number" class="input form-control" id="descuadre" value="0" autocomplete="off">
				</div>
				<div class="form-group col-md-8">
					<h4><label for="observaciones">Ingrese Observaciones *</label></h4>
					<input type="text" class="input form-control" id="observaciones" placeholder="observaciones" autocomplete="off">
				</div>
				<br><br>
				<div>
					<button type="button" class="editarArqueo btn btn-success btn-lg">Ingresar</button>
				</div>
			';
		}else{
			echo 'NO EXISTE ARQUEO';
		}

		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>