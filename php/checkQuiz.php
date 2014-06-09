<?php

	header('Access-Control-Allow-Origin: *');
    require("conn.php");

    $marker = $_POST["marker"];
    $usuario = $_POST["usuario"];

    if (isset($marker) && isset($usuario)) {
    	$query = "SELECT notas.nota, notas.fecha, examenes.nombre,
    		(SELECT COUNT(id_pregunta) FROM preguntas
 			WHERE examen=(SELECT id_examen FROM examenes WHERE marcador=?)) AS num_preguntas
			FROM notas
			LEFT JOIN examenes ON examenes.id_examen=notas.examen
			WHERE examen=(SELECT id_examen FROM examenes WHERE marcador=?)
			AND usuario=?
			LIMIT 1";


		$stmt = $mysqli->stmt_init();
		$stmt = $mysqli->prepare($query);
		$stmt->bind_param('iii', $marker, $marker, $usuario);

		// ejecuto el  query
		$stmt->execute();
		$stmt->bind_result($res_nota, $res_fecha, $res_nombre, $res_num_preguntas);

		$data = array();
		if ($stmt->fetch()) {

			$data['existe'] = true;
			$data['nota'] = $res_nota;
			$data['fecha'] = $res_fecha;
			$data['nombre'] = $res_nombre;
			$data['num_preguntas'] = $res_num_preguntas;

			echo json_encode($data);

		} else {
			$data['existe'] = false;
			echo json_encode($data);

		}

		// Cerrar statement
		$stmt->close();



    } else {
    	echo json_encode(false);
    }

    $mysqli->close();

?>