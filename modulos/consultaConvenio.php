<?php
	try{
		session_start();
		require "conexion.php";

		$idConvenio=$_POST['convenio'];
		$placaVehiculo=$_POST['placaVehiculo'];

		$sql=$conn->prepare('SELECT * FROM vehiculos WHERE placa = :P1');
		$resultado=$sql->execute(array('P1'=>$placaVehiculo));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			foreach ($resultado as $fila) {
				$idVehiculo=$fila['idVehiculo'];
			}
			$sql2=$conn->prepare('SELECT * FROM convenios WHERE idConvenio = :P1');
			$resultado2=$sql2->execute(array('P1'=>$idConvenio));
			$resultado2=$sql2->fetchAll();
			$num2=$sql2->rowCount();

			if ($num2>=1) {
				foreach ($resultado2 as $fila) {
					$minutosConvenio=$fila['minutosConvenio'];
					$dineroConvenio=$fila['dineroConvenio'];
				}
				$sql3=$conn->prepare('SELECT * FROM flujovehiculo WHERE idVehiculo = :P1 AND estado = :P2');
				$resultado3=$sql3->execute(array('P1'=>$idVehiculo, 'P2'=>"activo"));
				$resultado3=$sql3->fetchAll();
				$num3=$sql3->rowCount();

				if ($num3>=1) {
					foreach ($resultado3 as $fila) {
						$idFlujoVehiculo=$fila['idFlujoVehiculo'];
						$minutosCalculados=$fila['minutosReales']-$minutosConvenio;
						$idTarifa=$fila['idTarifa'];						
					}
					$sql4=$conn->prepare('SELECT * FROM tarifas WHERE idTarifa = :P1');
					$resultado4=$sql4->execute(array('P1'=>$idTarifa));
					$resultado4=$sql4->fetchAll();
					$num4=$sql4->rowCount();

					if ($num4>=1) {
						foreach ($resultado4 as $fila) {
							$valor=$fila['valorTarifa']*$minutosCalculados;
							$iva=$valor*$_SESSION["iva"]/100;
							$valorFlujoVehiculo=$valor+$iva;

							if ($valorFlujoVehiculo>50) {
								$valorFlujoVehiculo=ceil($valorFlujoVehiculo/50);
								$valorFlujoVehiculo=$valorFlujoVehiculo*50;
							}else{
								$valorFlujoVehiculo=50;
							}

							echo '			
								<input type="hidden" class="valorSinIva" value="'.$valor.'">
								<input type="hidden" class="iva" value="'.$iva.'">
								<div class="form-group col-md-6">
									<h4><label for="tiempoCalculado2">Tiempo</label></h4>
									<input type="text" data-id="'.$idFlujoVehiculo.'" class="input form-control" id="tiempoCalculado" value="'.$minutosCalculados.'" autocomplete="off" readonly>
								</div>
								<div class="form-group col-md-6">
									<h4><label for="valor">Valor (Incluye IVA)</label></h4>
									<input type="text" class="input form-control" id="valor" value="'.$valorFlujoVehiculo.'" autocomplete="off" readonly>
								</div>
								<div class="form-group col-md-12">
									<h4><label for="tipoPago">Tipo de Pago</label></h4>
									<select class="input form-control" id="tipoPago">
										<option value="1">Efectivo</option>
										<option value="2">Tarjeta Débito</option>
										<option value="3">Tarjeta Crédito</option>
									</select>
								</div>
								<div id="pagos">
									<div class="form-group col-md-6">
										<h4><label for="dineroRecibido">Dinero Recibido *</label></h4>
										<input type="text" class="input form-control" id="dineroRecibido" placeholder="Dinero Recibido" autocomplete="off">
									</div>
									<div class="form-group col-md-6">
										<h4><label for="cambioDinero">Cambio</label></h4>
										<input type="text" class="input form-control" id="cambioDinero" placeholder="Cambio" autocomplete="off" readonly>
									</div>								
									<div class="text-center">
										<button type="button" class="cancelar btn btn-danger btn-lg">Cancelar</button>
										<button type="button" class="liquidar btn btn-success btn-lg">Liquidar</button>
										<button type="button" id="factura" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".bs-example-modal-sm2">Ver Factura</button>
									</div>
								</div>
							';
						}
					}else{
						echo 'Error Inesperado';	
					}
				}else{
					echo 'Error Inesperado';	
				}
			}else{
				echo 'Error Inesperado';
			}
		}else{
			echo 'Error Inesperado';
		}
		$conn=NULL; //Cerrar la Conexión
	}catch(PDOException $e){
		echo "ERROR: ".$e->getMessage();
		exit();
	}
?>