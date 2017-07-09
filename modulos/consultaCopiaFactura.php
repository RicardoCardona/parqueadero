<?php
	try{
		session_start();
		require "conexion.php";

		$parametro=$_POST['parametro'];

		$sql=$conn->prepare('SELECT factura.idFactura, flujovehiculo.idFlujoVehiculo, vehiculos.placa, flujovehiculo.movFechaInicial, flujovehiculo.movFechaFinal, flujovehiculo.minutosReales, flujovehiculo.valorFlujoVehiculo FROM factura INNER JOIN flujovehiculo ON flujovehiculo.idFlujoVehiculo=factura.idFlujoVehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo WHERE vehiculos.placa LIKE :P1"%" OR factura.nFactura LIKE :P1"%"');
		$resultado=$sql->execute(array('P1'=>$parametro));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			echo '
				<table class="table table-hover">
					<tr>
						<th>Placa</th>
						<th>Fecha Inicio</th>
						<th>Fecha Fin</th>
						<th>Tiempo Calculado</th>
						<th>Valor Calculado</th>
						<th>Accion</th>
					</tr>
			';
			foreach ($resultado as $fila) {
				echo '
					<tr>
						<input type="hidden" class="idFactura" value="'.$fila['idFactura'].'">
						<td class="placa">'.$fila['placa'].'</td>
						<td>'.$fila['movFechaInicial'].'</td>
						<td>'.$fila['movFechaFinal'].'</td>
						<td>'.$fila['minutosReales'].'</td>
						<td>$'.number_format($fila['valorFlujoVehiculo']).'</td>
						<td>
							<button type="button" class="impresionCopiaFactura btn btn-success" data-toggle="modal" data-target=".bs-example-modal-sm2">Ver Factura</button>
						</td>
					</tr>
				';
			}
			echo '
				</table>
			';
		}else{
			echo '<div class="col-md-12"><center><h3>No Existe Ningún Vehiculo</h3></center></div>';
		}
		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>