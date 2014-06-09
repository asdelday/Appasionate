<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");

    $examen = $_POST["examen"];
    $score = $_POST["score"];
    $usuario = $_POST["usuario"];

    if (isset($examen) && isset($score) && isset($usuario)) {
    	// VARIABLES
    	$id_nota;
    	$res;

    	// QUERIES
	    $querySelect = "SELECT id_nota FROM notas WHERE examen=? AND usuario=? LIMIT 1";

	    $queryInsert = "INSERT INTO notas(id_nota, examen, usuario, nota, fecha) VALUES (NULL, ?, ?, ?, now())";

	    $queryUpdate = "UPDATE notas SET nota=?, fecha=now() WHERE id_nota=?";


	    // SELECT NOTAS
	    // ====================================================================================
	    $stmt = $mysqli->stmt_init();
	    $stmt = $mysqli->prepare($querySelect);
	    $stmt->bind_param('ii', $examen, $usuario);

	    // ejecuto el  query
	    $stmt->execute();
	    $stmt->bind_result($res_id_nota);
		if ($stmt->fetch()) {
			$id_nota = $res_id_nota;

			// Cerrar statement
			$stmt->close();

			$stmt = $mysqli->stmt_init();
	    	$stmt = $mysqli->prepare($queryUpdate);
	    	$stmt->bind_param('ii', $score, $id_nota);

	    	$stmt->execute();
	    	$res = $stmt->affected_rows;

	    	// Cerrar statement
			$stmt->close();

		} else {
			// Cerrar statement
			$stmt->close();

			$stmt = $mysqli->stmt_init();
	    	$stmt = $mysqli->prepare($queryInsert);
	    	$stmt->bind_param('iii', $examen, $usuario, $score);

			$stmt->execute();
			$res = $stmt->affected_rows;

	    	// Cerrar statement
			$stmt->close();
		}

		if ($res > 0) {
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}



    } else {
    	echo json_encode(false);
    }

    $mysqli->close();

?>