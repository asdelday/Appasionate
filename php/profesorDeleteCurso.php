<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	$clase=$_POST['clase'];

	// EMPTY
	if (isset($clase)) {

		/* set autocommit to off */
		$mysqli->autocommit(FALSE);

		$query = "DELETE FROM clases WHERE id_clase=?";
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('i', $clase);
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