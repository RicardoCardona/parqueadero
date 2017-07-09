<?php
	try{
		session_start();
		require "conexion.php";

		$placaVehiculo=$_POST['placaVehiculo'];

		$sql=$conn->prepare('SELECT flujovehiculo.movFechaInicial, vehiculos.placa FROM flujovehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo WHERE vehiculos.placa = :P1 AND flujovehiculo.estado = "activo"');
		$resultado=$sql->execute(array('P1'=>$placaVehiculo));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			foreach ($resultado as $fila) {
				echo '	<article>
							<center>
								<strong><h3>'.$_SESSION["nombreParqueadero"].'</h3></strong>
								<h5>'.$fila['movFechaInicial'].'</h5>
								<img id="codigoBarras"/>
							</center>
						</article>					
					';
			}
		}else{
			echo '';
		}

		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>