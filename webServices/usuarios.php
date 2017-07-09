<?php
	$conexion= new mysqli('bynary01.com','bynary','NL-PNel2T)4C','bynary_2parkPrueba')or die ("Error en la conexion a la base de datos");
	if ($conexion->connect_errno){
		echo "Error en el conexion de la base de datos ".$conexion=connect_error;
		exit();
	} 

	if (isset($_GET['parqueadero']))
	{
		$parqueadero = $_GET['parqueadero'];

		$urlUsuarios= 'http://2park.co/serviceApp/consultarUsuarios.php?parqueadero= 2';
		$jsonUsuarios = file_get_contents($urlUsuarios);
		$arrayUsuarios = json_decode($jsonUsuarios, true);
		$cantArray = count($arrayUsuarios);
		var_dump($arrayUsuarios);

 		for ($i=0; $i < $cantArray ; $i++) { 
 			$usuario= $arrayUsuarios[$i]["Usuario"];
 			$contrasena= $arrayUsuarios[$i]["Contrasena"];
 			
 			$sql="INSERT INTO user(id,email,password) VALUES ('',$usuario','$contrasena')";
 			if ($conexion->query($sql))
 			{
 				//echo "Datos ingresados exitosamente";
			}else{
				//echo "Usuarios ya registrados";	
	    	}	
 		}
 			
	}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<title>Usuarios 2Park</title>
</head>
<body>
	<form action="" method="GET">
		<label for="parqueadero">ingrese el Parqueadero</label>
		<input type="text" name="parqueadero">
		<button type="submit">Consultar</button>
	</form>
</body>
</html>