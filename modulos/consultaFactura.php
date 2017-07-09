<?php
	try{
		session_start();
		require "conexion.php";

		$placaVehiculo=$_POST['placaVehiculo'];

		$sql2=$conn->prepare('SELECT MAX(factura.idFactura), MAX(flujovehiculo.idFlujoVehiculo), vehiculos.placa FROM factura INNER JOIN flujovehiculo ON flujovehiculo.idFlujoVehiculo=factura.idFlujoVehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo WHERE vehiculos.placa = :P1');
		$resultado2=$sql2->execute(array('P1'=>$placaVehiculo));
		$resultado2=$sql2->fetchAll();
		$num2=$sql2->rowCount();

		if ($num2>=1) {
			foreach ($resultado2 as $fila) {
				$fila['MAX(factura.idFactura)'];
			}
		}

		$sql=$conn->prepare('SELECT factura.idFactura, configuracion.nombreParqueadero, configuracion.nit, configuracion.direccion, configuracion.telefono, factura.nFactura, vehiculos.placa, flujovehiculo.idFlujoVehiculo, flujovehiculo.movFechaInicial, flujovehiculo.movFechaFinal, flujovehiculo.minutosCalculados, flujovehiculo.minutosReales, tarifas.nombre, tarifas.valorTarifa, flujovehiculo.dineroRecibido, flujovehiculo.cambio, flujovehiculo.valorCalculado, factura.resolucion, factura.tipoCompañia, factura.redondeo, movimientoscaja.idCaja, usuarios.fullname, flujovehiculo.valorFlujoVehiculo, flujovehiculo.ivaFlujoVehiculo, tipoPago.tipoPago, flujovehiculo.codigoTransacion FROM factura INNER JOIN configuracion ON configuracion.idParqueadero=factura.idParqueadero INNER JOIN flujovehiculo ON flujovehiculo.idFlujoVehiculo=factura.idFlujoVehiculo INNER JOIN vehiculos ON vehiculos.idVehiculo=flujovehiculo.idVehiculo INNER JOIN movimientoscaja ON movimientoscaja.idCaja=flujovehiculo.idCaja INNER JOIN usuarios ON usuarios.idUser=movimientoscaja.idUser INNER JOIN tarifas ON tarifas.idTarifa=flujovehiculo.idTarifa INNER JOIN tipoPago ON tipoPago.idTipoPago=flujovehiculo.idTipoPago WHERE vehiculos.placa = :P1 AND flujovehiculo.estado = "inactivo" AND factura.idFactura= :P2');
		$resultado=$sql->execute(array('P1'=>$placaVehiculo, 'P2'=>$fila['MAX(factura.idFactura)']));
		$resultado=$sql->fetchAll();
		$num=$sql->rowCount();

		if ($num>=1) {
			foreach ($resultado as $fila) {
				echo '
					<center> 
						<article>
							<h3>'.$fila['nombreParqueadero'].'</h3>
							<table>
								<tr>
									<td>Nit: '.$fila['nit'].'</td>
								</tr>
								<tr>
									<td>Dirección: '.$fila['direccion'].'</td>
								</tr>
								<tr>
									<td>Tel: '.$fila['telefono'].'</td>
								</tr>
								<tr>
									<td>Factura Numero: '.$fila['nFactura'].'</td>
								</tr>
							</table>
						</article>
					<hr>
						<article>
							<table>
								<tr>
									<th>Placa:</th>
									<td>'.$fila['placa'].'</td>
								</tr>
								<tr>
									<th>Hora Entrada:</th>
									<td>'.$fila['movFechaInicial'].'</td>
								</tr>
								<tr>
									<th>Hora Salida:</th>
									<td>'.$fila['movFechaFinal'].'</td>
								</tr>
								<tr>
									<th>Tiempo Estadia: </th>
									<td> '.$fila['minutosReales'].' Minutos</td>
								</tr>
								<tr>
									<th>Convenio: </th>
									<td> '.$fila['minutosCalculados'].' Minutos</td>
								</tr>
								<tr>
									<th>Modalidad:</th>
									<td>'.$fila['nombre'].'</td>
								</tr>
								<tr>
									<th>Tipo de Pago:</th>
									<td>'.$fila['tipoPago'].'</td>
								</tr>
								<tr>
									<th>Transacion:</th>
									<td>'.$fila['codigoTransacion'].'</td>
								</tr>
								<tr>
									<th>Tarifa:</th>
									<td> $ '.number_format($fila['valorTarifa']).'</td>									
								</tr>
								<tr>
									<th>Sub Total:</th>
									<td> $ '.number_format($fila['valorCalculado']).'</td>									
								</tr>
								<tr>
									<th>Iva '.$_SESSION["iva"].'%:</th>									
									<td> $ '.number_format($fila['ivaFlujoVehiculo']).'</td>
								</tr>
								<tr>
									<th>Total:</th>									
									<td> $ '.number_format($fila['valorCalculado']+$fila['ivaFlujoVehiculo']).'</td>
								</tr>
								<tr>
									<th>Valor Redondeado:</th>
									<td> $ '.number_format($fila['valorFlujoVehiculo']).'</td>
								</tr>
								<tr>
									<th>Dinero Recibido:</th>
									<td> $ '.number_format($fila['dineroRecibido']).'</td>
								</tr>
								<tr>
									<th>Cambio:</th>
									<td> $ '.number_format($fila['cambio']).'</td>
								</tr>
							</table>
							<hr>
							<p>'.$fila['resolucion'].'</p>
							<p>'.$fila['tipoCompañia'].'</p>
							<hr>
							<p>'.$fila['redondeo'].'</p>
							<img id="codigoBarras2"/>
						</article>
						<p>'.$_SESSION["ofertasAvisos"].'</p>
						<h6>Factura Impresa Por: '.$fila['nombreParqueadero'].'</h6>
						<h6>Nit: '.$fila['nit'].'</h6>
						<h6>Atendido Por: '.$fila['fullname'].'</h6>
					<center>
					';
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