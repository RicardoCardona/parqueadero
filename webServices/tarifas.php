<?php
	// conexión a la base de datos mediante msqli 
	require "../modulos/conexion2.php";

	// Consulta en la tabla configuración para identificar cual es el parqueadero que se va a enviar como parámetro 
		$sql= "SELECT * FROM configuracion";
		$resultado=$conexion->query($sql);
		$num= $resultado->num_rows;

		if ($num>=1) {
			foreach ($resultado as $row) {
				// Variable con el codigo del parqueadero
				$parqueadero=$row["idParqueadero"];
			}
		}

	// Url del web service al cual se le esta mandando el parámetro del parqueadero 
	$urlUsuarios= "http://2park.co/serviceApp/consultarTarifa.php?estado=sin%20enviar&parqueadero='$parqueadero'";
	$jsonUsuarios = file_get_contents($urlUsuarios);
	// Convierte los datos que vienen en formato JSON y los deja en Array 
	$arrayUsuarios = json_decode($jsonUsuarios, true);
	$cantArray = count($arrayUsuarios);
	// var_dump($arrayUsuarios);


 	for ($i=0; $i < $cantArray ; $i++) { 
 		echo $idTarifa= $arrayUsuarios[$i]["idTarifa"];
 		echo $nombre= $arrayUsuarios[$i]["nombre"]; 		
 		echo $idModalidad= $arrayUsuarios[$i]["idModalidad"];
 		echo $idTipoVehiculo= $arrayUsuarios[$i]["idTipoVehiculo"];
 		echo $tiempoLimite= $arrayUsuarios[$i]["tiempoLimite"];
 		echo $valorTarifa= $arrayUsuarios[$i]["valorTarifa"];
 		echo $createDate= $arrayUsuarios[$i]["createDate"];

 		try{
			require "../modulos/conexion.php";

			$sql=$conn->prepare('SELECT * FROM tarifas WHERE idTarifa = :P1');
			$resultado=$sql->execute(array('P1'=>$idTarifa));
			$resultado=$sql->fetchAll();
			$num=$sql->rowCount();

			if ($num>=1) {
				$sql3=$conn->prepare('UPDATE tarifas SET nombre = :P1, idModalidad = :P2, idTipoVehiculo = :P3, tiempoLimite = :P4, valorTarifa = :P5, createDate = :P6 WHERE idTarifa = :P7');
				$resultado3=$sql3->execute(array('P1'=>$nombre, 'P2'=>$idModalidad, 'P3'=>$idTipoVehiculo, 'P4'=>$tiempoLimite, 'P5'=>$valorTarifa, 'P6'=>$createDate, 'P7'=>$idTarifa));
				$num3=$sql3->rowCount();

				if ($num3>=1) {
					echo "Se edito Correctamente";
				}else{
					echo "Error Inesperado";
				}
			}else{			
				$sql2=$conn->prepare('INSERT INTO tarifas (idTarifa, nombre, idModalidad, idTipoVehiculo, tiempoLimite, valorTarifa, createDate) VALUES (:P1, :P2, :P3, :P4, :P5, :P6, :P7)');
				$resultado2=$sql2->execute(array('P1'=>$idTarifa, 'P2'=>$nombre,'P3'=>$idModalidad, 'P4'=>$idTipoVehiculo, 'P5'=>$tiempoLimite, 'P6'=>$valorTarifa, 'P7'=>$createDate));
				$num2=$sql2->rowCount();

				if ($num2>=1) {
					echo "Se ingreso Correctamente";
				}else{
					echo "no se ingreso ";
				}
			}
				$conn=NULL; //Cerrar la Conexión
		}catch(PDOException $e){
			echo "ERROR: ".$e->getMessage();
			exit();
		}
  	}
?>