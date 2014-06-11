<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

    $marker = $_POST["marker"];
    //$marker = 1;

    if (isset($marker)) {
    	// VARIABLES
    	$examen = array();
    	$json = array();
    	$json['info'] = array();
    	$json['questions'] = array();

    	// QUERIES
	    $queryExamen = "SELECT id_examen, nombre, descripcion, abierto FROM examenes WHERE marcador=? LIMIT 1";

	    $queryPreguntas = "SELECT id_pregunta, pregunta, mensaje_correcto, mensaje_error
	    					FROM preguntas WHERE examen=?";

	    $queryOpciones = "SELECT id_opcion, opcion, correcta FROM opciones WHERE pregunta=?";


	    // OBTENER EXAMEN
	    // ====================================================================================
	    $stmt = $mysqli->stmt_init();
	    $stmt = $mysqli->prepare($queryExamen);
	    $stmt->bind_param('i', $marker);

	    // ejecuto el  query
	    $stmt->execute();
	    $stmt->bind_result($res_id_examen, $res_nombre, $res_descripcion, $res_abierto);
		$stmt->fetch();
		// Variable locales
		$examen['id_examen'] = $res_id_examen;
		$examen['nombre'] = $res_nombre;
		$examen['descripcion'] = $res_descripcion;
		$examen['preguntas'] = array();
		// variable de retorno
		$json['id'] = $res_id_examen;
		$json['abierto'] = $res_abierto;
		$json['info']['name'] = $res_nombre;
		$json['info']['main'] = $res_descripcion;
		$json['info']['results'] = "";

		// Cerrar statement
		$stmt->close();


		// OBTENER PREGUNTAS
	    // ====================================================================================
		if(isset($examen['id_examen'])) {
			//$json['questions'][] = new array();

			$stmt = $mysqli->stmt_init();
	    	$stmt = $mysqli->prepare($queryPreguntas);
	    	$stmt->bind_param('i', $examen['id_examen']);

	    	// ejecuto el  query
		    $stmt->execute();
		    $stmt->bind_result($res_id_pregunta, $res_pregunta, $res_mensaje_correcto, $res_mensaje_error);

		    $data = array();
		    $questions = array();
		    while($stmt->fetch()){
				$data['id_pregunta'] = $res_id_pregunta;
				$data['pregunta'] = $res_pregunta;
				$data['mensaje_correcto'] = $res_mensaje_correcto;
				$data['mensaje_error'] = $res_mensaje_error;
				$data['opciones'] = array();

				$examen['preguntas'][] = $data;

				// variable de retorno
				$questions['q'] = $res_pregunta;
				$questions['a'] = array();
				$questions['correct'] = $res_mensaje_correcto;
				$questions['incorrect'] = $res_mensaje_error;

				$json['questions'][] = $questions;
			}

			// Cerrar statement
			$stmt->close();


			// OBTENER OPCIONES
	    	// ====================================================================================
	    	for ($i = 0; $i < sizeof($examen['preguntas']); $i++) {
	    		$stmt = $mysqli->stmt_init();
		    	$stmt = $mysqli->prepare($queryOpciones);
		    	$stmt->bind_param('i', $examen['preguntas'][$i]['id_pregunta']);

		    	// ejecuto el  query
			    $stmt->execute();
			    $stmt->bind_result($res_id_opcion, $res_opcion, $res_correcta);

			    $data = array();
			    $option = array();
			    while($stmt->fetch()){
					$data['id_opcion'] = $res_id_opcion;
					$data['opcion'] = $res_opcion;
					$data['correcta'] = $res_correcta;

					$examen['preguntas'][$i]['opciones'][] = $data;

					// variable de retorno
					$correctBool = ($res_correcta == 1)?true:false;
					$option['option'] = $res_opcion;
					$option['correct'] = $correctBool;

					$json['questions'][$i]['a'][] = $option;
				}

				// Cerrar statement
				$stmt->close();
	    	}

	    	echo json_encode($json);

		} else {
			echo json_encode(false);
		}

	} else {
		echo json_encode(false);
	}

	$mysqli->close();

?>