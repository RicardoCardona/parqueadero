<?php
	try{
		session_start();
		require "conexion.php";

		$descuadre=$_POST['descuadre'];
		$observaciones=$_POST['observaciones'];

		$sql=$conn->prepare('SELECT MAX(idArqueo) FROM arqueo WHERE idUser = :P1');
		$resultado=$sql->execute(array('P1'=>$_SESSION["idUser"]));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			foreach ($resultado as $fila) {
				$fila['MAX(idArqueo)'];
			}
			$sql2=$conn->prepare('UPDATE arqueo SET descuadre = :P1, observaciones = :P2 WHERE idArqueo = :P3');
			$resultado2=$sql2->execute(array('P1'=>$descuadre, 'P2'=>$observaciones, 'P3'=>$fila['MAX(idArqueo)']));
			$num2=$sql2->rowCount();

			if ($num2>=1) {
				echo "<center><h3>Arqueo Realizado Satisfactoriamente</h3></center>";
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