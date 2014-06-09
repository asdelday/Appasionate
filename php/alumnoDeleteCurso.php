<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");


	// EMPTY
	if (isset($_POST['clase']) && isset($_POST['usuario'])) {
		$clase = $_POST['clase'];
		$usuario = $_POST['usuario'];

		/* set autocommit to off */
		$mysqli->autocommit(FALSE);

		$query = "DELETE FROM usuarios_clases WHERE clase=? AND usuario=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('ii', $clase, $usuario);
		$stmt->execute();

		/* commit transaction */
		if ($mysqli->commit()) {
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}

		$stmt->close();

	} else {
		echo json_encode(false);
	}


	$mysqli->close();

?>