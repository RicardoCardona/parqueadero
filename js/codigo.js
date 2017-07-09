$(document).on('ready', iniciar );
	function iniciar(){
		$.ajax({
			type: 'POST',
			url: 'consultaPlantas.php',
			success: function (resultado) {
				$("#resultado").html(resultado);
				$(".reserva").on("click", reserva);
			}
		});
	}
	setInterval(iniciar, 3000);

$(document).on('ready', iniciando );
	function iniciando() {
		$("#placaVehiculo").on("keyup", buscar);
		$(".calcularArqueo").on("click", calcularArqueo);		
		$("#tab2").on("click", tab2);
		$("#historial").on("keyup", historial);
		$(".salir").on("click", salir);
		$(".cuerpotabla").on("click", verMasMensaje);
		$("#consultaCopiaFactura").on("keyup", consultaCopiaFactura);
		$("#consultaCopiaComprobante").on("keyup", consultaCopiaComprobante);

		$.ajax({
			type: 'POST',
			url: 'webServices/consumoHistorial.php',
			success: function (resultado) {
			}
		});
		$('form').keypress(function(e){   
    		if(e == 13){
      			return false;
    		}
  		});

  		$('input').keypress(function(e){
    		if(e.which == 13){
      			return false;
    		}
  		});
	}

$(document).on('ready', start);
	function start() {
		$.ajax({
			type: 'POST',
			url: 'modulos/notificaciones.php',
			success: function (Reserva) {
				$("#resultadoNoti").html(Reserva);
			}
		});
		$.ajax({
			type: 'POST', 
			url: 'modulos/etiquetaMensaje.php',
			success: function(etiquetaMensaje){
				$("#etiquetaMensaje").html(etiquetaMensaje);
			}
		});
		$.ajax({
			type: 'POST',
			url: 'modulos/consultaMensaje.php',
			success: function(verMasMensaje2){
				$("#respuesta7").html(verMasMensaje2);
				$(".cuerpotabla").on("click", verMasMensaje);
			}
		});
	}
setInterval(start, 3000);

$(document).on('ready', usuarios);
	function usuarios() {
		$.ajax({
			type: 'POST',
			url: 'usuarios.php',
			success: function (Reserva) {
				// alert(Reserva);
			}
		})		
		$.ajax({
			type: 'POST',
			url: 'webServices/mensajes.php',
			success: function (Reserva) {
			}
		})
		$.ajax({
			type: 'POST',
			url: 'webServices/convenios.php',
			success: function (Reserva) {
			}
		})
		$.ajax({
			type: 'POST',
			url: 'webServices/tarifas.php',
			success: function (Reserva) {
			}
		})
	}
setInterval(usuarios, 1800000);

function buscar(){
	if ($("#placaVehiculo").val().length >0) {
		$.ajax({
			type: 'POST', 
			url: 'modulos/consultarVehiculo.php',
			data: $("#placaVehiculo").serialize(), 
			success: function(consultarVehiculo){
				$("#respuesta2").html(consultarVehiculo);
				$('.ingresarVehiculo').attr('disabled', false);
				$('.liquidar').attr('disabled', false);
				$(".ingresarVehiculo").on("click", ingresarVehiculo);
				$(".cancelar").on("click", cancelarIngresoVehiculo);
				$("#dineroRecibido").on("keyup", cambio);
				$(".liquidar").on("click", liquidar);
				$("#comprobante").hide();
				$("#factura").hide();
				$("#convenio").on("change", convenio);
				$("#tipoPago").on("change", tipoPago);
			}
		});
	}else{
		$("#respuesta2").html('');
	}
	$.ajax({
		type: 'POST',
		url: 'webServices/mensajes.php',
		success: function (Reserva) {
		}
	})
}
setInterval(buscar, 30000);

function ingresarVehiculo(){
	var placaVehiculo = $('#placaVehiculo').val();
	var tipoVehiculo = $('#tipoVehiculo').val();
	var descripcion = $('#descripcion').val();
	var modalidad=$("#modalidad").val();

	var datosIngresarVehiculo = {
								"placaVehiculo":placaVehiculo,
								"tipoVehiculo":tipoVehiculo,
								"descripcion":descripcion,
								"modalidad":modalidad
							};
	if ($('#placaVehiculo').val().length < 6) {
		alert('¡ERROR! La Placa Debe Tener Por Lo Menos 6 Caracteres');
	}if ($('#placaVehiculo').val().length > 12) {
		alert('¡ERROR! La Placa No Debe Tener Mas De 12 Caracteres');
	}else{
		$.ajax({
			type: 'POST',
			url: 'modulos/ingresarVehiculo.php',
			data: datosIngresarVehiculo,
			success: function(ingresoVehiculo){
				$("#respuesta1").html(ingresoVehiculo);
				$("#comprobante").show();
				$("#comprobante").on("click", comprobar);
				$('.ingresarVehiculo').attr('disabled', true);
			}
		});
	}
}

function comprobar(){
	var placaVehiculo = $('#placaVehiculo').val();
	$.ajax({
		type: 'POST',
		url: 'modulos/consultaComprobante.php',
		data: {"placaVehiculo":placaVehiculo},
		success: function(comprobante){
			$("#lugarComprobante").html(comprobante);
			$("#codigoBarras").JsBarcode(placaVehiculo, {displayValue:true, fontSize:18, width:2, height:50});
			$("#impresionComprobante").on("click", impresionComprobante);
		}
	});
}

function impresionComprobante(){
	var objeto=document.getElementById('lugarComprobante');  //obtenemos el objeto a imprimir
	var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
	ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
	ventana.document.close();  //cerramos el documento
	ventana.print();  //imprimimos la ventana
	ventana.close();  //cerramos la ventana
	$('#placaVehiculo').val('');
	$("#respuesta1").html('');
	$("#respuesta2").html('');
}

function cancelarIngresoVehiculo(){
	$('#descripcion').val('');
	$('#placaVehiculo').val('');
	$("#respuesta2").html('');
}

function cambio(){
	var valor = $('#valor').val();
	var dineroRecibido = $('#dineroRecibido').val();
	var devuelta = dineroRecibido-valor;
	$("#cambioDinero").val(devuelta);
}

function liquidar(){
	var idIngresarVehiculo = $('#tiempoCalculado').data('id');
	var placaVehiculoLiquidar = $('#placaVehiculo').val();
	var tiempoCalculadoLiquidar = $('#tiempoCalculado').val();
	var valorLiquidar = $('#valor').val();
	var dineroRecibidoLiquidar = $('#dineroRecibido').val();
	var cambioDineroLiquidar = $('#cambioDinero').val();
	var valorSinIva = $('.valorSinIva').val();
	var iva = $('.iva').val();
	var estadoLiquidar = "inactivo";
	var idConvenio = $('#convenio').val();
	var tipoPago = $('#tipoPago').val();
	

	if ($('#dineroRecibido').val().length == 0) {
		alert('¡ERROR! Ningún Campo Debe Quedar Vacio');
	}else if (cambioDineroLiquidar < 0 || cambioDineroLiquidar == "NaN") {
		alert('¡ERROR! El Dinero Recibido Debe Ser Menor o Igual Al Valor');
	}else{
		var liquidacion = {
							"idIngresarVehiculo": idIngresarVehiculo,
							"placaVehiculoLiquidar": placaVehiculoLiquidar,
							"tiempoCalculadoLiquidar": tiempoCalculadoLiquidar,
							"valorLiquidar": valorLiquidar,
							"dineroRecibidoLiquidar": dineroRecibidoLiquidar,
							"cambioDineroLiquidar": cambioDineroLiquidar,
							"valorSinIva": valorSinIva,
							"iva": iva,
							"estadoLiquidar": estadoLiquidar,
							"idConvenio": idConvenio,
							"tipoPago": tipoPago
						};
		
		if (confirm('¿Seguro que desea liquidar el Vehículo '+placaVehiculoLiquidar+'?')) {
			$.ajax({
				type: 'POST',
				url: 'modulos/liquidarVehiculo.php',
				data: liquidacion,
				success: function(liquidacionVehiculo){
					$("#respuesta1").html(liquidacionVehiculo);
					$("#factura").show();
					$("#factura").on("click", facturar);
					$('.liquidar').attr('disabled', true);
				}
			});
		}
	}
}

function facturar(){
	var placaVehiculo = $('#placaVehiculo').val();
	$.ajax({
		type: 'POST',
		url: 'modulos/consultaFactura.php',
		data: {"placaVehiculo":placaVehiculo},
		success: function(factura){
			$("#lugarFactura").html(factura);
			$("#codigoBarras2").JsBarcode(placaVehiculo, {displayValue:true, fontSize:18, width:2, height:50});
			$("#impresionFactura").on("click", impresionFactura);
		}
	});
}

function impresionFactura(){
	var objeto=document.getElementById('lugarFactura');  //obtenemos el objeto a imprimir
	var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
	ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
	ventana.document.close();  //cerramos el documento
	ventana.print();  //imprimimos la ventana
	ventana.close();  //cerramos la ventana
	$('#placaVehiculo').val('');
	$("#respuesta1").html('');
	$("#respuesta2").html('');
}

function calcularArqueo(){
	var fechaInicio = $('#fechaInicio').val();
	var fechaFin = $('#fechaFin').val();

	var calcularArqueo = {
			"fechaInicio": fechaInicio,
			"fechaFin": fechaFin
		};

	$.ajax({
		type: 'POST',
		url: 'modulos/ConsultaArqueo.php',
		data: calcularArqueo,
		success: function(calcularArqueo){
			$("#respuesta5").html(calcularArqueo);
			var html =' <button class="imprimirArqueo btn">Imprimir</button>';
			$('#botonImprimir').html(html);
			$(".imprimirArqueo").on("click", imprimirArqueo);
			$(".editarArqueo").on("click", editarArqueo);
		}
	});
}

function imprimirArqueo(){
	var objeto=document.getElementById('arqueoImprimir');  //obtenemos el objeto a imprimir
	var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
	ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
	ventana.document.close();  //cerramos el documento
	ventana.print();  //imprimimos la ventana
	ventana.close();  //cerramos la ventana
}

window.onbeforeunload = salida;
function salida(){
	$.ajax({
		type: 'POST',
		url: 'login/logout.php',
		success: function(calcularArqueo){

		}
	});
}

function editarArqueo(){
	var descuadre = $('#descuadre').val();
	var observaciones = $('#observaciones').val();

	var editarArqueo = {
			"descuadre": descuadre,
			"observaciones": observaciones
		};

	$.ajax({
		type: 'POST',
		url: 'modulos/editarArqueo.php',
		data: editarArqueo,
		success: function(calcularArqueo){
			$("#respuesta5").html(calcularArqueo);
			$(".imprimirArqueo").hide();
		}
	});
}

function historial(){
	$("#respuesta3").html('');

	if ($("#historial").val().length >0) {
		$.ajax({
			type: 'POST', 
			url: 'modulos/consultarHistorial.php',
			data: $("#historial").serialize(), 
			success: function(consultarHistorial){
				$("#respuesta3").html(consultarHistorial);
			}
		});
	}else{
		$("#respuesta3").html('');
	}
}


// Función para la cancelación de la reserva.
function reserva() {
	var div = $(event.target);
	var reserva= div.parents(".result").find(".reserva").data("reserva");
	var placa= div.parents(".result").find(".reserva").data("placa");

	if (reserva != 0) {
		if (confirm("¿Seguro desea cancelar la reserva ?")) {
			// alert(reserva);
			$.ajax({
				type: 'POST',
				url: 'modulos/finalizarReserva.php',
				data: {reserva:reserva, placa:placa},
				success: function (Reserva) {
					$("#ComprobanteImpresionAutomatica").html(Reserva);
					$("#impresionComprobante3").on("click", impresionComprobante3);
				}
			})
		} 
	}
}

function salir() {
	if ($("#descripcionSalida").val().length >0) {
		var descripcionSalida = $('#descripcionSalida').val();
		var jsonDescripcionSalida = {
				"descripcionSalida": descripcionSalida
			};

		$.ajax({
			type: 'POST',
			url: 'login/logout.php',
			data: jsonDescripcionSalida,
			success: function(descripcionSalida){
				window.location='index.php';
			}
		});
	}else{
		var descripcionSalida = "NULL";
		var jsonDescripcionSalida = {
				"descripcionSalida": descripcionSalida
			};

		$.ajax({
			type: 'POST',
			url: 'login/logout.php',
			data: jsonDescripcionSalida,
			success: function(descripcionSalida){
				window.location='index.php';
			}
		});
	}
}

function verMasMensaje(){
	$.ajax({
		type: 'POST',
		url: 'modulos/consultaMensaje.php',
		success: function(verMasMensaje2){
			$("#respuesta7").html(verMasMensaje2);
			$(".cuerpotabla").on("click", verMasMensaje);
		}
	});
	$("#respuesta8").html('');
	var Mensaje = $(event.target);
	var codigoMensaje = Mensaje.parents('tbody').find('.cuerpotabla').data('mensaje');

	var JsonCodigoMensaje = {"codigoMensaje": codigoMensaje};

	$.ajax({
		type: 'POST',
		url: 'modulos/verMasMensaje.php',
		data: JsonCodigoMensaje,
		success: function(verMasMensaje){
			$("#respuesta8").html(verMasMensaje);
			$(".cuerpotabla").on("click", verMasMensaje);
		}
	});
	$.ajax({
		type: 'POST',
		url: 'modulos/consultaMensaje.php',
		success: function(verMasMensaje2){
			$("#respuesta7").html(verMasMensaje2);
			$(".cuerpotabla").on("click", verMasMensaje);
		}
	});
	$.ajax({
		type: 'POST', 
		url: 'modulos/etiquetaMensaje.php',
		success: function(etiquetaMensaje){
			$("#etiquetaMensaje").html(etiquetaMensaje);
			$(".cuerpotabla").on("click", verMasMensaje);
		}
	});
}

function impresionComprobante3(){
	var objeto=document.getElementById('impresionAutomatica');  //obtenemos el objeto a imprimir
	var ventana=window.open('','_blank');  //abrimos una ventana vacía nueva
	ventana.document.write(objeto.innerHTML);  //imprimimos el HTML del objeto en la nueva ventana
	ventana.document.close();  //cerramos el documento
	ventana.print();  //imprimimos la ventana
	ventana.close();  //cerramos la ventana
	$("#impresionComprobante3").html('');
	
}

function consultaCopiaFactura(){
	$.ajax({
		type: 'POST', 
		url: 'modulos/consultaCopiaFactura.php',
		data: $("#consultaCopiaFactura").serialize(), 
		success: function(consultaCopiaFactura){
			$("#respuesta9").html(consultaCopiaFactura);
			$(".impresionCopiaFactura").on("click", impresionCopiaFactura);
		}
	});
}

function impresionCopiaFactura(){
	var fila = $(event.target);
	var codigoFactura = fila.parents('tr').find('.idFactura').val();
	var JsonFactura = {"codigoFactura": codigoFactura};

	$.ajax({
		type:'POST', 
		url:'modulos/copiaFactura.php',
		data: JsonFactura,
		success: function(copiaFactura){
			$("#lugarFactura").html(copiaFactura);
			$("#impresionFactura").on("click", impresionFactura);
		}
	});
}

function consultaCopiaComprobante(){
	$.ajax({
		type: 'POST', 
		url: 'modulos/consultaCopiaComprobante.php',
		data: $("#consultaCopiaComprobante").serialize(), 
		success: function(consultaCopiaComprobante){
			$("#respuesta10").html(consultaCopiaComprobante);
			$(".impresionCopiaComprobante").on("click", impresionCopiaComprobante);
		}
	});
}

function impresionCopiaComprobante(){
	var fila = $(event.target);
	var placaVehiculo = fila.parents('tr').find('.placa').val();

	var JsonPlaca = {"placaVehiculo": placaVehiculo};

	$.ajax({
		type:'POST', 
		url:'modulos/consultaComprobante.php',
		data: JsonPlaca,
		success: function(copiaComprobante){
			$("#lugarComprobante").html(copiaComprobante);
			$("#codigoBarras").JsBarcode(placaVehiculo, {displayValue:true, fontSize:18, width:2, height:50});
			$("#impresionComprobante").on("click", impresionComprobante);
		}
	});
}

function convenio(){	
	var convenio = $('#convenio').val();
	var placaVehiculo = $('#placaVehiculo').val();
	
	if ( convenio != 0) {
		var JsonConvenio = {"convenio": convenio, "placaVehiculo": placaVehiculo};

		$.ajax({
			type:'POST', 
			url:'modulos/consultaConvenio.php',
			data: JsonConvenio,
			success: function(convenio){
				$("#espacioLiquidar").html(convenio);
				$("#dineroRecibido").on("keyup", cambio);
				$(".liquidar").on("click", liquidar);
				$("#factura").hide();
				$("#tipoPago").on("change", tipoPago);
			}
		});
	}
}

function tab2(){	
	$.ajax({
		type: 'POST',
		url: 'modulos/consultarHistorialHoy.php',
		success: function (tab2) {
			$("#respuesta3").html(tab2);
		}
	});
}

function tipoPago(){
	var tipoPago = $("#tipoPago").val();
	if (tipoPago != 1) {
		var html = '<div class="form-group col-md-12">';
		html += '		<h4><label for="transacion">Codigo de Transación</label></h4>';
		html += '		<input type="text" class="input form-control" id="transacion" placeholder="Codigo de Transación" autocomplete="off">';
		html += '	</div>';
		html += '	<div class="text-center">';
		html += '		<button type="button" class="cancelar btn btn-danger btn-lg">Cancelar</button>';
		html += '		<button type="button" class="liquidarVehiculo btn btn-success btn-lg">Liquidar</button>';
		html += '		<button type="button" id="factura" class="btn btn-primary btn-lg" data-toggle="modal" data-target=".bs-example-modal-sm2">Ver Factura</button>';
		html += '	</div>';
		$("#pagos").html(html);
		$("#factura").hide();
		$(".liquidarVehiculo").on("click", liquidarVehiculo);
		$(".cancelar").on("click", cancelarIngresoVehiculo);
	}
}

function liquidarVehiculo(){
	var idIngresarVehiculo = $('#tiempoCalculado').data('id');
	var placaVehiculoLiquidar = $('#placaVehiculo').val();
	var tiempoCalculadoLiquidar = $('#tiempoCalculado').val();
	var valorLiquidar = $('#valor').val();
	var valorSinIva = $('.valorSinIva').val();
	var iva = $('.iva').val();
	var estadoLiquidar = "inactivo";
	var idConvenio = $('#convenio').val();
	var tipoPago = $('#tipoPago').val();	
	var transacion = $('#transacion').val();

	if ($('#transacion').val().length == 0) {
		alert('¡ERROR! Ningún Campo Debe Quedar Vacio');
	}else{
		var liquidacionVehiculo = {
							"idIngresarVehiculo": idIngresarVehiculo,
							"placaVehiculoLiquidar": placaVehiculoLiquidar,
							"tiempoCalculadoLiquidar": tiempoCalculadoLiquidar,
							"valorLiquidar": valorLiquidar,
							"valorSinIva": valorSinIva,
							"iva": iva,
							"estadoLiquidar": estadoLiquidar,
							"idConvenio": idConvenio,
							"tipoPago": tipoPago,
							"transacion": transacion
						};

		if (confirm('¿Seguro que desea liquidar el Vehículo '+placaVehiculoLiquidar+'?')) {
			$.ajax({
				type: 'POST',
				url: 'modulos/liquidarVehiculoTarjeta.php',
				data: liquidacionVehiculo,
				success: function(liquidacionVehiculo){
					$("#respuesta1").html(liquidacionVehiculo);
					$("#factura").show();
					$("#factura").on("click", facturar);
					$('.liquidarVehiculo').attr('disabled', true);
					$(".cancelar").on("click", cancelarIngresoVehiculo);
				}
			});
		}
	}
}