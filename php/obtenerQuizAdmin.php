<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

    $marker = $_POST["marker"];
    $usuario = $_POST["usuario"];

    if (isset($marker) && isset($usuario)) {
    	// VARIABLES
    	$examen = array();
    	$json = array();
    	$json['info'] = array();
    	$json['questions'] = array();

    	// QUERIES
    	$queryMarcador = "SELECT titulo AS marcador FROM marcadores
    					WHERE creador=? AND id_marcador=? LIMIT 1";

	    $queryExamen = "SELECT examenes.id_examen, examenes.nombre, examenes.descripcion
	    				FROM examenes
	    				INNER JOIN marcadores ON marcadores.id_marcador=examenes.marcador
	    				WHERE examenes.marcador=? AND marcadores.creador=? LIMIT 1";

	    $queryPreguntas = "SELECT id_pregunta, pregunta, mensaje_correcto, mensaje_error
	    					FROM preguntas WHERE examen=?";

	    $queryOpciones = "SELECT id_opcion, opcion, correcta FROM opciones WHERE pregunta=?";



	    // OBTENER MARCADOR
	    // ====================================================================================
	    $stmt = $mysqli->stmt_init();
	    $stmt = $mysqli->prepare($queryMarcador);
	    $stmt->bind_param('ii', $usuario, $marker);

	    // ejecuto el  query
	    $stmt->execute();
	    $stmt->bind_result($res_marcador);
		$stmt->fetch();

		$json['marcador'] = $res_marcador;

		// Cerrar statement
		$stmt->close();

		if (isset($res_marcador)) {
		    // OBTENER EXAMEN
		    // ====================================================================================
		    $stmt = $mysqli->stmt_init();
		    $stmt = $mysqli->prepare($queryExamen);
		    $stmt->bind_param('ii', $marker, $usuario);

		    // ejecuto el  query
		    $stmt->execute();
		    $stmt->bind_result($res_id_examen, $res_nombre, $res_descripcion);
			$stmt->fetch();
			// Variable locales
			$examen['id_examen'] = $res_id_examen;
			$examen['nombre'] = $res_nombre;
			$examen['descripcion'] = $res_descripcion;
			$examen['preguntas'] = array();
			// variable de retorno
			$json['id'] = $res_id_examen;
			$json['marcador'] = $res_marcador;
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
			echo json_encode($json);
		}

	} else {
		echo json_encode(false);
	}

	$mysqli->close();

?>