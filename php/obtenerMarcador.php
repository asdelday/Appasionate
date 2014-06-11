<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

	$usuario = $_POST["usuario"];
	$marker = $_POST["marker"];

	$query = "SELECT lat, lng, titulo, descripcion, clases.nombre AS clase
				FROM (usuarios_clases INNER JOIN clases ON clases.id_clase=usuarios_clases.clase)
				INNER JOIN marcadores ON clases.id_clase=marcadores.clase
				WHERE usuarios_clases.usuario=? AND marcadores.id_marcador=?
				LIMIT 1";

	/* Ejecuto el método prepare y este me va a devolver el objeto */
	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('ii', $usuario, $marker);

	    /* ejecuto el  query */
	    $stmt->execute();
	    $stmt->bind_result($res_lat, $res_lng, $res_titulo, $res_descripcion, $res_clase);

	    $data = array();
		if($stmt->fetch()){
			$data['lat'] = $res_lat;
			$data['lng'] = $res_lng;
			$data['titulo'] = $res_titulo;
			$data['descripcion'] = $res_descripcion;
			$data['clase'] = $res_clase;
			$data['imagenes'] = array();
			$data['res'] = true;
		} else {
			$fail = array();
			$fail['res'] = false;
			echo json_encode($fail);
		}

		$stmt->close();

	} else {
		$fail = array();
		$fail['res'] = false;
		echo json_encode($fail);
	}

	$mysqli->close();

	require("conn.php");


	if ($result = $mysqli->query("SELECT id_imagen, path_imagen FROM imagenes WHERE marcador=".$marker)) {

		$imgData = array();
		while($row = $result->fetch_assoc()){
			$imgData[] = $row;
		}
		$data['imagenes'] = $imgData;

		$result->free();

	} else {
		$fail = array();
		$fail['res'] = false;
		echo json_encode($fail);
	}


	echo json_encode($data);


	$mysqli->close();

?>