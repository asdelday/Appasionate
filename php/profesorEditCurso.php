<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");


	// EMPTY
	if (isset($_POST['nombre']) && isset($_POST['cod']) && isset($_POST['clase']) && isset($_POST['usuario'])) {
		$nombre = $_POST['nombre'];
		$cod=$_POST['cod'];
		$clase=$_POST['clase'];
		$usuario=$_POST['usuario'];

		$query = "UPDATE clases
					SET nombre=?, cod=?
					WHERE profesor=? AND id_clase=?";

		$stmt = $mysqli->stmt_init();
		if ($stmt = $mysqli->prepare($query)) {

			$stmt->bind_param('ssii', $nombre, $cod, $usuario, $clase);

			$stmt->execute();
			$res = $stmt->affected_rows;

			if ($res > 0) {
				echo json_encode(array("res" => "actualizado"));
			} else {
				echo json_encode(array("res" => "No habia cambios"));
			}

	    	// Cerrar statement
			$stmt->close();

		} else {
			echo json_encode(array("res" => "error en el servidor"));
		}

	} else {
		echo json_encode(array("res" => "error en el servidor"));
	}


	$mysqli->close();

?>