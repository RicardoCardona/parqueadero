<?php
	try{
		date_default_timezone_set('America/Bogota');
		require "conexion.php";

		$historial=date('Y-m-d');

		$sql=$conn->prepare('SELECT tarifas.nombre, tipovehiculo.descripcion, tarifas.tiempoLimite, tarifas.valorTarifa, tarifas.createDate FROM tarifas INNER JOIN tipovehiculo ON tipovehiculo.idTipoVehiculo=tarifas.idTipoVehiculo');
		$resultado=$sql->execute(array('P1'=>$historial));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		//operación de caluclo
		if ($num>=1) {
			echo '
					<table class="table table-hover">
						<tr>
							<th>Tipo de Tarifa</th>
							<th>Vehículo</th>
							<th>Tiempo Límite</th>
							<th>Valor Tarifa</th>
							<th>Fecha Creación</th>
						</tr>
			';
			foreach ($resultado as $fila) {
				echo '
						<tr>
							<td>'.$fila['nombre'].'</td>
							<td>'.$fila['descripcion'].'</td>
							<td>'.number_format($fila['tiempoLimite']).' Minutos</td>
							<td>$'.number_format($fila['valorTarifa']).'</td>
							<td>'.$fila['createDate'].'</td>							
						</tr>
				';
			}
			echo '
					</table>
				';
		}

		$conn=NULL; //Cerrar la Conexión

	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>