<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	$cod=$_POST['cod'];
	$usuario=$_POST['usuario'];

	// EMPTY
	if (isset($cod) && isset($usuario)) {
		$query = "SELECT COUNT(id_clase) AS count, id_clase
				FROM clases WHERE cod=? LIMIT 1";

		$stmtcod = $mysqli->stmt_init();
		if ($stmtcod = $mysqli->prepare($query)) {

			$stmtcod->bind_param('s', $cod);

			$stmtcod->execute();
			$stmtcod->bind_result($res_count, $res_id_clase);
			if ($stmtcod->fetch()) {
				if ($res_count > 0) {
				   	$stmtcod->close();

				   	$query = "SELECT COUNT(clases.id_clase) AS count
							FROM clases
							INNER JOIN usuarios_clases ON usuarios_clases.clase=clases.id_clase
							WHERE clases.id_clase=? AND usuarios_clases.usuario=?";

					$stmtclass = $mysqli->stmt_init();
					if ($stmtclass = $mysqli->prepare($query)) {

						$stmtclass->bind_param('ii', $res_id_clase, $usuario);

						$stmtclass->execute();
						$stmtclass->bind_result($res_count2);

						if ($stmtclass->fetch()) {
							if ($res_count2 == 0) {
							   	$stmtclass->close();

							   	/* set autocommit to off */
								$mysqli->autocommit(FALSE);

								$queryInsertUsrClass = "INSERT INTO usuarios_clases(usuario, clase)
														VALUES (?, ?)";
								$stmtInsertUsrClass = $mysqli->prepare($queryInsertUsrClass);
							    $stmtInsertUsrClass->bind_param('ii', $usuario, $res_id_clase);
							    $stmtInsertUsrClass->execute();

								/* commit transaction */
								if ($mysqli->commit()) {
									echo json_encode(array("res" => "insertado"));
								} else {
									echo json_encode(array("res" => "error en el servidor"));
								}


							} else {
								echo json_encode(array("res" => "ya esta registrado en el curso"));
							}
						} else {
							echo json_encode(array("res" => "error en el servidor"));
						}
					} else {
						echo json_encode(array("res" => "error en el servidor"));
					}

				} else {
					echo json_encode(array("res" => "código incorrecto"));
				}
			} else {
				echo json_encode(array("res" => "error en el servidor"));
			}

		} else {
			echo json_encode(array("res" => "error en el servidor"));
		}

	} else {
		echo json_encode(array("res" => "error en el servidor"));
	}


	$mysqli->close();

?>