<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$alumno;
	$examen;
	$usuario;
	$order;
	$pagina = 1;
	$maxResults = 10;

	// RECOGER PARAMETROS
	if (isset($_POST['alumno'])) {
		$alumno = $_POST['alumno'];
	}

	if (isset($_POST['examen'])) {
		$examen = $_POST['examen'];
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

	getNotas();



	function getNotas() {
		global $mysqli, $alumno, $usuario, $examen, $order, $pagina, $maxResults;

		$bindParam = new BindParam();
		$qArray1 = array();
		$qArray2 = array();

		$query = 'SELECT notas.id_nota, notas.fecha, examenes.nombre AS examen,
					marcadores.titulo AS marcador, clases.nombre AS clase,
			        CONCAT(usuarios.nombre, " ", usuarios.apellidos) AS alumno,
			        (SELECT COUNT(notas.id_nota) FROM (((notas
						INNER JOIN examenes ON examenes.id_examen=notas.examen)
						INNER JOIN marcadores ON examenes.marcador=marcadores.id_marcador)
						INNER JOIN usuarios ON notas.usuario=usuarios.id_usuario)
						INNER JOIN clases ON clases.id_clase=marcadores.clase
						WHERE ';

		// FILTROS
		$qArray1[] = 'marcadores.creador = ?';
		$bindParam->add('i', $usuario);

		if(isset($alumno)){
		    $qArray1[] = 'notas.usuario = ?';
		    $bindParam->add('i', $alumno);
		}
		if(isset($examen)){
		    $qArray1[] = 'examenes.nombre LIKE ?';
		    $auxExamen = '%' . $examen . '%';
		    $bindParam->add('s', $auxExamen);
		}

		$query .= implode(' AND ', $qArray1);
		$query .= ' LIMIT 1) AS totalRegistros ';


		$query .= 'FROM (((notas
						INNER JOIN examenes ON examenes.id_examen=notas.examen)
						INNER JOIN marcadores ON examenes.marcador=marcadores.id_marcador)
						INNER JOIN usuarios ON notas.usuario=usuarios.id_usuario)
						INNER JOIN clases ON clases.id_clase=marcadores.clase
						WHERE ';

		// FILTROS
		$qArray2[] = 'marcadores.creador = ?';
		$bindParam->add('i', $usuario);

		if(isset($alumno)){
		    $qArray2[] = 'notas.usuario = ?';
		    $bindParam->add('i', $alumno);
		}
		if(isset($examen)){
		    $qArray2[] = 'examenes.nombre LIKE ?';
		    $auxExamen = '%' . $examen . '%';
		    $bindParam->add('s', $auxExamen);
		}

		$query .= implode(' AND ', $qArray2);

		// SORT
		if(isset($order)) {
		    $query .= ' ORDER BY %s';
		} else {
			$query .= ' ORDER BY %s';
			$order = 'examenes.nombre ASC';
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
		$stmt->bind_result($res_id_nota, $res_fecha, $res_examen, $res_marcador, $res_clase, $res_alumno, $res_totalRegistros);

		$data = array();
		$json = array();
		while ($stmt->fetch()) {
			$data['id_nota'] = $res_id_nota;
			$data['fecha'] = $res_fecha;
			$data['examen'] = $res_examen;
			$data['marcador'] = $res_marcador;
			$data['clase'] = $res_clase;
			$data['alumno'] = $res_alumno;
			$data['totalRegistros'] = $res_totalRegistros;

			$json[] = $data;
		}

		//echo json_encode($json);

		$stmt->close();

		for ($i=0; $i<sizeof($json); $i++) {
			$idNota = $json[$i]['id_nota'];

			$query2 = 'SELECT notas.nota, (SELECT COUNT(id_pregunta) FROM preguntas
 									WHERE examen=(SELECT examen FROM notas WHERE id_nota='.$idNota.')) AS preguntas
						FROM notas
						INNER JOIN examenes ON examenes.id_examen=notas.examen
						WHERE notas.id_nota='.$idNota.' LIMIT 1';

			if ($result = $mysqli->query($query2)) {

				$data = array();
				$json[$i]['puntuacion'] = "No examinado";
				while($row = $result->fetch_assoc()){
					$json[$i]['puntuacion'] = $row['nota']."/".$row['preguntas'];
					$json[$i]['nota'] = ($row['nota']*10)/$row['preguntas'];
				}

				$result->free();

			} else {
				$json[$i]['puntuacion'] = "Error al consultar puntuación.";
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

