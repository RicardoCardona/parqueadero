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
	$urlMensajes= "http://2park.co/serviceApp/consultarMensaje.php?estado=sin%20enviar&parqueadero='$parqueadero'";
	$jsonMensajes = file_get_contents($urlMensajes);
	// Convierte los datos que vienen en formato JSON y los deja en Array 
	$arrayMensajes = json_decode($jsonMensajes, true);
	$cantArray = count($arrayMensajes);
	// var_dump($arrayUsuarios);


	for ($i=0; $i<$cantArray; $i++) { 
		echo $titulo= $arrayMensajes[$i]["titulo"];
		echo $mensaje= $arrayMensajes[$i]["mensaje"];
		echo $Codigo_sesion= $arrayMensajes[$i]["Codigo_sesion"];
		echo $usuario= $arrayMensajes[$i]["usuario"];
		echo $fechaHoraMensaje= $arrayMensajes[$i]["FechaHoraMensaje"];

		try{
			require "../modulos/conexion.php";

			if (!empty($titulo)) {
				$sql2=$conn->prepare('INSERT INTO mensajes (idRemitente, remitenteMensaje, tituloMensaje, contenidoMensaje, fechaMensaje) VALUES (:P1, :P2, :P3, :P4, :P5)');
				$resultado2=$sql2->execute(array('P1'=>$Codigo_sesion,'P2'=>$usuario, 'P3'=>$titulo,'P4'=>$mensaje, 'P5'=>$fechaHoraMensaje));
				$num2=$sql2->rowCount();

				if ($num2>=1) {
					$estado="activo";

					$sql3=$conn->prepare('SELECT MAX(idMensaje) FROM mensajes');
					$resultado3=$sql3->execute(array());
					$resultado3=$sql3->fetchAll();
					$num3=$sql3->rowCount();

					if ($num3>=1) {
						foreach ($resultado3 as $fila) {
							$idMensaje=$fila['MAX(idMensaje)'];
						}
						$sql4=$conn->prepare('SELECT * FROM usuarios');
						$resultado4=$sql4->execute(array());
						$resultado4=$sql4->fetchAll();
						$num4=$sql4->rowCount();

						if ($num4>=1) {
							foreach ($resultado4 as $fila) {
								$sql5=$conn->prepare('INSERT INTO usuariosmensaje (idMensaje, idUser, estado) VALUES (:P1, :P2, :P3)');
								$resultado5=$sql5->execute(array('P1'=>$idMensaje, 'P2'=>$fila['idUser'], 'P3'=>$estado));
								$num5=$sql5->rowCount();
							}
							if ($num5>=1) {
								echo "si";
							}else{
								echo "Error Usuario Mensaje";
							}
						}else{
							echo "No existen Usuarios";
						}
					}else{
						echo "No existen Mensajes";
					}
				}else{
					echo "Error Al Insertar Mensaje";
				}
			}
			$conn=NULL; //Cerrar la Conexión
		}catch(PDOException $e){
			echo "ERROR: ".$e->getMessage();
			exit();
		}
	}
?>