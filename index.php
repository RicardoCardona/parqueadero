<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<title>Login Admin</title>
	<!-- Integracion de Favicon -->
	<link rel="shortcut icon" href="img/favicon.png">
	<!-- Equivalente al framework de bootstrap css -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<!-- Estilo modificable para la interfaz del login principal -->
	<link rel="stylesheet" href="css/estilo.css">
</head>
<body>
	<div class="col-md-4 col-sm-1 col-xs-1"></div>
	<div class="col-md-4 col-sm-10  col-xs-10">
		<form role="form" name="login" id="formLogin" action="login/login.php" method="post">
			<img src="img/2ParkLogo.png"><br>
			<h3 class="text-center">Iniciar Sesion</h3><br>
			<input type="text" class="form-control" name="username" placeholder="Nombre de usuario"><br>
			<input type="password" class="form-control" name="password" placeholder="Contraseña">
			<br>
			<input type="submit" class="btn btn-primary form-control" name="login" value="Entrar"> <br><br>
			<a class="text-center" id="cambiarPassword" href="cambiarPassword/index.html">Cambiar Contraseña</a>
		</form>
	</div>
	<div class="col-md-4 col-sm-1 col-xs-1"></div>
<script src="js/valida_login.js"></script>
</body>
</html>