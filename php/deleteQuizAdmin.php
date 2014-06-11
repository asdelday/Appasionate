<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");
    $mysqli->autocommit(FALSE);



    if (isset($_POST["marker"])) {
    	$marcador = $_POST["marker"];

    	// DELETE EXAMEN
		$queryDelete = "DELETE FROM examenes WHERE marcador=?";

		$stmtDelete = $mysqli->prepare($queryDelete);
		$stmtDelete->bind_param('i', $marcador);
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