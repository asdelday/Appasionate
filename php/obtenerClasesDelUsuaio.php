<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

	$id_usr = $_POST["id"];

	$query = "SELECT id_clase, nombre FROM clases WHERE profesor=? ORDER BY nombre";

	/* Ejecuto el método prepare y este me va a devolver el objeto */
	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('i', $id_usr);

	    /* ejecuto el  query */
	    $stmt->execute();
	    $stmt->bind_result($res_id_clase, $res_nombre);

	    $data = array();
	    $json = array();
		while($stmt->fetch()){
			$data['id_clase'] = $res_id_clase;
			$data['nombre'] = $res_nombre;

			$json[] = $data;
		}
		echo json_encode($json);

	    $stmt->close();
	} else {
		echo json_encode(array());
	}

	$mysqli->close();

?>