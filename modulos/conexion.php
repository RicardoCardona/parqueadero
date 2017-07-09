<?php
	// Conexion metodo PDO
	$conn = new PDO('mysql:host=localhost; dbname=adminPark', 'root', '');
	// Limpieza de Cache y Seguridad
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// Conexion a UTF8, uso de Tildes y Ñ
	$conn->exec("set names utf8");
?>