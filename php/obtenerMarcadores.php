<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

	$id_usr = $_POST["id"];
	//$id_usr = 1;

	$query = "SELECT id_marcador, lat, lng, titulo, descripcion, clases.nombre AS clase
				FROM (usuarios_clases INNER JOIN clases ON clases.id_clase=usuarios_clases.clase)
				INNER JOIN marcadores ON clases.id_clase=marcadores.clase
				WHERE usuarios_clases.usuario=?
				ORDER BY titulo";

	/* Ejecuto el método prepare y este me va a devolver el objeto */
	$stmt = $mysqli->stmt_init();
	if ($stmt = $mysqli->prepare($query)) {

	    $stmt->bind_param('i', $id_usr);

	    /* ejecuto el  query */
	    $stmt->execute();
	    $stmt->bind_result($res_id_marcador, $res_lat, $res_lng, $res_titulo, $res_descripcion, $res_clase);

	    $data = array();
	    $json = array();
		while($stmt->fetch()){
			$data['id_marcador'] = $res_id_marcador;
			$data['lat'] = $res_lat;
			$data['lng'] = $res_lng;
			$data['titulo'] = $res_titulo;
			$data['descripcion'] = $res_descripcion;
			$data['clase'] = $res_clase;
			$data['imagenes'] = array();

			$json[] = $data;
		}

		$stmt->close();

	} else {
		echo json_encode(array());
	}

	$mysqli->close();

	require("conn.php");

	/*$query = "SELECT path_imagen FROM imagenes WHERE marcador=?";
	for ($i = 0; $i < sizeof($json); $i++) {


		$stmt = $mysqli->stmt_init();
		if ($stmt = $mysqli->prepare($query)) {

		    $stmt->bind_param('i', $json[$i]['id_marcador']);

		    $stmt->execute();
		    $stmt->bind_result($res_path);

		    $data = array();
			while($stmt->fetch()){
				$data['imagen'] = $res_path;

				$json[$i]['imagenes'] = $data;
			}

			$stmt->close();

			echo json_encode($json);


		} else {
			echo json_encode(array());
		}


	}*/

	if(sizeof($json) > 0) {

		for ($i = 0; $i < sizeof($json); $i++) {

			if ($result = $mysqli->query("SELECT path_imagen FROM imagenes WHERE marcador=".$json[$i]['id_marcador'])) {

			    $data = array();
			    while($row = $result->fetch_assoc()){
					$data[] = $row;
				}
				$json[$i]['imagenes'] = $data;

			    $result->free();

			} else {
				echo json_encode(array());
			}


		}

		echo json_encode($json);

	}

	$mysqli->close();

?>