<?php
	try{
		date_default_timezone_set('America/Bogota');
		require "conexion.php";
		if(!isset($_SESSION)){
			session_start();
		}
		
		$sql=$conn->prepare('SELECT usuariosmensaje.idUsuariosMensaje, usuariosmensaje.estado, mensajes.idMensaje, mensajes.fechaMensaje, mensajes.remitenteMensaje, mensajes.tituloMensaje, mensajes.contenidoMensaje FROM usuariosmensaje INNER JOIN mensajes ON mensajes.idMensaje = usuariosmensaje.idMensaje WHERE usuariosmensaje.idUser = :P1 ORDER BY mensajes.fechaMensaje DESC');
		$resultado=$sql->execute(array('P1'=>$_SESSION['idUser']));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			echo '
					<table class="table table-hover">
						<tr>
							<th>Remitente</th>
							<th>Asunto</th>
							<th>Fecha y Hora</th>
						</tr>
			';

			foreach ($resultado as $fila) {
				echo '	<tbody class="'.$fila['estado'].'">
							<tr data-mensaje="'.$fila['idMensaje'].'" class="cuerpotabla">
								<td>'.$fila['remitenteMensaje'].'</td>
								<td>'.$fila['tituloMensaje'].'</td>
								<td>'.$fila['fechaMensaje'].'</td>
							</tr>
						</tbody>
				';
			}
			echo '
					</table>
			';
		}else{
			echo 'NO HAS RECIBIDO NINGÚN MENSAJE TODAVÍA';
		}

		$conn=NULL; //Cerrar la Conexión

	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>