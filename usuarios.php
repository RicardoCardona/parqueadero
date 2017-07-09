<?php
	// conexión a la base de datos mediante msqli 
	$conexion= new mysqli('localhost','root','','2park');

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
	$urlUsuarios= 'http://2park.co/serviceApp/consultarUsuarios.php?parqueadero='.$parqueadero;
	$jsonUsuarios = file_get_contents($urlUsuarios);
	// Convierte los datos que vienen en formato JSON y los deja en Array 
	$arrayUsuarios = json_decode($jsonUsuarios, true);
	$cantArray = count($arrayUsuarios);
	// var_dump($arrayUsuarios);


 	for ($i=0; $i < $cantArray ; $i++) { 
 		$codigo= $arrayUsuarios[$i]["Codigo_sesion"];
 		$usuario= $arrayUsuarios[$i]["Usuario"];
 		$contrasena= $arrayUsuarios[$i]["Contrasena"];

 		$sql1= 'DELETE FROM usuarios WHERE idUser ='.$codigo;
 		if ($conexion->query($sql1))
 		{
 	 		$sql= "INSERT INTO usuarios (idUser,fullname,username,password,idRol) VALUES ('$codigo','Admin','$usuario','$contrasena','1')";
 	 		if ($conexion->query($sql)) {
 	 			echo "Se ingreso Correctamente";
 	 		}else{
 	 			echo "no se ingreso";
 	 		}
  		}else{
  			$sql2= "INSERT INTO usuarios (idUser,fullname,username,password,idRol) VALUES ('$codigo','Admin','$usuario','$contrasena','1')";
 	 		if ($conexion->query($sql2)) {
 	 			echo "Se ingreso Correctamente";
 	 		}else{
 	 			echo "no se ingreso";
 	 		}
  		}
  	}
?>