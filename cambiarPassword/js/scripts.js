$(document).on("ready",iniciar);
	function iniciar() {
		$("#recuperar").on("click", recuperar);
	}

	function recuperar() {
		var email= $("#email").val();
		$.ajax({
			type: 'POST',
			url: 'validaremail.php',
			data: {email:email},
			success: function (respuesta) {
				$("#mensaje").html(respuesta);
			}
		})
	}