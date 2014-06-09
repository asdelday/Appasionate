<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$nombre;
	$usuario;
	$order;
	$pagina = 1;
	$maxResults = 10;

	// RECOGER PARAMETROS
	if (isset($_POST['nombre'])) {
		$nombre = $_POST['nombre'];
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

	getCursos();



	function getCursos() {
		global $mysqli, $usuario, $nombre, $order, $pagina, $maxResults;

		$bindParam = new BindParam();
		$qArray1 = array();
		$qArray2 = array();

		$query = 'SELECT clases.id_clase, clases.nombre, clases.cod,
				CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS profesor,
						(SELECT COUNT(clases.id_clase) FROM (clases
						LEFT JOIN usuarios_clases ON usuarios_clases.clase=clases.id_clase)
						INNER JOIN usuarios ON usuarios.id_usuario=usuarios_clases.usuario
						WHERE ';

		// FILTROS
		$qArray1[] = 'usuarios.id_usuario = ?';
		$bindParam->add('i', $usuario);

		if(isset($nombre)){
		    $qArray1[] = 'clases.nombre LIKE ?';
		    $auxNombre = '%' . $nombre . '%';
		    $bindParam->add('s', $auxNombre);
		}

		$query .= implode(' AND ', $qArray1);
		$query .= ' LIMIT 1) AS totalRegistros ';


		$query .= 'FROM (clases
						LEFT JOIN usuarios_clases ON usuarios_clases.clase=clases.id_clase)
						INNER JOIN usuarios ON usuarios.id_usuario=usuarios_clases.usuario
					WHERE ';

		// FILTROS
		$qArray2[] = 'usuarios.id_usuario = ?';
		$bindParam->add('i', $usuario);

		if(isset($nombre)){
		    $qArray2[] = 'clases.nombre LIKE ?';
		    $auxNombre = '%' . $nombre . '%';
		    $bindParam->add('s', $auxNombre);
		}

		$query .= implode(' AND ', $qArray2);

		// SORT
		if(isset($order)) {
		    $query .= ' ORDER BY %s';
		} else {
			$query .= ' ORDER BY %s';
			$order = 'clases.nombre ASC';
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
		$stmt->bind_result($res_id_clase, $res_nombre, $res_cod, $res_profesor, $res_totalRegistros);

		$data = array();
		$json = array();
		while ($stmt->fetch()) {
			$data['id_clase'] = $res_id_clase;
			$data['nombre'] = $res_nombre;
			$data['cod'] = $res_cod;
			$data['profesor'] = $res_profesor;
			$data['totalRegistros'] = $res_totalRegistros;

			$json[] = $data;
		}

		//echo json_encode($json);

		$stmt->close();

		for ($i=0; $i<sizeof($json); $i++) {
			$idClass = $json[$i]['id_clase'];

			$query2 = 'SELECT COUNT(usuario) AS alumnos FROM usuarios_clases WHERE clase='.$idClass.' LIMIT 1';
			if ($result = $mysqli->query($query2)) {

				$data = array();
				while($row = $result->fetch_assoc()){
					$json[$i]['alumnos'] = $row['alumnos'] - 1;
				}

				$result->free();

			} else {
				$json[$i]['alumnos'] = 0;
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