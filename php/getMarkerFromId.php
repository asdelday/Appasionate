<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");



    if (isset($_POST["usuario"]) && isset($_POST['marker'])) {
    	$usuario = $_POST["usuario"];
    	$marker = $_POST["marker"];

    	// DELETE EXAMEN
		$query = "SELECT lat, lng, titulo, descripcion, clase
					FROM marcadores WHERE creador=? AND id_marcador=?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('ii', $usuario, $marker);
		$stmt->execute();

		$stmt->bind_result($res_lat, $res_lng, $res_titulo, $res_descripcion, $res_clase);

	    $data = array();
		if($stmt->fetch()){
			$data['lat'] = $res_lat;
			$data['lng'] = $res_lng;
			$data['titulo'] = $res_titulo;
			$data['descripcion'] = $res_descripcion;
			$data['clase'] = $res_clase;
			$data['res'] = true;
		}

		echo json_encode($data);

		$stmt->close();

    } else{
    	$data = array();
    	$data['res'] = false;
    	echo json_encode($data);
    }

	$mysqli->close();

?>