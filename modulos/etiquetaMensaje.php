<?php
	try{
		session_start();
		
		$_SESSION["idUser"];
		
		require "conexion.php";
		
		$sql=$conn->prepare('SELECT * FROM usuariosmensaje WHERE estado = "activo" AND idUser = :P1');
		$resultado=$sql->execute(array('P1'=>$_SESSION["idUser"]));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			echo '<label class="alerta" for="tab6">Mensajes ('.$num.')</label>';		
		}else{
			echo '<label class="tabs" for="tab6">Mensajes</label>';
		}

		$conn=NULL; //Cerrar la Conexión

	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>