<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$titulo;
	$clase;
	$usuario;
	$order;
	$pagina = 1;
	$maxResults = 10;

	// RECOGER PARAMETROS
	if (isset($_POST['titulo'])) {
		$titulo = $_POST['titulo'];
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

	getCursos();



	function getCursos() {
		global $mysqli, $clase, $usuario, $titulo, $order, $pagina, $maxResults;

		$bindParam = new BindParam();
		$qArray1 = array();
		$qArray2 = array();

		$query = 'SELECT marcadores.id_marcador, marcadores.lat, marcadores.lng,
				marcadores.titulo, marcadores.descripcion, clases.nombre AS clase,
				CONCAT(marcadores.lat, ",", marcadores.lng) AS localizacion,
				(SELECT COUNT(marcadores.id_marcador) FROM marcadores
				INNER JOIN clases ON clases.id_clase=marcadores.clase
				WHERE ';

		// FILTROS
		$qArray1[] = 'marcadores.creador = ?';
		$bindParam->add('i', $usuario);

		if(isset($titulo)){
		    $qArray1[] = 'marcadores.titulo LIKE ?';
		    $auxtitulo = '%' . $titulo . '%';
		    $bindParam->add('s', $auxtitulo);
		}

		if(isset($clase)){
		    $qArray1[] = 'clases.id_clase = ?';
		    $bindParam->add('i', $clase);
		}

		$query .= implode(' AND ', $qArray1);
		$query .= ' LIMIT 1) AS totalRegistros ';


		$query .= 'FROM marcadores
				INNER JOIN clases ON clases.id_clase=marcadores.clase
				WHERE ';

		// FILTROS
		$qArray2[] = 'marcadores.creador = ?';
		$bindParam->add('i', $usuario);

		if(isset($titulo)){
		    $qArray2[] = 'marcadores.titulo LIKE ?';
		    $auxtitulo = '%' . $titulo . '%';
		    $bindParam->add('s', $auxtitulo);
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
			$order = 'marcadores.titulo ASC';
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
		$stmt->bind_result($res_id_marcador, $res_lat, $res_lng, $res_titulo, $res_descripcion, $res_clase, $res_localizacion, $res_totalRegistros);

		$data = array();
		$json = array();
		while ($stmt->fetch()) {
			$data['id_marcador'] = $res_id_marcador;
			$data['lat'] = $res_lat;
			$data['lng'] = $res_lng;
			$data['titulo'] = $res_titulo;
			$data['descripcion'] = $res_descripcion;
			$data['clase'] = $res_clase;
			$data['localizacion'] = $res_localizacion;
			$data['totalRegistros'] = $res_totalRegistros;

			$json[] = $data;
		}

		echo json_encode($json);

		$stmt->close();

		$mysqli->close();
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