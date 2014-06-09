<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

	//$id_usr = $_POST["id"];
	$id_marker = 1;

	$query = "SELECT path_imagen FROM imagenes WHERE marcador=?";

	/* Ejecuto el método prepare y este me va a devolver el objeto */
	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('i', $id_marker);

	    /* ejecuto el  query */
	    $stmt->execute();
	    $stmt->bind_result($res_path);

	    $data = array();
	    $json = array();
		while($stmt->fetch()){
			$data['imagen'] = $res_path;

			$json[] = $data;
		}

		$stmt->close();

		echo json_encode($json);


	} else {
		echo json_encode(array());
	}

	$mysqli->close();

?>