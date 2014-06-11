<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");
    $mysqli->autocommit(FALSE);

    $marcador = $_POST["marcador"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $auxOpen = $_POST["abierto"];
    $abierto = ($auxOpen === true || $auxOpen === "true" || $auxOpen === 1)?1:0;
    $preguntas = $_POST["preguntas"];


	// DELETE EXAMEN
	$queryDelete = "DELETE FROM examenes WHERE marcador=?";

	$stmtDelete = $mysqli->prepare($queryDelete);
	$stmtDelete->bind_param('i', $marcador);
	$stmtDelete->execute();


	// INSERT EXAMEN
	$queryExamen = "INSERT INTO examenes(marcador, nombre, descripcion, abierto)
					VALUES (?, ?, ?, ?)";

	$stmtExamen = $mysqli->prepare($queryExamen);
	$stmtExamen->bind_param('issi', $marcador, $nombre, $descripcion, $abierto);
	$stmtExamen->execute();

	$examenId = $mysqli->insert_id;


	// INSERT PREGUNTAS
	if (sizeof($preguntas) > 0 || isset($preguntas)) {
		$queryPregunta = "INSERT INTO preguntas(examen, pregunta, mensaje_correcto, mensaje_error)
						  VALUES (? ,? ,? ,?)";

		$queryOpcion = "INSERT INTO opciones(pregunta, opcion, correcta) VALUES (?, ?, ?)";

		for ($i=0; $i < sizeof($preguntas); $i++) {
			$question = $preguntas[$i];

			$pregunta = $question['pregunta'];
			$mensaje_correcto = $question['mensaje_correcto'];
			$mensaje_error = $question['mensaje_error'];
			$opciones = $question['opciones'];

			$stmtPregunta = $mysqli->prepare($queryPregunta);
			$stmtPregunta->bind_param('isss', $examenId, $pregunta, $mensaje_correcto, $mensaje_error);
			$stmtPregunta->execute();

			$preguntaId = $mysqli->insert_id;


			// INSERT OPCIONES
			if (sizeof($opciones) > 0 || isset($opciones)) {

				for ($j=0; $j < sizeof($opciones); $j++) {
					$option = $opciones[$j];

					$opcion = $option["opcion"];
				    $auxBool = $option["correcta"];
				    $correcta = ($auxBool === true || $auxBool === "true" || $auxBool === 1)?1:0;

				    $stmtOpcion = $mysqli->prepare($queryOpcion);
					$stmtOpcion->bind_param('isi', $preguntaId, $opcion, $correcta);
					$stmtOpcion->execute();

				} // FOR - Opciones

			} // IF - Opciones

		} // FOR - Preguntas

	} // IF - Preguntas


	/* commit transaction */
	if ($mysqli->commit()) {
		echo json_encode(true);
	} else {
		echo json_encode(false);
	}

	$mysqli->close();

?>