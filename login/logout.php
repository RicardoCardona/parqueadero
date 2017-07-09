<?php
	session_start();
	try{
		require "../modulos/conexion.php";

		$descripcionSalida=$_POST['descripcionSalida'];

		$sql=$conn->prepare('UPDATE movimientoscaja SET fechaCierre = now(), incidencias = :P3 WHERE idCaja = :P2');
		$resultado=$sql->execute(array('P2'=>$_SESSION["idCaja"], 'P3'=>$descripcionSalida));
		$num=$sql->rowCount();

		if ($num>=1) {
			session_destroy();
		}else{
			session_destroy();
		}

		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>