<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$usuario;

	// RECOGER PARAMETROS
	if (isset($_POST['usuario'])) {
		$usuario = $_POST['usuario'];
		getAlumnos();
	} else {
		echo json_encode(array("res" => false));
	}


	function getAlumnos() {
		global $mysqli, $usuario;

		$query = 'SELECT DISTINCT usuarios.id_usuario,
				CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS alumno
				FROM (usuarios
      			INNER JOIN usuarios_clases ON usuarios_clases.usuario=usuarios.id_usuario)
      			INNER JOIN clases ON usuarios_clases.clase=clases.id_clase
				WHERE clases.profesor=?
				ORDER BY alumno ASC';

		$stmt = $mysqli->stmt_init();
		if ($stmt = $mysqli->prepare($query)) {

		    $stmt->bind_param('i', $usuario);

		    /* ejecuto el  query */
		    $stmt->execute();
		    $stmt->bind_result($res_id_usuario, $res_alumno);

		    $data = array();
			$json = array();
			while ($stmt->fetch()) {
				$data['id_usuario'] = $res_id_usuario;
				$data['alumno'] = $res_alumno;

				$json['alumnos'][] = $data;
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