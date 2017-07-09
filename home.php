<!--
	Compañía: Bynary01
	Autor: Ricardo Cardona
	Sistema: 2park
	Fecha: 17/09/2016
	Descripción: Panel de administración 2Park	
-->
<?php
	date_default_timezone_set('America/Bogota');
	$fechaActual=date("Y-m-d").'T18:00';
	$fechaActualCorta=date('Y-m-d').'T00:00';

	session_start();
	if(!isset($_SESSION["fullname"]) || $_SESSION["fullname"]==null){
		print "<script>alert(\"Acceso invalido!\");window.location='index.php';</script>";
	}
	$hoy=date('Y-m-d H:i:s');
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<title>2park | Home</title>
	<!-- Integracion de Favicon -->
	<link rel="shortcut icon" href="img/favicon.png">
	<!-- Equivalente al framework de bootstrap css -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Estilo modificable para la interfaz del login principal -->
	<link rel="stylesheet" href="css/estilo.css">
	<!--conexion jquery-->
	<script src="js/jquery.min.js"></script>
	<!--conexion bootstrap de jquery-->
	<script src="js/bootstrap.min.js"></script>
	<!--conexion codigo libre jquery-->
	<script src="js/codigo.js"></script>
	<!--conexion codigo libre jquery-->
	<script src="js/clock.js"></script>
	<!--conexion codigo de Barras jquery-->
	<script src="js/JsBarcode.all.min.js"></script>
</head>
<body>  	
	<!--Contenedor-->
	<div class="content">
		<!--Pestañas-->
		<input type="radio" id="tab1" name="tabs" checked/>
		<input type="radio" id="tab2" name="tabs"/>
		<input type="radio" id="tab3" name="tabs"/>
		<input type="radio" id="tab4" name="tabs"/>
		<input type="radio" id="tab5" name="tabs"/>
		<input type="radio" id="tab6" name="tabs"/>
		<input type="radio" id="tab7" name="tabs"/>
		<input type="radio" id="tab8" name="tabs"/>
		<input type="radio" id="tab9"/>

		<!--Titulos de Pestañas-->
		<label class="tabs" for="tab1">Reservas</label>
		<label class="tabs" for="tab2">Historial</label>
		<label class="tabs" for="tab3">Reportes</label>
		<label class="tabs" for="tab4">Arqueo</label>
		<label class="tabs" for="tab5">Tarifas</label>
		<span id="etiquetaMensaje"></span>
		<label class="tabs" for="tab7">Facturas</label>
		<label class="tabs" for="tab8">Comprobantes</label>
		
		<!--Cerrar sesion-->
		<label class="tabs" for="tab9">
			<a type="button" data-toggle="modal" data-target=".bs-example-modal-lg">Salir</a>
		</label>
		<span id="ComprobanteImpresionAutomatica"></span>
		<!--Titulo de Parqueadero-->
		<label id="parkingName" class="tabs" for="tab10"><?php echo $_SESSION["nombreParqueadero"]; ?></label>

		<!--Hoja de Pestaña 1-->
		<section class="tab-uno">
			<!--resultado de php-->
			<div id="respuesta1" class="col-md-12"></div>
			<article class="col-md-5">
				<div id="reloj">
					<div id="Date"></div>
					<ul>
						<li id="hours"></li>
						<li id="point">:</li>
						<li id="min"></li>
						<li id="point">:</li>
						<li id="sec"></li>
					</ul>
				</div>				
				<form id="ingresarVehiculo" method="post">
					<div class="form-group col-md-12">
						<h4><label for="placa">Placa *</label></h4>
						<input type="text" value="" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" class="input form-control" id="placaVehiculo" placeholder="Placa del Vehículo" name="placa" spellcheck="false" autocomplete="off" maxlength="12">
					</div>
					<div id="respuesta2"></div>
				</form>
			</article>
			<div id="linea" class="hidden-xs hidden-sm">
				<img  src="img/1.jpg">
			</div>
			<article id="reservas">
				<div id="resultado"></div>
			</article>			
			<article id="notificaciones">
				<div id="scroll">
					<div id="resultadoNoti"></p></div>
				</div>
			</article>
		</section>

		<!--Hoja de Pestaña 2-->
		<section class="tab-dos">
			<div class="col-md-3"></div>
				<form id="consultarHistorial" method="post" class="col-md-6" action="reportes/reporteHistorial.php">
					<h4 class="text-center"><label for="placa">Ingrese Algún Parametro de Consulta</label></h4>
					<div class="form-group col-md-8">
						<input type="text" class="input form-control text-center" id="historial" name="historial" autocomplete="off" value="<?php echo date('Y-m-d'); ?>" placeholder="Ingrese Algún Parametro de Consulta">
					</div>
					<input type="submit" class="btn btn-primary btn-lg" value="Exportar a Excel"/>
				</form>
				<div id="respuesta3">
					<?php  include_once "modulos/consultarHistorialHoy.php"; ?>
				</div>
			<div class="col-md-3"></div>
		</section>

		<!--Hoja de Pestaña 3-->
		<section class="tab-tres">
			<center><h2>Reportes</h2></center>
			<center>
				<article>
					<input type="radio" id="tab2" name="tabs"/>
					<label class="btn btn-primary btn-lg" for="tab2">Historial</label>
					<input type="radio" id="tab4" name="tabs"/>
					<label class="btn btn-primary btn-lg" for="tab4">Arqueo</label>
					<input type="radio" id="tab5" name="tabs"/>
					<label class="btn btn-primary btn-lg" for="tab5">Tarifas</label>
				</article>
			</center>
		</section>

		<!--Hoja de Pestaña 4-->
		<section class="tab-cuatro">
			<center><h2>Arqueo</h2></center>
			<div class="form-inline text-center">
				<form id="consultarArqueo" method="post" class="col-md-12" action="reportes/reporteArqueo.php">
					<div class="form-group">
						<h4><label for="inicio">Fecha-Hora de Inicio</label></h4>
						<input type="datetime-local" class="input form-control" placeholder="Fecha-Hora de Inicio" name="fechaInicio" id="fechaInicio" autocomplete="off" value="<?php echo $fechaActualCorta; ?>">
					</div>
					<div class="form-group">
						<h4><label for="fin">Fecha-Hora de Fin</label></h4>
						<input type="datetime-local" class="input form-control" placeholder="Fecha-Hora de Fin" name="fechaFin" id="fechaFin" autocomplete="off" value="<?php echo $fechaActual; ?>">
					</div>
					<br><br>
					<div>
						<button type="button" class="calcularArqueo btn btn-success btn-lg">Consultar</button>
						<input type="submit" class="btn btn-primary btn-lg" value="Exportar a Excel">
					</div>
				</form>
			</div>
			<div id="botonImprimir" class="col-sm-offset-11"></div>
			<div id="respuesta5"></div>
		</section>

		<!--Hoja de Pestaña 5-->
		<section class="tab-cinco">
			<center><h2>Tarifas</h2></center>
			<form method="post" action="reportes/reporteTarifa.php">
				<input type="submit" class="btn btn-primary col-sm-offset-10" id="tabTarifas" value="Exportar a Excel"/>
			</form>
			<div id="respuesta6">
				<?php  include_once "modulos/consultaTarifa.php"; ?>
			</div>
		</section>

		<!--Hoja de Pestaña 6-->
		<section class="tab-seis" class="col-md-12">
			<center><h2>Mensajes</h2></center>
			<div id="respuesta7" class="col-md-6">
				<?php  include_once "modulos/consultaMensaje.php"; ?>
			</div>
			<div id="respuesta8" class="col-md-6">
				<h3>Seleccione Un Mensaje Por Favor</h3>
			</div>
		</section>

		<!--Hoja de Pestaña 7-->
		<section class="tab-siete" class="col-md-12">
			<div class="col-md-3"></div>
			<form id="" method="post" class="col-md-6">
				<h4 class="text-center"><label for="consultaCopiaFactura">Ingrese Placa o Numero de Factura</label></h4>
				<div class="form-group col-md-12">
					<input type="text" class="input form-control text-center" id="consultaCopiaFactura" name="parametro" autocomplete="off" placeholder="Ingrese Placa o Numero de Factura">
				</div>
			</form>				
			<div class="col-md-3"></div>
			<div id="respuesta9"></div>
		</section>

		<!--Hoja de Pestaña 8-->
		<section class="tab-ocho" class="col-md-12">
			<div class="col-md-3"></div>
			<form id="" method="post" class="col-md-6">
				<h4 class="text-center"><label for="consultaCopiaComprobante">Ingrese Placa</label></h4>
				<div class="form-group col-md-12">
					<input type="text" class="input form-control text-center" id="consultaCopiaComprobante" name="parametro2" autocomplete="off" placeholder="Ingrese Placa">
				</div>
			</form>				
			<div class="col-md-3"></div>
			<div id="respuesta10"></div>
		</section>

		<!--Hoja de Pestaña 9 asigna el enlace de logout-->
	</div>

	<div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  		<div class="modal-dialog modal-sm" role="document">
    		<div class="modal-content">    			
				<div id="lugarComprobante"></div>				
						
				<div class="modal-footer">
					<button type="button" id="impresionComprobante" class="btn btn-success" data-dismiss="modal">Imprimir</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade bs-example-modal-sm2" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  		<div class="modal-dialog modal-sm" role="document">
    		<div class="modal-content">
    			<div class="modal-header">
    				<button type="button" id="impresionFactura" class="btn btn-success" data-dismiss="modal">Imprimir</button>

					<div id="lugarFactura" class="col-md-12"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<form action="login/logout.php" method="POST">
					<div class="form-group col-md-12">
						<h3>¿Desea Incluir Alguna Incidencia?</h3>
						<textarea id="descripcionSalida" class="input form-control" placeholder="Ingresar Incidencia"></textarea>
					</div>
					<div class="modal-footer">
						<button type="button" class="salir btn btn-success btn-lg">SALIR</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade bs-example-modal-sm3" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel">
  		<div class="modal-dialog modal-sm" role="document">
    		<div class="modal-content">    			
				<div id="impresionAutomatica">					
					<center>
						<strong><h3><?php  echo $_SESSION["nombreParqueadero"]; ?></h3></strong>
						<h5><?php echo $hoy; ?></h5>
						<img id="codigoBarras3"/>
					</center>
				</div>					
				<div class="modal-footer">
					<button type="button" id="impresionComprobante3" class="btn btn-success" data-dismiss="modal">Imprimir</button>
				</div>
			</div>
		</div>
	</div>
</body>
</html>