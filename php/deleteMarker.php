<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");
    $mysqli->autocommit(FALSE);



    if (isset($_POST["usuario"]) && isset($_POST['marker'])) {
    	$usuario = $_POST["usuario"];
    	$marker = $_POST["marker"];

    	// DELETE EXAMEN
		$queryDelete = "DELETE FROM marcadores
						WHERE creador=? AND id_marcador=?";

		$stmtDelete = $mysqli->prepare($queryDelete);
		$stmtDelete->bind_param('ii', $usuario, $marker);
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