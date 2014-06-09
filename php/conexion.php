<?php
	$servidorBD = "127.0.0.1";
	$usuarioBD = "vygoowbi_asdel";
	$claveBD = "asdelday27";
	$basedatos = "vygoowbi_appasionate";

    // creamos el objeto conexion
	$conexion = mysql_connect($servidorBD,$usuarioBD,$claveBD)  or die ("<center>ERROR EN LA CONEXION a MYSQL</center>");
	// seleccionamos la base de datos
	mysql_select_db($basedatos,$conexion) or die ("<center>ERROR conectando a la base de datos</center>");
	mysql_query ("SET NAMES 'utf8'");
?>