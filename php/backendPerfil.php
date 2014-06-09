<?php

	header('Access-Control-Allow-Origin: *');
	require("conn.php");

	// VARIABLES
	$usuario;
	$action;
	$email;
	$nombre;
	$apellidos;
	$pass1;
	$pass2;

	// RECOGER PARAMETROS
	if (isset($_POST['usuario']) && isset($_POST['action'])) {
		$usuario = $_POST['usuario'];
		$actions = array('select', 'update');

		switch ($_POST['action']) {

		    case $actions[0]:
		    	selectUsuario();
		        break;

		    case $actions[1]:
		    	updateUsuario();
		    	break;

		}

	} else {
		$data = array();
		$data['state'] = false;
		echo json_encode($data);
	}



	function selectUsuario() {
		global $mysqli, $usuario, $action, $email, $nombre, $apellidos, $pass1, $pass2;

		$query = 'SELECT email, nombre, apellidos FROM usuarios WHERE id_usuario=? LIMIT 1';

		$stmt = $mysqli->stmt_init();
		if ($stmt = $mysqli->prepare($query)) {

			$stmt->bind_param('i', $usuario);

			$stmt->execute();
			$stmt->bind_result($res_email, $res_nombre, $res_apellidos);

			$data = array();
			if ($stmt->fetch()) {
				$data['email'] = $res_email;
				$data['nombre'] = $res_nombre;
				$data['apellidos'] = $res_apellidos;
				$data['state'] = true;

			} else {
				$data['state'] = false;
			}

			echo json_encode($data);

		} else {
			$data = array();
			$data['state'] = false;
			echo json_encode($data);
		}

	}

	function updateUsuario() {
		global $mysqli, $usuario, $action, $email, $nombre, $apellidos, $pass1, $pass2;
		$centinela = false;

		$bindParam = new BindParam();
		$qArray = array();

		$query = 'UPDATE usuarios SET ';

		// EMAIL
		if (isset($_POST['email'])) {
			$email = $_POST['email'];

			if (!empty($email)) {
				if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
					// EMAIL EXISTENTE
					$queryEmail = "SELECT COUNT(id_usuario) AS count FROM usuarios  WHERE email=? LIMIT 1";
					$stmtEmail = $mysqli->stmt_init();
					if ($stmtEmail = $mysqli->prepare($queryEmail)) {

					    $stmtEmail->bind_param('s', $email);

					    $stmtEmail->execute();
					    $stmtEmail->bind_result($res_count_email);
					    $stmtEmail->fetch();

					    if ($res_count_email == 0) {
					    	$qArray[] = 'email = ?';
						    $bindParam->add('s', $email);
						    $centinela = true;
					    }
					    $stmtEmail->close();
					}
				}
			}
		}

		// NOMBRE
		if(isset($_POST['nombre'])){
			$nombre = $_POST['nombre'];

			if (!empty($nombre)) {
				$qArray[] = 'nombre = ?';
			    $bindParam->add('s', $nombre);
			    $centinela = true;
			}
		}

		// APELLIDOS
		if(isset($_POST['apellidos'])){
			$apellidos = $_POST['apellidos'];

			if (!empty($apellidos)) {
				$qArray[] = 'apellidos = ?';
			    $bindParam->add('s', $apellidos);
			    $centinela = true;
			}
		}

		// PASSWORD
		if(isset($_POST['pass1']) && isset($_POST['pass2'])){
			$pass1 = $_POST['pass1'];
			$pass2 = $_POST['pass2'];

			if (!empty($pass1) && !empty($pass2)) {
				if (!strlen($pass1)<6 && !($pass1 != $pass2)) {
					$passCod = md5(md5($pass1));
					$qArray[] = 'password = ?';
				    $bindParam->add('s', $passCod);
				    $centinela = true;
				}
			}
		}


		$query .= implode(', ', $qArray);
		$query .= ' WHERE id_usuario = ?';
		$bindParam->add('i', $usuario);

		if ($centinela) {
			$stmt = $mysqli->prepare($query);

			$params = $bindParam->get();
			$tmp = array();
	        foreach($params as $key => $value) $tmp[$key] = &$params[$key];
			call_user_func_array(array($stmt, 'bind_param'), $tmp);

			$stmt->execute();
			$res = $stmt->affected_rows;

			// Cerrar statement
			$stmt->close();

			if ($res > 0) {
				$data = array();
				$data['state'] = true;
				echo json_encode($data);

			} else {
				$data = array();
				$data['state'] = false;
				echo json_encode($data);
			}

		} else {
			$data = array();
			$data['state'] = false;
			echo json_encode($data);
		}
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

