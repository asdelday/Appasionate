<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");
    $mysqli->autocommit(FALSE);

    if (!empty($_POST["titulo"]) && !empty($_POST["descripcion"]) && !empty($_POST["clase"]) && isset($_POST["usuario"]) && isset($_POST['marker'])) {
    	$usuario = $_POST["usuario"];
    	$marker = $_POST["marker"];
    	$titulo = $_POST["titulo"];
		$descripcion = $_POST["descripcion"];
		$clase = $_POST["clase"];

		$query = "UPDATE marcadores SET titulo=?, descripcion=?, clase=?
					WHERE creador=? AND id_marcador=?";

		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('ssiii', $titulo, $descripcion, $clase, $usuario, $marker);
		$stmt->execute();

		if ($mysqli->commit()) {
			$data = array();
    		$data['res'] = true;
    		echo json_encode($data);
		} else {
			$data = array();
    		$data['res'] = false;
    		echo json_encode($data);
		}

    } else{
    	$data = array();
    	$data['res'] = false;
    	echo json_encode($data);
    }

	$mysqli->close();

?>