<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$usuario;

	// RECOGER PARAMETROS
	if (isset($_POST['usuario'])) {
		$usuario = $_POST['usuario'];
		getCursos();
	} else {
		echo json_encode(array("res" => false));
	}


	function getCursos() {
		global $mysqli, $usuario;

		$query = 'SELECT clases.id_clase, clases.nombre  FROM clases
					LEFT JOIN usuarios_clases ON usuarios_clases.clase=clases.id_clase
					WHERE usuarios_clases.usuario=?
					ORDER BY clases.nombre ASC';

		$stmt = $mysqli->stmt_init();
		if ($stmt = $mysqli->prepare($query)) {

		    $stmt->bind_param('i', $usuario);

		    /* ejecuto el  query */
		    $stmt->execute();
		    $stmt->bind_result($res_id_clase, $res_nombre);

		    $data = array();
			$json = array();
			while ($stmt->fetch()) {
				$data['id_clase'] = $res_id_clase;
				$data['nombre'] = $res_nombre;

				$json['cursos'][] = $data;
			}

			$json['res'] = true;

		    echo json_encode($json);

		    $stmt->close();
		} else {
			echo json_encode(array("res" => false));
		}

		$mysqli->close();
	}

?>