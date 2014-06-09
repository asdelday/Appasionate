<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$nomApe;
	$clase;
	$usuario;
	$order;
	$pagina = 1;
	$maxResults = 10;

	// RECOGER PARAMETROS
	if (isset($_POST['nomApe'])) {
		$nomApe = $_POST['nomApe'];
	}

	if (isset($_POST['clase'])) {
		$clase = $_POST['clase'];
	}

	if (isset($_POST['usuario'])) {
		$usuario = $_POST['usuario'];
	} else {
		$usuario = 1;
	}

	if (isset($_POST['order'])) {
		$order = $_POST['order'];
	}

	if (isset($_POST['pagina'])) {
		$pagina = $_POST['pagina'];
	}

	if (isset($_POST['maxResults'])) {
		$maxResults = $_POST['maxResults'];
	}

	getAlumnos();



	function getAlumnos() {
		global $mysqli, $clase, $usuario, $nomApe, $order, $pagina, $maxResults;

		$bindParam = new BindParam();
		$qArray1 = array();
		$qArray2 = array();

		$query = 'SELECT DISTINCT usuarios.id_usuario,
				CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS alumno, usuarios.email,
				(SELECT COUNT(DISTINCT usuarios.id_usuario)
      				FROM (usuarios
      				INNER JOIN usuarios_clases ON usuarios_clases.usuario=usuarios.id_usuario)
      				INNER JOIN clases ON usuarios_clases.clase=clases.id_clase
					WHERE ';

		// FILTROS
		$qArray1[] = 'clases.profesor = ?';
		$bindParam->add('i', $usuario);

		$qArray1[] = 'usuarios.id_usuario != ?';
		$bindParam->add('i', $usuario);

		if(isset($nomApe)){
		    $qArray1[] = '(usuarios.nombre LIKE ? OR usuarios.apellidos LIKE ?)';
		    $auxNomApe = '%' . $nomApe . '%';
		    $bindParam->add('s', $auxNomApe);
		    $bindParam->add('s', $auxNomApe);
		}

		if(isset($clase)){
		    $qArray1[] = 'clases.id_clase = ?';
		    $bindParam->add('i', $clase);
		}

		$query .= implode(' AND ', $qArray1);
		$query .= ' LIMIT 1) AS totalRegistros ';


		$query .= 'FROM (usuarios
      				INNER JOIN usuarios_clases ON usuarios_clases.usuario=usuarios.id_usuario)
      				INNER JOIN clases ON usuarios_clases.clase=clases.id_clase
					WHERE ';

		// FILTROS
		$qArray2[] = 'clases.profesor = ?';
		$bindParam->add('i', $usuario);

		$qArray2[] = 'usuarios.id_usuario != ?';
		$bindParam->add('i', $usuario);

		if(isset($nomApe)){
		    $qArray2[] = '(usuarios.nombre LIKE ? OR usuarios.apellidos LIKE ?)';
		    $auxNomApe = '%' . $nomApe . '%';
		    $bindParam->add('s', $auxNomApe);
		    $bindParam->add('s', $auxNomApe);
		}

		if(isset($clase)){
		    $qArray2[] = 'clases.id_clase = ?';
		    $bindParam->add('i', $clase);
		}

		$query .= implode(' AND ', $qArray2);

		// SORT
		if(isset($order)) {
		    $query .= ' ORDER BY %s';
		} else {
			$query .= ' ORDER BY %s';
			$order = 'usuarios.nombre ASC';
		}

		// PAGINACION
		$query .= ' LIMIT ?,?';
		$pag = ($pagina - 1) * $maxResults;
		$bindParam->add('i', $pag);
		$bindParam->add('i', $maxResults);

		$query = sprintf($query, mysqli_real_escape_string($mysqli, $order));

		$stmt = $mysqli->prepare($query);
		$params = $bindParam->get();
		$tmp = array();
        foreach($params as $key => $value) $tmp[$key] = &$params[$key];
		call_user_func_array(array($stmt, 'bind_param'), $tmp);

		$stmt->execute();
		$stmt->bind_result($res_id_usuario, $res_alumno, $res_email, $res_totalRegistros);

		$data = array();
		$json = array();
		while ($stmt->fetch()) {
			$data['id_usuario'] = $res_id_usuario;
			$data['alumno'] = $res_alumno;
			$data['email'] = $res_email;
			$data['totalRegistros'] = $res_totalRegistros;

			$json[] = $data;
		}

		//echo json_encode($json);

		$stmt->close();

		for ($i=0; $i<sizeof($json); $i++) {
			$idUsuario = $json[$i]['id_usuario'];

			$query2 = 'SELECT clases.nombre FROM usuarios_clases
						INNER JOIN clases ON clases.id_clase=usuarios_clases.clase
						WHERE usuarios_clases.usuario='.$idUsuario;
			if ($result = $mysqli->query($query2)) {

				$dataCursos = array();
				while($row = $result->fetch_assoc()){
					$dataCursos[] .= $row['nombre'];
				}

				$cadCursos = implode(', ', $dataCursos);
				$json[$i]['cursos'] = $cadCursos;

				$result->free();

			} else {
				$json[$i]['cursos'] = ' ';
			}
		}

		$mysqli->close();

		echo json_encode($json);
	}


	// CONSTRUCTOR DE BIND PARAM
	class BindParam{
	    private $values = array(), $types = '';

	    public function add( $type, &$value ){
	        $this->values[] = $value;
	        $this->types .= $type;
	    }

	    public function get(){
	        return array_merge(array($this->types), $this->values);
	    }
	}
?>