<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	$titulo = $_POST["titulo"];
	$descripcion = $_POST["descripcion"];
	$lat = $_POST["lat"];
	$lng = $_POST["lng"];
	$creador = $_POST["creador"];
	$clase = $_POST["clase"];

	// EMPTY
	if (empty($titulo) || empty($descripcion) || empty($lat) || empty($lng) || empty($creador) || empty($clase)) {
		echo json_encode(array('res' => true));
	}

	else {

		// CREADOR Y CLASE CORRESPONDIENTE EXISTENTE
		$queryCreador = "SELECT COUNT(usuarios.id_usuario) AS count FROM usuarios
						INNER JOIN clases ON clases.profesor = usuarios.id_usuario
						WHERE id_usuario=? AND id_clase=? AND rol=1 LIMIT 1";
		$stmtCreador = $mysqli->stmt_init();
		if ($stmtCreador = $mysqli->prepare($queryCreador)) {

			$stmtCreador->bind_param('ii', $creador, $clase);

			$stmtCreador->execute();
			$stmtCreador->bind_result($res_count_creador);
			$stmtCreador->fetch();

			if ($res_count_creador <= 0) {
			   	echo json_encode(array('res' => true));
			    $stmtCreador->close();
			}

			// CREADOR CORRECTO
			else {
				$stmtCreador->close();

			    /* set autocommit to off */
				$mysqli->autocommit(FALSE);

				// INSERTAR MARCADOR
				$queryInsertUsr = "INSERT INTO marcadores(lat, lng, titulo, descripcion, creador, clase)
									VALUES (?, ?, ?, ?, ?, ?)";
				$stmtInsertUsr = $mysqli->prepare($queryInsertUsr);
				$stmtInsertUsr->bind_param('ddssii', $lat, $lng, $titulo, $descripcion, $creador, $clase);
				$stmtInsertUsr->execute();

				$markerId = $mysqli->insert_id;

				/* commit transaction */
				if ($mysqli->commit()) {
					echo json_encode(array('res' => true, 'id' => $markerId));
				} else {
					echo json_encode(array('res' => true));
				}
			}

		// CREADOR STMT INCORRECTA
		} else {
			echo json_encode(array('res' => true));
		}

	}

	$mysqli->close();

?>