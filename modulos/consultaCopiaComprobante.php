<?php
	try{
		session_start();
		require "conexion.php";

		$parametro2=$_POST['parametro2'];

		$sql=$conn->prepare('SELECT *, vehiculos.placa FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo WHERE vehiculos.placa LIKE :P1"%" AND estado = "activo"');
		$resultado=$sql->execute(array('P1'=>$parametro2));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			echo '
				<table class="table table-hover">
					<tr>
						<th>Placa</th>
						<th>Fecha Inicio</th>
						<th>Accion</th>
					</tr>
			';
			foreach ($resultado as $fila) {
				echo '
					<tr>
						<input type="hidden" class="placa" value="'.$fila['placa'].'">
						<td>'.$fila['placa'].'</td>
						<td class="movFechaInicial">'.$fila['movFechaInicial'].'</td>
						<td>
							<button type="button" class="impresionCopiaComprobante btn btn-success" data-toggle="modal" data-target=".bs-example-modal-sm">Ver Comprobante</button>
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