<?php

    header('Access-Control-Allow-Origin: *');
    require("conn.php");
    $mysqli->autocommit(FALSE);



    if (isset($_POST["usuario"]) && isset($_POST['imagen'])) {
    	$usuario = $_POST["usuario"];
    	$imagen = $_POST["imagen"];

    	// DELETE EXAMEN
		$queryDelete = "DELETE imagenes.* FROM imagenes
						INNER JOIN marcadores ON marcadores.id_marcador=imagenes.marcador
						WHERE marcadores.creador=? AND imagenes.id_imagen=?";

		$stmtDelete = $mysqli->prepare($queryDelete);
		$stmtDelete->bind_param('ii', $usuario, $imagen);
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