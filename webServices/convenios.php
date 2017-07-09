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
	$urlUsuarios= "http://2park.co/serviceApp/consultarConvenio.php?estado=sin%20enviar&parqueadero='$parqueadero'";
	$jsonUsuarios = file_get_contents($urlUsuarios);
	// Convierte los datos que vienen en formato JSON y los deja en Array 
	$arrayUsuarios = json_decode($jsonUsuarios, true);
	$cantArray = count($arrayUsuarios);
	// var_dump($arrayUsuarios);


 	for ($i=0; $i < $cantArray ; $i++) { 
 		echo $idConvenio= $arrayUsuarios[$i]["idConvenio"];
 		echo $nombre= $arrayUsuarios[$i]["nombre"]; 		
 		echo $fechaInicio= $arrayUsuarios[$i]["fechaInicio"];
 		echo $fechaFin= $arrayUsuarios[$i]["fechaFin"];
 		echo $minutosConvenio= $arrayUsuarios[$i]["minutosConvenio"];
 		echo $createDate= $arrayUsuarios[$i]["createDate"];
 		echo $idTarifa= $arrayUsuarios[$i]["idTarifa"];
 		echo $dineroConvenio= $arrayUsuarios[$i]["dineroConvenio"];

 		try{
			require "../modulos/conexion.php";

			$sql=$conn->prepare('SELECT * FROM convenios WHERE idConvenio = :P1');
			$resultado=$sql->execute(array('P1'=>$idConvenio));
			$resultado=$sql->fetchAll();
			$num=$sql->rowCount();

			if ($num>=1) {
				$sql3=$conn->prepare('UPDATE convenios SET nombre = :P1, fechaInicio = :P2, fechaFin = :P3, idTarifa = :P4, minutosConvenio = :P5, dineroConvenio = :P6, createDate = :P7 WHERE idConvenio = :P8');
				$resultado3=$sql3->execute(array('P1'=>$nombre, 'P2'=>$fechaInicio, 'P3'=>$fechaFin, 'P4'=>$idTarifa, 'P5'=>$minutosConvenio, 'P6'=>$dineroConvenio, 'P7'=>$createDate, 'P8'=>$idConvenio));
				$num3=$sql3->rowCount();

				if ($num3>=1) {
					echo "Se edito Correctamente";
				}else{
					echo "Error Inesperado";
				}
			}else{
				$sql2=$conn->prepare('INSERT INTO convenios (idConvenio, nombre, fechaInicio, fechaFin, idTarifa, minutosConvenio, dineroConvenio, createDate) VALUES (:P1, :P2, :P3, :P4, :P5, :P6, :P7, :P8)');
				$resultado2=$sql2->execute(array('P1'=>$idConvenio, 'P2'=>$nombre,'P3'=>$fechaInicio, 'P4'=>$fechaFin, 'P5'=>$idTarifa, 'P6'=>$minutosConvenio, 'P7'=>$dineroConvenio, 'P8'=>$createDate));
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