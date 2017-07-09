<?php
	if(!empty($_POST)){
		if(isset($_POST["username"]) &&isset($_POST["password"])){
			if($_POST["username"]!=""&&$_POST["password"]!=""){
				include "conexion.php";
				$psw= base64_encode($_POST["password"]);
				
				$user_id=null;
				$sql1= "select * from usuarios where (username=\"$_POST[username]\") and password=\"$psw\" ";
				$query = $con->query($sql1);
				while ($r=$query->fetch_array()) {
					$user_id=$r["fullname"];
					$codigo=$r["idUser"];
					break;
				}
				if($user_id==null){
					print "<script>alert(\"Acceso invalido.\");window.location='../index.php';</script>";
				}else{
					session_start();
					$_SESSION["fullname"]=$user_id;
					$_SESSION["idUser"]=$codigo;
					try{
						require "../modulos/conexion.php";

						$sql3=$conn->prepare('SELECT * FROM configuracion');
						$resultado3=$sql3->execute(array());
						$resultado3=$sql3->fetchAll();
						$num3=$sql3->rowCount();

						if ($num3>=1) {
							foreach ($resultado3 as $fila) {
								$_SESSION["idParqueadero"]=$fila['idParqueadero'];
								$_SESSION["baseInicial"]=$fila['baseInicial'];
								$_SESSION["nombreParqueadero"]=$fila['nombreParqueadero'];
								$_SESSION["iva"]=$fila['iva'];
								$_SESSION["resolucion"]=$fila['resolucion'];
								$_SESSION["tipoCompañia"]=$fila['tipoCompañia'];
								$_SESSION["redondeo"]=$fila['redondeo'];								
								$_SESSION["ofertasAvisos"]=$fila['ofertasAvisos'];
							}
						}

						$sql2=$conn->prepare('SELECT * FROM movimientoscaja');
						$resultado2=$sql2->execute(array());
						$resultado2=$sql2->fetchAll();
						$num2=$sql2->rowCount();

						if ($num2>=1) {
							foreach ($resultado2 as $fila) {
								$fila['valorFinal'];
							}
							$sql=$conn->prepare('INSERT INTO movimientoscaja (base, idUser, valorFinal) VALUES (:P1, :P2, :P1)');
							$resultado=$sql->execute(array('P1'=>$fila['valorFinal'], 'P2'=>$_SESSION["idUser"]));
							$num=$sql->rowCount();

							if ($num>=1) {
								print "<script>window.location='../home.php';</script>";
							}

						}else{
							$sql=$conn->prepare('INSERT INTO movimientoscaja (base, idUser, valorFinal) VALUES (:P1, :P2, :P1)');
							$resultado=$sql->execute(array('P1'=>$_SESSION["baseInicial"], 'P2'=>$_SESSION["idUser"]));
							$num=$sql->rowCount();

							if ($num>=1) {
								print "<script>window.location='../home.php';</script>";
							}
						}
						$sqlV=$conn->prepare('SELECT MAX(idCaja) FROM movimientoscaja WHERE idUser = :P1');
						$resultadoV=$sqlV->execute(array('P1'=>$_SESSION["idUser"]));
						$resultadoV=$sqlV->fetchAll();
						$numV=$sqlV->rowCount();

						if ($numV>=1) {
							foreach ($resultadoV as $fila) {
								$_SESSION["idCaja"]=$fila['MAX(idCaja)'];
							}
						}else{
							echo "Error Inesperado";
						}
						$conn=NULL; //Cerrar la Conexión
					}catch(PDOException $e){
						echo "ERROR: ".$e->getMessage();
						exit();
					}									
				}
			}
		}
	}
?> 