<?php
	try{
		date_default_timezone_set('America/Bogota');
		require "conexion.php";
		session_start();

		$codigoMensaje=$_POST['codigoMensaje'];
		
		$sql=$conn->prepare('SELECT usuariosmensaje.idUsuariosMensaje, usuariosmensaje.estado, mensajes.idMensaje, mensajes.fechaMensaje, mensajes.remitenteMensaje, mensajes.tituloMensaje, mensajes.contenidoMensaje FROM usuariosmensaje INNER JOIN mensajes ON mensajes.idMensaje = usuariosmensaje.idMensaje WHERE usuariosmensaje.idUser = :P1 AND usuariosmensaje.idMensaje = :P2');
		$resultado=$sql->execute(array('P1'=>$_SESSION["idUser"], 'P2'=>$codigoMensaje));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			echo '
				<div class="panel panel-default">
					<div class="panel-body">
			';
			foreach ($resultado as $fila) {
				echo '
					<p>De: '.$fila['remitenteMensaje'].'</p>
					<h4>Asunto: </strong>'.$fila['tituloMensaje'].'</strong></h4><br><br>					
					<p>'.$fila['contenidoMensaje'].'</p><br><br>
					<h5>'.$fila['fechaMensaje'].'</h5>
				';
			}
			echo '
					</div>
				</div>
			';

			$sql2=$conn->prepare('UPDATE usuariosmensaje SET estado = "inactivo", fechaHoraLeido = NOW() WHERE idMensaje = :P1 AND idUser = :P2 ');
			$resultado2=$sql2->execute(array('P1'=>$codigoMensaje, 'P2'=>$_SESSION["idUser"]));
			$num2=$sql2->rowCount();
			
		}else{
			echo 'NO HAS RECIBIDO NINGÚN MENSAJE TODAVÍA';
		}

		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>