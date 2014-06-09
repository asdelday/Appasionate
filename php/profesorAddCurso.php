<?php
	header('Access-Control-Allow-Origin: *');
	require("conn.php");


	// EMPTY
	if (isset($_POST['nombre']) && isset($_POST['cod']) && isset($_POST['usuario'])) {
		$nombre = $_POST['nombre'];
		$cod=$_POST['cod'];
		$usuario=$_POST['usuario'];

		$query = "SELECT COUNT(id_clase) AS count, id_clase
				FROM clases WHERE cod=? LIMIT 1";

		$stmtcod = $mysqli->stmt_init();
		if ($stmtcod = $mysqli->prepare($query)) {

			$stmtcod->bind_param('s', $cod);

			$stmtcod->execute();
			$stmtcod->bind_result($res_count, $res_id_clase);
			if ($stmtcod->fetch()) {
				if ($res_count == 0) {
				   	$stmtcod->close();

					/* set autocommit to off */
					$mysqli->autocommit(FALSE);

					// INSERTAR USUARIO
					$queryInsertClass = "INSERT INTO clases(id_clase, nombre, profesor, cod)
										VALUES (NULL, ?, ?, ?)";
					$stmtInsertClass = $mysqli->prepare($queryInsertClass);
					$stmtInsertClass->bind_param('sss', $nombre, $usuario, $cod);
					$stmtInsertClass->execute();

					$classId = $mysqli->insert_id;

					// INSERTAR USUARIO_CLASE
					$queryInsertUsrClass = "INSERT INTO usuarios_clases(usuario, clase)
											VALUES (?, ?)";
					$stmtInsertUsrClass = $mysqli->prepare($queryInsertUsrClass);
					$stmtInsertUsrClass->bind_param('ii', $usuario, $classId);
					$stmtInsertUsrClass->execute();


					/* commit transaction */
					if ($mysqli->commit()) {
						echo json_encode(array("res" => "insertado"));
					} else {
						echo json_encode(array("res" => "error en el servidor"));
					}

					$stmtInsertClass->close();
					$stmtInsertUsrClass->close();

				} else {
					echo json_encode(array("res" => "Curso con código ya existente"));
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