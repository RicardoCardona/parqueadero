<?php
	try{
		session_start();
		require "conexion.php";

		$historial=$_POST['historial'];

		$sql=$conn->prepare('SELECT vehiculos.placa, flujovehiculo.movFechaInicial, flujovehiculo.movFechaFinal, flujovehiculo.minutosReales, flujovehiculo.valorCalculado, flujovehiculo.valorFlujoVehiculo FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo WHERE vehiculos.placa LIKE :P1"%" OR flujovehiculo.movFechaInicial LIKE :P1"%" OR flujovehiculo.movFechaFinal LIKE :P1"%" OR flujovehiculo.minutosCalculados LIKE :P1"%" OR flujovehiculo.valorCalculado LIKE :P1"%"');
		$resultado=$sql->execute(array('P1'=>$historial));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		//operación de caluclo
		if ($num>=1) {
			echo '
					<table class="table table-hover">
						<tr>
							<th>Placa</th>
							<th>Fecha Inicio</th>
							<th>Fecha Fin</th>
							<th>Tiempo Calculado</th>
							<th>Valor Calculado</th>
						</tr>
			';
			foreach ($resultado as $fila) {				
				echo '
						<tr>
							<td>'.$fila['placa'].'</td>
							<td>'.$fila['movFechaInicial'].'</td>
							<td>'.$fila['movFechaFinal'].'</td>
							<td>'.$fila['minutosReales'].'</td>
							<td>$'.number_format($fila['valorFlujoVehiculo']).'</td>
						</tr>
				';
			}
			echo '
					</table>
				';
		}else{
			echo '<div class="col-md-12"><center><h3>No se ha Registrado Ningún Vehiculo</h3></center></div>';
		}

		$conn=NULL; //Cerrar la Conexión

	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>