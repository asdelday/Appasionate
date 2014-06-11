<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");
    $mysqli->autocommit(FALSE);



    if (isset($_POST["profesor"]) && isset($_POST['alumno'])) {
    	$profesor = $_POST["profesor"];
    	$alumno = $_POST["alumno"];

    	// DELETE EXAMEN
		$queryDelete = "DELETE usuarios_clases.* FROM usuarios_clases
						INNER JOIN clases ON usuarios_clases.clase=clases.id_clase
						WHERE clases.profesor=? AND usuarios_clases.usuario=?";

		$stmtDelete = $mysqli->prepare($queryDelete);
		$stmtDelete->bind_param('ii', $profesor, $alumno);
		$stmtDelete->execute();

		if ($mysqli->commit()) {
			echo json_encode(true);
		} else {
			echo json_encode(false);
		}

    } else{
    	echo json_encode(false);
    }


	$mysqli->close();

?>